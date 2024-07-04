<?php

namespace src\model;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception as PHPMailerException;
use src\lib\Logger;
use src\lib\LogLevels;

/**
 * Class UsersRepository
 *
 * Provides data access and manipulation operations for the "users" table.
 */
class UsersRepository
{
    public function __construct(
        private ?dbConnect $db = null,
        public ?UsersEntity $user = null,
        public ?string $challengePassword = null,
        public ?string $challengeVerificationToken = null,
        public ?bool $isDebugMode = true,
        private ?Logger $logger = new Logger()
    )
    {}

    private function logMessage(string $message, LogLevels $logLevel = LogLevels::INFO, ?object $dump = null): void
    {
        $dump ??= $this->user;

        if (!$dump) {
            throw new \Exception("Cannot log message without a valid Object.");
        }

        $this->logger->logMessage($message, $dump, $logLevel);
    }

    /**
     * Executes a SQL statement on the database.
     * 
     * @param string $sql The SQL statement to execute.
     * @param array|null $fieldsToBind The fields to bind to the statement (default to all).
     * @return \SQLite3Result The result of the query.
     * @throws \Exception If an error occurs during the database operation.
     */
    private function executeStatement(string $sql, array $fieldsToBind = null):\SQLite3Result
    {
        $statement = $this->db->prepare($sql);
        $fieldMap = $this->user->fieldMap();
        $fieldsToBind = $fieldsToBind ?? array_keys($fieldMap); 
        
        foreach ($fieldsToBind as $field) {
            if (isset($fieldMap[$field])) {
                // Explicitly check if the field exists on the object
                if (property_exists($this->user, $field)) {
                    $statement->bindValue(":{$field}", $this->user->{$field}, $fieldMap[$field]);
                } else {
                    $this->logMessage("Property '$field' does not exist on user object", LogLevels::WARNING);
                }
            }
        }

        if (!$result = $statement->execute()) {
            $this->logMessage("Failed to execute statement", LogLevels::ERROR);
            throw new \Exception("Échec de l'exécution de la requête.");
        }

        return $result;
    }

    /**
     * Fetches a user account by the user entity ID.
     *
     * @return UsersEntity|null The user entity found, or null if no user is found.
     * @throws \Exception If an error occurs during the database query.
     */
    public function getByID(): ?UsersEntity
    {
        return $this->getBy('id');
    }

    /**
     * Fetches a user account by the user entity email.
     *
     * @return UsersEntity|null The user entity found, or null if no user is found.
     * @throws \Exception If an error occurs during the database query.
     */
    public function getByEmail(): ?UsersEntity
    {
        return $this->getBy('email');
    }
 
    /**
     * Fetches a user account by the specified field.
     *
     * @param string $field The field to search by.
     * @param int|string $value The value to search for.
     * @param int $type The type of the value to search for.
     * @return UsersEntity|null The user entity found, or null if no user is found.
     * @throws \Exception If an error occurs during the database query.
     */
    private function getBy(string $field): ?UsersEntity
    {
        $this->logMessage("Attempting to get user from database by $field", LogLevels::INFO);
        try {
            $result = $this->executeStatement("SELECT * FROM users WHERE $field = :value", [$field]);
    
            if(!$result){
                $this->logMessage("User not found by $field", LogLevels::WARNING);
                return null;
            }

            $row = $result->fetchArray(SQLITE3_ASSOC);

            $this->logMessage("User found: " . print_r($row, true), LogLevels::DEBUG);

            $user = new UsersEntity();
            foreach ($this->user->fieldMap() as $field) {
                if (!isset($row[$field])) {
                    $this->logMessage("Field '$field' not found in user data", LogLevels::WARNING);
                    continue;
                }
                $user->{$field} = $row[$field];
            }

            return $user;

        } catch (\Exception $e) {
            $this->logMessage("Error while fetching user data: " . $e->getMessage(), LogLevels::ERROR);
            throw new \Exception("Error retrieving user account data.");
        }
    }

   
    /**
     * Checks if the email address is already in use.
     *
     * @return bool True if the email address is already in use, false otherwise.
     */
    private function isEmailInUse(): bool
    {
        return $this->getByEmail() ?? false;
    }

