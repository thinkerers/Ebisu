<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php'); // Redirect to login if not authenticated
    exit;
}

include_once './vue/head.php';
?>

<header>
    <nav>
       <a href="profil.php"><?= htmlspecialchars($_SESSION['user']); ?></span><a href="logout.php">Déconnection</a>
    </nav>
</header>
<main>
    <h1>Bienvenue sur Ebisu 🐠</h1>
</main>

<?php include_once './vue/footer.php'; ?>