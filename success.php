<?php 
$title = 'Success';
ob_start(); 
?>
<h1>Account Created Successfully</h1>
<p>Thank you for creating an account. Your account has been successfully created.</p>
<p>You can now <a href="login.php">log in</a> to your account.</p>
<?php
$content = ob_get_clean();
require('templates/layout.php');
?>