    /**
     * Creates a new user account and send a verification email.
     * @return UsersEntity The user entity created.
     * @throws \Exception If an error occurs during account creation.
     */
    public function create(): ?UsersEntity
    {
        $this->logMessage("Attempting to create user", LogLevels::INFO); 
        if($this->user->isCreated()) {
            $this->logMessage("User already exist", LogLevels::ERROR); 
            throw new \Exception("L'utilisateur existe déjà.");
        }
        if (!$this->user->isValid()) {
            $this->logMessage("Cannot create user without email and password", LogLevels::ERROR); 
            throw new \Exception("Email ou mot de passe manquant.");
        }

        if($this->isEmailInUse()){
            $this->logMessage("Email already in use", LogLevels::ERROR); 
            throw new \Exception("L'email est déjà utilisé.");
        }

        try {
            $this->user->prepareVerification();
            $this->sendVerificationEmail();
            $this->insert();
            $this->logMessage("User created", LogLevels::DEBUG); 
            return $this->user;

        } catch (\Exception $e) {
            $this->logMessage("Failed to create user". $e->getMessage(), LogLevels::ERROR);
            throw new \Exception("Erreur lors de la création du compte.");
        }
    }

    /**
     * Inserts the user account into the database.
     * 
     * @return true If the user account was successfully inserted.
     * @throws \Exception If there is an error during the insertion process.
     */
    public function insert():bool
    {
        $this->logMessage("Try to insert user", LogLevels::INFO);
        $result = $this->executeStatement(
            'INSERT INTO users (email, hashedPassword, verified, verification_token, token_expiry) 
             VALUES (:email, :hashedPassword, :verified, :verification_token, :token_expiry)'
        );

        if(!$result){
            $this->logMessage("Failed to insert user", LogLevels::ERROR);
            throw new \Exception("L'utilisateur n'a pas pu être inséré.");
        }

        $userId = $this->db->lastInsertRowID();
        $this->user->setId($userId);
        return true;
    }

    /**
     * Updates the user account.
     * 
     * @return bool True if the user account was successfully updated.
     * @throws \Exception If there is an error during the update process.
     */
    public function update():bool
    {
        $this->logMessage("Try to update user", LogLevels::INFO);
        $result = $this->executeStatement(
           'UPDATE users 
             SET
                email = :email, 
                hashedPassword = :hashedPassword, 
                verified = :verified, 
                verification_token = :verification_token, 
                token_expiry = :token_expiry
             WHERE id = :id'
        );

        if(!$result){
            $this->logMessage("Failed to insert user", LogLevels::ERROR);
            throw new \Exception("L'utilisateur n'a pas pu être inséré.");
        }

        return true;
    }


    /**
     * Prepare the user for verification by resetting the verification token and expiry date. 
     * 
     * @param UsersEntity $user The user entity to update.
     * @return UsersEntity return the user prepared for verification if the token reset was successful, false otherwise.
     */
    public function prepareVerification(): UsersEntity
    {
        $this->logMessage("Preparing user for verification", LogLevels::INFO); 
        try {
            if($this->user->isReadyForVerification()){
                $this->logMessage("User is already ready for verification", LogLevels::DEBUG); 
                return $this->user;
            }

            $this->user->prepareVerification();
            $this->sendVerificationEmail();
            $this->update();
            return $this->user;

        } catch (\Exception $e) { 
            $this->logMessage("Error preparing user for verification". $e->getMessage(), LogLevels::ERROR);
            throw new \Exception("La préparation de la vérification de l'utilisateur a échoué.");
        }
    }
    
