<?php 

session_start();

include_once("../whitco_util.php"); 
include_once("../dbproj_connect.php"); 
$menu = "notLoggedIn";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h2>Login Here:</h2>
    <?php
    switch($menu){
        case 'notLoggedIn':
            genLoginForm();
            break;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        processLogin($db, $_POST);
    }
    ?>
    <p>Not registered? <a href="signup.php">Click here to sign up!</a></p>
</body>
</html>