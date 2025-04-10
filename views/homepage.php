<?php
session_start();
include_once("../whitco_util.php"); 
include_once("../casibe_util.php"); 
include("./bootstrap.php");

var_dump($_SESSION);

if(!$_SESSION["valid"]){
    header("Location:" . "login.php");
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Fuerza Home</title>
        <h1>Welcome <?php echo $_SESSION["uname"]; ?></h1>
    </head>
    
    <body>
    <?php genHeader(); 
    genPosts();
    ?>
    
    </body>
</html>
