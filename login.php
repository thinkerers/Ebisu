<?php 
$title = 'Login';
ob_start(); 
?>
<style>
  @import url(./vue/form.css);
</style>
<form method="post" action="authenticate.php">
  <fieldset>
    <legend>Login</legend>
    <label>
      Email
      <input name="email" type="email" required>
    </label>
    <label>
      Mot de passe
      <input name="password" type="password">
    </label>
    <input type="submit" value="Login"/>
  </fieldset>
</form>

<?php
$content = ob_get_clean();
require('vue/layout.php');
?>