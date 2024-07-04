<?php

namespace src\model;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

class LogLevels
{
    const DEBUG = 'debug';
    const INFO = 'info';
    const WARNING = 'warning';
    const ERROR = 'error';
}

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
        public ?bool $isDebugMode = false
    )
    {
        // $this->isDebugMode = getenv('APP_ENV') === 'development';
        $this->isDebugMode = true;
    }

    public function logMessage(string $message, string $logLevel = LogLevels::INFO): void
    {
        if (!$this->user) {
            throw new \Exception("Cannot log message without a valid UsersEntity.");
        }

        if (!$this->isDebugMode && $logLevel === LogLevels::DEBUG) {
            return;
        }

        $logFilePath = 'UsersRepository.log';
        $timestamp = date('Y-m-d H:i:s');
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        $caller = $backtrace[1];

        $logMessage = sprintf(
            "[%s] [%s] User ID %s (%s): %s\n
            Called from: %s on line %s in function %s\n
            ------------------------------\n",
            $timestamp,
            $logLevel,
            $this->user->id,
            $this->user->email,
            $message,
            $caller['file'],
            $caller['line'],
            $caller['function']
        );

        if (!file_put_contents($logFilePath, $logMessage, FILE_APPEND)) {
            error_log("Failed to write to log file: $logFilePath");
        }
    }

   /**
     * Executes a prepared statement and fetches a row from the database.
     *
     * @param string $sql The SQL query to execute.
     * @param array|null $fieldsToBind The fields to bind to the prepared statement.
     * @param int $resultMode The mode of result fetching (e.g., SQLITE3_ASSOC).
     * @return array|false The row fetched from the database, or false if no row was found.
     * @throws \Exception If the statement execution fails.
     */
    private function executeStatement(string $sql, array $fieldsToBind = null, int $resultMode = SQLITE3_ASSOC): array|false
    {
        $statement = $this->db->prepare($sql);
        
        $fieldMap = [
            'id' => SQLITE3_INTEGER,
            'email' => SQLITE3_TEXT,
            'hashedPassword' => SQLITE3_TEXT,
            'verificationToken' => SQLITE3_TEXT,
            'verified' => SQLITE3_INTEGER,
            'tokenExpiry' => SQLITE3_TEXT,
        ];

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

        $result = $statement->execute();
        $row = $result->fetchArray($resultMode);

        if (!$row) {
            $this->logMessage("Failed to execute statement", LogLevels::ERROR);
            throw new \Exception("Échec de l'exécution de la requête.");
        }

        return $row;
    }

    /**
     * Fetches a user account by the user entity ID.
     *
     * @return UsersEntity|null The user entity found, or null if no user is found.
     * @throws \Exception If an error occurs during the database query.
     */
    public function getByID(): ?UsersEntity
    {
        return $this->getUserBy('id', $this->user->id, SQLITE3_INTEGER);
    }

    /**
     * Fetches a user account by the user entity email.
     *
     * @return UsersEntity|null The user entity found, or null if no user is found.
     * @throws \Exception If an error occurs during the database query.
     */
    public function getByEmail(): ?UsersEntity
    {
        return $this->getUserBy('email', $this->user->email, SQLITE3_TEXT);
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
    private function getUserBy(string $field, int|string $value, int $type): ?UsersEntity
    {
        $this->logMessage("Attempting to get user from database by $field", LogLevels::INFO);
        try {
            $statement = $this->db->prepare("SELECT * FROM users WHERE $field = :value");
            $statement->bindValue(':value', $value, $type);
            $result = $statement->execute();
            $row = $result->fetchArray(SQLITE3_ASSOC);

            if (!$row) {
                $this->logMessage("User not found by $field: $value", LogLevels::WARNING);
                throw new \Exception("Utilisateur introuvable.");
            }

            $this->logMessage("User found: " . print_r($row, true), LogLevels::DEBUG);
            return new UsersEntity(
                id: $row['id'],
                email: $row['email'],
                hashedPassword: $row['hashedPassword'],
                verified: $row['verified'],
                verificationToken: $row['verification_token'],
                tokenExpiry: new \DateTime($row['token_expiry'])
            );
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
    public function create(): UsersEntity
    {
        $this->logMessage("Attempting to create user", LogLevels::INFO); 
        if($this->user->isCreated()) {
            $this->logMessage("User already exist", LogLevels::ERROR); 
            throw new \Exception("L'utilisateur existe déjà.");
        }
        if (!$this->user->hasCredentials()) {
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
        return $this->executeStatement(
            'INSERT INTO users (email, hashedPassword, verified, verification_token, token_expiry) 
             VALUES (:email, :hashedPassword, :verified, :verification_token, :token_expiry)'
        );
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
        return $this->executeStatement(
           'UPDATE users 
             SET
                email = :email, 
                hashedPassword = :hashedPassword, 
                verified = :verified, 
                verification_token = :verification_token, 
                token_expiry = :token_expiry
             WHERE id = :id'
        );
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
        return $this->executeStatement(
            'DELETE FROM users WHERE id = :id',
            ['id']
         );
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

            return $this->executeStatement(
                'UPDATE users SET email = :email WHERE id = :id',
                ['email', 'id']
             );
             
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
            return $this->executeStatement(
                'UPDATE users
                SET hashedPassword = :hashedPassword
                WHERE id = :id',
               ['hashedPassword', 'id']
             );
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