    /**
     * Send a verification email to the user
     * 
     * @return bool True if the email is sent successfully, false otherwise.
     */
    public function sendVerificationEmail(): bool
    {
        $this->logMessage("Try to send verification email", LogLevels::INFO);
        try {
            if(!$this->user->isCreated()){
                $this->logMessage("User does not exist", LogLevels::ERROR);
                throw new \Exception("L'utilisateur n'existe pas.");
            }
            if(!$this->user->email){
                $this->logMessage("Mail missing", LogLevels::ERROR);
                throw new \Exception("Le mail est manquant.");
            }
    
            if(!$this->user->isReadyForVerification()){
                $this->logMessage("User must be ready for verification before sending verification email", LogLevels::ERROR);
                throw new \Exception("Le compte n'est pas prêt pour la vérification.");
            }
            
            $emailSent = $this->sendEmail(
                subject:"Vérification de votre adresse mail",
                message:"Pour valider votre adresse mail, veuillez cliquer sur <a href='http://ebisu.test/?action=verify&token={$this->user->verificationToken}'>Verifier mon compte</a>"
            );

            if(!$emailSent){
                $this->logMessage("Failed to send verification email", LogLevels::ERROR);
                throw new \Exception("Le mail n'a pas pu être envoyé.");
            }

            $this->logMessage("Verification email sent successfully", LogLevels::DEBUG);
            return true;

        } catch (\Exception $e) {
            $this->logMessage("Error sending verification email". $e->getMessage(), LogLevels::ERROR);
            throw new \Exception("Le mail de vérification n'a pas pu être envoyé.");
        }
    }
    
    /**
     * Verifies a user account.
     *
     * @return bool True if the user is successfully verified, false otherwise.
     * @throws \Exception If there is an error during the verification process.
     */
    public function verify(): bool {
        $this->logMessage("Try to verify user", LogLevels::INFO);
        try {
            if ($this->user->verified) {
                $this->logMessage("User is already verified", LogLevels::DEBUG);
                return true;
            }

            if(!$this->user->isReadyForVerification()){
                $this->logMessage("Preparing user for verification", LogLevels::DEBUG);
                $this->prepareVerification();
            }

            if($this->user->verificationToken !== $this->challengeVerificationToken){
                $this->logMessage("Verification token does not match", LogLevels::ERROR);
                return false;
            }

            $this->executeStatement(
                'UPDATE users SET verified = 1 WHERE id = :id AND verified = 0',
                ['id']
             );

            
            $this->user->setVerified(true);
            $this->logMessage("User verification successfull", LogLevels::DEBUG);
            return true;
        } catch (\Exception $e) {
            $this->logMessage("Error verifying user". $e->getMessage(), LogLevels::ERROR);
            throw new \Exception("Une erreur s'est produite durant la vérification du compte.");
        }
    }

    /**
     * Deletes the user account.
     * 
     * @return bool True if the account was successfully deleted, false otherwise.
     * @throws \Exception If there is an error during the deletion process.
     */
    public function delete(): bool
    {
        $this->logMessage("Try to delete user", LogLevels::INFO);
        $result = $this->executeStatement(
            'DELETE FROM users WHERE id = :id',
            ['id']
         );
         if(!$result){
            $this->logMessage("Failed to delete user", LogLevels::ERROR);
            throw new \Exception("L'utilisateur n'a pas pu être supprimé.");
         }
        return true;
    }

