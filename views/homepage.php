<?php
session_start();

if(!$_SESSION["valid"]){
    header("Location:" . "login.php");
}

include_once("../whitco_util.php"); 
include_once("../casibe_util.php"); 
include_once("../db_connect.php"); 
include_once("../bootstrap.php"); 
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Fuerza Home</title>
        <h1>
            <style>h1 {text-align: center;}</style>
            Welcome, <?php echo $_SESSION["uname"]; ?>!
        </h1>
    </head>
    
    <body>
        <?php
            genHeader();
            genSearchBar(); 
            if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['explore'])) {
                    genHomeToggle();
                    genExplorePage($db);
            }   
            else{
                genExploreToggle();
                genHomepagePosts($db); 
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    //if($_SERVER["QUERY_STRING"] == 'search'){
                        processSearch($db, $_POST);
                    //}
                }    
            }    
            genFooter();
        ?>
    </body>
</html>
