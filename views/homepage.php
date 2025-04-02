<?php
session_start();

var_dump($_SESSION);

if(!$_SESSION["valid"]){
    header("Location:" . "login.php");
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Fuerza Home</title>
        <h1>Welcome Connor</h1>
    </head>
    
    <body>
    </body>
</html>