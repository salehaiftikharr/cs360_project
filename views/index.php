<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include("./bootstrap.php"); ?>
    <title>Fuerza</title>
    <link rel="stylesheet" href="public-style.css">
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <a class="navbar-brand" href="index.php">Fuerza</a>
            <div class="navbar-links">
                <a href="home.html">Home</a>
                <a href="product.html">Product</a>
                <a href="mission.html">Mission</a>
                <a href="whatsnew.html">What's New</a>
                <a href="login.php">Login</a>
            </div>
        </div>
    </nav>

    <main class="welcome-page">
        <div class="welcome-container">
            <h1>WELCOME TO FUERZA!</h1>
            <p>
                Discover how Fuerza empowers you to push beyond limits.<br>
                Dive into our <strong>Product</strong> lineup for smart training tools,<br>
                explore our <strong>Mission</strong> to see what drives us,<br>
                and check out <strong>What's New</strong> to stay in the loop.<br>
                Already a member? <strong>Login</strong> to get started!
            </p>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 Fuerza Inc.</p>
    </footer>
</body>
</html>
