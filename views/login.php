<?php 

session_start();
include_once("../whitco_util.php"); 
include_once("../casibe_util.php"); 
include_once("../db_connect.php");
include("../bootstrap.php");
$menu = "notLoggedIn";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
 <div class="main-content">
    <h2>Login Here:</h2>
    <?php
    
    //$unam = $_SESSION['uname'];
    //print"<p>$unam</p>";
    
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
    </div>
    
    
    <?php genFooter(); ?>
</body>
</html>
