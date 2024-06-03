<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./templates/style.css">
    <link rel="shortcut icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='22pt' height='22pt'%3E%3Ctext x='50%25' y='50%25' font-size='22' dominant-baseline='central' text-anchor='middle'%3E🐠%3C/text%3E%3C/svg%3E">
    <title><?= $title ?? 'Ebisu' ?></title>
</head>

<body>
    <?php if (isset($_SESSION['user'])) { ?>
        <header>
            <nav>
                <a href="profil.php"><?= htmlspecialchars($_SESSION['user']); ?></span><a href="logout.php" class="material-symbols-outlined" title="Déconnexion">logout</a>
            </nav>
        </header>
    <?php } ?>
    <?= $content ?>
    <footer class="material-symbols-outlined">
        <a title="Accueil" href="/">home</a>
        <a title="Ouvrir adminer" href="http://ebisu.test/model/adminer/sqlite.php?sqlite=&username=&db=..%2Febisu.sqlite&select=users">admin_panel_settings</a>
        <a title="github" href="https://github.com/thinkerers/Ebisu">folder_data</a>
        <a title="wiki" href="https://github.com/thinkerers/Ebisu/wiki">unknown_document</a>
        <p>🐠 2024 - Thinkerers</p>
    </footer>
</body>

</html>