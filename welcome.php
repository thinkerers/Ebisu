<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php'); // Redirect to login if not authenticated
    exit;
}

include_once './vue/head.php';
?>


<h1>Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?>!</h1>
<p>You have successfully logged in.</p>
<a href="logout.php">Logout</a>

<?php include_once './vue/footer.php'; ?>