<?php
session_start();

var_dump($_SESSION);

if(!$_SESSION["valid"]){
    header("Location:" . "login.php");
}

include_once("../whitco_util.php"); 
include_once("../casibe_util.php"); 
include_once("../dbproj_connect.php"); 
include_once("../bootstrap.php"); 

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Fuerza Home</title>
        <h1>
            <style>h1 {text-align: center;}</style>
            Welcome <?php echo $_SESSION["uname"]; ?>
        </h1>
    </head>
    
    <body>
        <?php
            //genHeader();
            genSearchBar();
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                //var_dump($_SERVER);
                if($_SERVER["QUERY_STRING"] == 'search'){
                    //var_dump($_POST);
                    processSearch($db, $_POST);
                }
            } 
            genHomepagePosts($db);
            //genFooter();
        ?>
    </body>
</html>
