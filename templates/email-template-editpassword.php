<!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <style>
        @font-face {
            font-family: 'Pixellari';
            src: url('/public/fonts/Pixellari.ttf');
        }
        :root{
        --color-primary: #A35647;
        --color-secondary: #FEDAC1;
        }
        body {
            font-family: 'pixellari', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #FEDAC1;
            box-shadow: 4px 4px 79.1px 23px #E2A475 inset; 
            border-radius: 8px;
            border: 7px solid #a35647;
            overflow: hidden;
        }
        .header {
            background-color: #FEDAC1;
            box-shadow: 4px 4px 79.1px 23px #E2A475 inset; 
            border: 7px solid #a35647;
            margin: 10px;
            padding: 20px;
            text-align: center;
            font-size: 24px;
        }
        .content {
            padding: 20px;
            font-size: 16px;
            line-height: 1.5;
        }
        .content p {
            margin: 0 0 20px;
        }
        .content a {
            display: inline-block;
            background-color: #A35647;
            color: #ffffff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 10px;
        }
        .content a:hover {
            background-color: #8b4b3e;
        }
        .footer {
            background-color: #A35647;
            color: #2d2c2c;
            padding: 10px;
            text-align: center;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            Réinitialisation de votre mot de passe
        </div>
        <div class='content'>
            <p>Bonjour,</p>
            <p>Cliquez sur le lien ci-dessous pour changer votre mot de passe :</p>
            <p><a href='http://ebisu.test/index.php?action=editPassword&token={{token}}'>Changer votre mot de passe</a></p>
            <p>Si vous n'avez pas demandé la réinitialisation de votre mot de passe, veuillez ignorer cet e-mail.</p>
            <p>Merci,</p>
            <p>L'équipe Ebisu</p>
        </div>
        <div class='footer'>
            &copy; 2024 Ebisu. Tous droits réservés.
        </div>
    </div>
</body>
</html>
