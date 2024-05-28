<?php if (isset($error)) { ?>
  <p style="color: red;"><?php echo $error; ?></p>
<?php } ?>
<!-- https://www.nngroup.com/articles/form-design-placeholders -->
<style>
  @import url(./vue/form.css);
</style>
<form method="post">
  <fieldset>
    <legend>Création de compte</legend>
    <label>
      Email
      <small id="emailHint">Veuillez entrer un email valide</small>
      <input name="email" type="email" minlength="5" maxlength="50" required autofocus aria-describedby="emailHint">
    </label>
    <label>
      Mot de passe
      <small id="emailHint">(optionnel) Le mot de passe doit être de minimum 6 caractères et contenir au moins une majuscule, une minuscule, un nombre et un caractère spécial.</small>
      <input name="password" type="password" minlength="6" maxlength="50" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{6,}" aria-describedby="passwordHint">
    </label>
    <input name="submit" type="submit" />
  </fieldset>
</form>