    /**
     * Updates a user's email address and sends a verification email.
     * 
     * @return bool True if the email was successfully updated and the verification email was sent, false otherwise.
     * @throws \Exception If there is an error during the update or email sending process.
     */
    public function editEmail(): bool
    {   
        $this->logMessage("Try to update mail", LogLevels::INFO);
        try{
            if(!$this->user->isReadyForVerification()){
                $this->prepareVerification();
            }

            if(!$this->user->verified){
                $this->verify();
            }

            $result = $this->executeStatement(
                'UPDATE users SET email = :email WHERE id = :id',
                ['email', 'id']
             );

             if(!$result){
                $this->logMessage("Failed to update email", LogLevels::ERROR);
                throw new \Exception("L'adresse mail n'a pas pu être mise à jour.");
             }

             return true;
             
            } catch (\Exception $e) {
                $this->logMessage("Error updating email". $e->getMessage(), LogLevels::ERROR);
                throw new \Exception("Erreur lors de la mise à jour de l'adresse mail.");
            }
    }
        
    /**
     * Edits the user's password.
     *
     * @return bool True if the password was successfully updated.
     * @throws \Exception If there is an error during the update process.
     */
    public function editPassword():bool
    {
        $this->logMessage("Try to update password", LogLevels::INFO);
        try{
            $result = $this->executeStatement(
                'UPDATE users
                SET hashedPassword = :hashedPassword
                WHERE id = :id',
               ['hashedPassword', 'id']
             );

             if(!$result){
                $this->logMessage("Failed to update email", LogLevels::ERROR);
                throw new \Exception("L'adresse mail n'a pas pu être mise à jour.");
             }

             return true;

        } catch (\Exception $e) {
            $this->logMessage("Error updating password". $e->getMessage(), LogLevels::ERROR);
            throw new \Exception("Erreur lors de la mise à jour du mot de passe.");
        }
    }
    
    /**
     * Sends an email to the specified user.
     *
     * @param string $subject The subject of the email.
     * @param string $message The HTML body of the email.
     * @return bool True if the email was sent successfully.
     * @throws PHPMailerException If there was an error sending the email.
     */
    public function sendEmail(string $subject, string $message): bool
    {
        $PHPMailer = new PHPMailer(true); // Enable exceptions

        try{
         //$mail->SMTPDebug = SMTP::DEBUG_SERVER;//Info for debugging

         //Avec MailHog    
         //$mail->SMTPDebug = PHPMailer::DEBUG_SERVER;
         $PHPMailer->isSMTP();
         $PHPMailer->Host = 'localhost';
         $PHPMailer->Port = 1025; // Port par défaut de MailHog

         $PHPMailer->CharSet = 'UTF-8';

         //Destinataire
         $PHPMailer->addAddress($this->user->email);
        
         //Expéditeur
         $PHPMailer->setFrom('no-replay@ebisu.be', 'Ebisu');

         $PHPMailer->isHTML();
        
         $PHPMailer->Subject = $subject;
         $PHPMailer->Body = $message;
        
         if(!$PHPMailer->send()){
            $this->logMessage("Failed to send email", LogLevels::ERROR);
            throw new PHPMailerException("Message could not be sent.");
         }

        $this->logMessage("Email sent", LogLevels::DEBUG);
        return true;
         
        }catch (PHPMailerException $e){
            $this->logMessage("Error sending email". $e->getMessage(), LogLevels::ERROR);
            throw new PHPMailerException("Erreur lors de l'envois du mail.");
        }
    }

    
    /**
     * Authenticates the user by verifying their password.
     *
     * @return bool True if the user is authenticated, false otherwise.
     */
    public function login(): bool
    {
        $this->logMessage("Try to login", LogLevels::INFO);
        try {
            $passwordMatches = password_verify($this->challengePassword,$this->user->hashedPassword);

            if (!$passwordMatches) {
                $this->logMessage("Invalid password", LogLevels::ERROR);
                throw new \Exception("Mot de passe incorrect.");
            } 
            return true;
        } catch (\Exception $e) {
            $this->logMessage("Error logging in". $e->getMessage(), LogLevels::ERROR);
            throw new \Exception("Erreur lors de la connexion.");
        }
    }

    /**
     * Logs out from session.
     *
     * Destroys the session and redirects to the home page.
     */
    public function logout(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $_SESSION = array();
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();

        header('Location: /'); 
        exit;
    }
}
