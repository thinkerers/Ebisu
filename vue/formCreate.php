<?php
// Cette page permet de créer un compte utilisateur
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création de compte</title>
    <link rel="stylesheet" href="form.css">
</head>
<body>
<!-- https://www.nngroup.com/articles/form-design-placeholders -->
<form method="post" action="self" >
    <fieldset>
        <legend>Connexion</legend>
        <label>
            Email
            <small id="emailHint">Veuillez entrer un email valide"</small>
            <input 
              name="email" 
              type="email" 
              minlength="5" 
              maxlength="50"
              required
              autofocus
              aria-describedby="emailHint"
            >
        </label>
        <label>
          Mot de passe
          <small id="emailHint">(optionnel) Le mot de passe doit être de minimum 6 caractères et contenir au moins une majuscule, une minuscule, un nombre et un caractère spécial.</small>
          <input 
            name="password"
            type="password"
            minlength="6"
            maxlength="50"
            pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{6,}"
            aria-describedby="passwordHint"
          >
        </label>
        <input name="submit" type="submit"/>
    </fieldset>
</form>
</body>
</html>