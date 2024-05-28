<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="vue/styles.css"> <!-- Link to your CSS file -->
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <?php if (isset($error)) { ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php } ?>
        <form method="post" action="authenticate.php">
            <label>
                Email:
                <input type="email" name="email" required>
            </label>
            <br>
            <label>
                Password:
                <input type="password" name="password" required>
            </label>
            <br>
            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>
