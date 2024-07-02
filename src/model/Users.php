<?php

namespace src\model;

// use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;


/**
 * Class Users
 *
 * Represents a record in the "users" table.
 *
 * @property int $id The unique identifier of the user.
 * @property string $email The email address of the user.
 * @property string $hashedPassword The hashed password of the user.
 */
class Users
{
    public function __construct(
        public ?int $id = null,
        public ?string $email = null,
        public ?string $hashedPassword = null,
        private ?dbConnect $db = null
    )
    {}

    /**
     * Creates a new user account.
     *
     * @param string $email User's email address.
     * @param string $password User's password (plain text).
     * @return bool True on success, false on failure.
     * @throws \Exception If an error occurs during account creation.
     */
    public function create($email, $password)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $verificationToken = bin2hex(random_bytes(16));
        $tokenExpiry = date("Y-m-d H:i:s", strtotime('+60 minutes'));

        try {
            $statement = $this->db->prepare('INSERT INTO users (email, hashedPassword, verification_token, token_expiry) VALUES (:email, :hashedPassword, :verification_token, :token_expiry)');
            $statement->bindValue(':email', $email);
            $statement->bindValue(':hashedPassword', $hashedPassword);
            $statement->bindValue(':verification_token', $verificationToken);
            $statement->bindValue(':token_expiry', $tokenExpiry);
            $statement->execute();
            $this->sendVerificationEmail($email, $verificationToken);
        } catch (\Exception $e) {
            error_log("Account creation error: " . $e->getMessage());
            throw new \Exception("Erreur lors de la création du compte.");
        }
    }    
    /**
     * Get the token linked to the email passed as argument
     *
     * @param  mixed $email
     * @return string The token linked to the email passed as argument.
     * @throws \Exception If an error occurs during token retrieval.
     */
    public function getToken($email){
        try{
            $statement = $this->db->prepare('SELECT verification_token FROM users WHERE email = :email');
            $statement->bindValue(':email', $email);
            $result = $statement->execute();
            if ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                return $row['verification_token'];
            } else{
                throw new \Exception("Utilisateur introuvable.");
            }
        } catch (\Exception $e) {
            error_log("Account getToken error: " . $e->getMessage());
            throw new \Exception("Erreur lors de la recupération du Token.");
        }
    }    
    /**
     * Reset the token and token expiry date to 10 minutes after the current time
     *
     * @param  mixed $email
     * @return void
     * @throws \Exception If an error occurs during token reset.
     */
    public function resetTokenExpiry($email){
        $verificationToken = bin2hex(random_bytes(16));
        $tokenExpiry = date("Y-m-d H:i:s", strtotime('+10 minutes'));
        try{
            $statement = $this->db->prepare('UPDATE users SET verification_token = :verification_token, token_expiry = :token_expiry, is_verified = :is_verified WHERE email = :email');
            $statement->bindValue(':email', $email);
            $statement->bindValue(':verification_token', $verificationToken);
            $statement->bindValue(':token_expiry', $tokenExpiry);
            $statement->bindValue(':is_verified', 0);
            $result = $statement->execute();
            
        } catch (\Exception $e) {
            error_log("Account resetTokenExpiry error: " . $e->getMessage());
            throw new \Exception("Erreur lors de la création du Token.");
        }
    }
    
    /**
     * Send a verification email to the user
     *
     * @param  mixed $email
     * @param  mixed $verificationToken
     * @return void
     * @throws \Exception If an error occurs during email sending.
     */
    public function sendVerificationEmail($email, $verificationToken){
        $verificationLink = "http://ebisu.test/?action=verify&token=$verificationToken";
        $subject = "Vérification de votre adresse mail";
        $message = "Pour valider votre adresse mail, veuillez cliquer sur <a href=" . $verificationLink .">Verifier mon compte</a>";
        try{
        $this->sendEmail($subject, $message, $email);
        } catch (\Exception $e) {
        error_log("Account sendVerificationEmail error: " . $e->getMessage());
        throw new \Exception("Erreur lors de l'envoie du mail de vérification.");
        }
    }
    
    /**
     * Verify the token passed as argument
     *
     * @param  mixed $token
     * @return bool True if the token is valid, false otherwise
     */
    public function verify($token) {
        try {
            error_log("Verification start for token: " . $token);
    
            // Préparer et exécuter la requête SELECT
            $statement = $this->db->prepare("SELECT email, token_expiry FROM users WHERE verification_token = :token AND is_verified = 0");
            $statement->bindValue(':token', $token, SQLITE3_TEXT); // Spécifiez le type de données
            $result = $statement->execute();
            $row = $result->fetchArray(SQLITE3_ASSOC);
    
            if ($row) {
                error_log("Token found: " . print_r($row, true));
                $tokenExpiry = strtotime($row['token_expiry']);
                $current_time = time();
    
                if ($current_time < $tokenExpiry) {
                    error_log("Token not expired. Updating verification status.");
    
                    // Préparer et exécuter la requête UPDATE
                    $updateStatement = $this->db->prepare("UPDATE users SET is_verified = 1 WHERE verification_token = :token");
                    $updateStatement->bindValue(':token', $token, SQLITE3_TEXT); // Spécifiez le type de données
                    $updateStatement->execute();
                    return true;
                } else {
                    throw new \Exception("Token expired.");
                    // Token expiré
                }
            } else {
                throw new \Exception("No matching token found or already verified.");
                // Aucun utilisateur trouvé avec ce token
            }
        } catch (\Exception $e) {
            error_log("Account verification error: " . $e->getMessage());
            throw new \Exception("Erreur lors de la vérification.");
        }
    }

    public function is_verified($email){
        try{
            $statement = $this->db->prepare('SELECT is_verified FROM users WHERE email = :email');
            $statement->bindValue(':email', $email);
            $result = $statement->execute();
            if ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                return $row['is_verified'];
            } else{
                throw new \Exception("Utilisateur introuvable.");
            }
        } catch (\Exception $e) {
            error_log("Account is_verified error: " . $e->getMessage());
            throw new \Exception("Erreur lors de la recupération du Token.");
        }
    }

    /**
     * Delete the account linked to the email passed as argument
     *
     * @param string $email the email of the account to delete
     * @return bool True if the account has been deleted, false otherwise.
     */
    public function delete($email)
    {
        if ($_SESSION['user'] == $email) {
            try {
                $statement = $this->db->prepare('DELETE FROM users WHERE email = :email');
                $statement->bindParam(':email', $email);
                $statement->execute();
                return true;
            } catch (\Exception $e) {
                error_log("Account delete error: " . $e->getMessage());
                throw new \Exception("Erreur lors de la suppression du compte.");
            }
        }
    }
    
    /**
     * Edit the email of the account linked to the email passed as argument
     *
     * @param  mixed $email
     * @return bool True if the email has been edited, false otherwise.
     * @throws \Exception If an error occurs during email edition.
     */
    public function editEmail($email)
    {        
        $verificationToken = bin2hex(random_bytes(16));
        $tokenExpiry = date("Y-m-d H:i:s", strtotime('+10 minutes'));
        try{
            $statement = $this->db->prepare('UPDATE users SET email = :email, is_verified = :is_verified, verification_token = :verification_token, token_expiry = :token_expiry WHERE email = :oldEmail');
            $statement->bindParam(':email', $email);
            $statement->bindValue(':is_verified', 0);
            $statement->bindParam(':verification_token', $verificationToken);
            $statement->bindParam(':token_expiry', $tokenExpiry);
            $statement->bindParam(':oldEmail', $_SESSION['user']);
            $statement->execute();
            $this->sendVerificationEmail($email, $verificationToken);
                return true;  
            } catch (\Exception $e) {
                error_log("Account editEmail error: " . $e->getMessage());
                throw new \Exception("Erreur lors du changemant d'email.");
            }
    }
        
    /**
     * Edit the password of the email linked to the session
     *
     * @param  mixed $password
     * @return bool True if the password has been edited, false otherwise.
     */
    public function editPassword($password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        try{
        $statement = $this->db->prepare('UPDATE users SET hashedPassword = :hashedPassword WHERE email = :email');
        $statement->bindParam(':hashedPassword', $hashedPassword);
        $statement->bindParam(':email', $_SESSION['user']);
        $statement->execute();
            return true;   
        } catch (\Exception $e) {
            error_log("Account zditPassword error: " . $e->getMessage());
            throw new \Exception("Erreur lors du changement de mot de passe.");
        }
    }
    
    /**
     * Send an email to the user to change his password
     * 
     * @return bool True if the email as been send, false otherwise.
    */
    public function sendEmail($subject, $message, $email){
        require_once($_SERVER['DOCUMENT_ROOT'].'/includes/PHPMailer/Exception.php');
        require_once($_SERVER['DOCUMENT_ROOT'].'/includes/PHPMailer/PHPMailer.php');
        require_once($_SERVER['DOCUMENT_ROOT'].'/includes/PHPMailer/SMTP.php');

        $mail = new PHPMailer(true);

        try{
         //$mail->SMTPDebug = SMTP::DEBUG_SERVER;//Info for debugging

         //Avec MailHog    
         //$mail->SMTPDebug = PHPMailer::DEBUG_SERVER;
         $mail->isSMTP();
         $mail->Host = 'localhost';
         $mail->Port = 1025; // Port par défaut de MailHog

         $mail->CharSet = 'UTF-8';
         $mail->CharSet = 'UTF-8';

         //Destinataire
         $mail->addAddress($_SESSION['user'] ?? $email);
        
         //Expéditeur
         $mail->setFrom('no-replay@ebisu.be', 'Ebisu');

         $mail->isHTML();
        
         $mail->Subject = $subject;
         $mail->Body = $message;
        
         $mail->send();
         return true;
        }catch ( \PHPMailer\PHPMailer\Exception){
            throw new  \PHPMailer\PHPMailer\Exception("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
            return false;
        }
    }

    /**
     * Logs out the current user.
     *
     * Destroys the session and redirects to the home page.
     */
    public function logout()
    {
        session_start();
        session_destroy();
        header('Location: /');
    }

    /**
     * Retrieves the hashed password for a given email address.
     *
     * @param string $email User's email address.
     * @return string The hashed password.
     * @throws \Exception If the user is not found.
     */
    public function getUserHash($email)
    {
        try {
            $statement = $this->db->prepare('SELECT hashedPassword FROM users WHERE email = :email');
            $statement->bindValue(':email', $email, SQLITE3_TEXT);
            $result = $statement->execute();
            if ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                return $row['hashedPassword'];
            } else{
                throw new \Exception("User not found.");
            }
        }
       
        catch(\Exception $e){
            error_log("User not found: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Logs the user in if valid credentials are provided.
     *
     * @param string|null $email The email address of the user.
     * @param string|null $password The password of the user.
     * @return bool True if the user is authenticated, false otherwise.
     */
    public function login($email = null, $password = null)
    {
        $hashedPassword = $this->getUserHash($email);
        return password_verify($password, $hashedPassword);
    }

    /**
     * Retrieves the user ID for a given email address.
     *
     * @param string $email User's email address.
     * @return int The user ID.
     * @throws \Exception If the user is not found.
     */
    public function getUserId($email)
    {
        try {
            $statement = $this->db->prepare('SELECT id FROM users WHERE email = :email');
            $statement->bindValue(':email', $email, SQLITE3_TEXT);
            $result = $statement->execute();
            if ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                return $row['id'];
            } else{
                throw new \Exception("User not found.");
            }
        }
       
        catch(\Exception $e){
            error_log("User not found: " . $e->getMessage());
            return false;
        }
    }
}
