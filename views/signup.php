<?php 

session_start();

include("../whitco_util.php"); 
$menu = "signUp";
?>

<!DOCTYPE html>
<head>
    <title>Signup</title>
</head>
<body>
    <h2>Welcome to Fuerza! Sign up Here:</h2>
    <class = 'col-4'>
    <?php
        genSignupForm();
    ?><br>
    <a href="login.php">Click here to return to login page.</a>
    <?php
    include_once("../dbproj_connect.php");
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!empty($_POST["username"]) && !empty($_POST["password"]) && !empty($_POST["email"])) {
            $username = htmlspecialchars($_POST["username"]);
            $email = htmlspecialchars($_POST["email"]);
            $password = htmlspecialchars($_POST["password"]);

            if(checkDuplicateUsername($db, $username) && checkDuplicateEmail($db, $email)){
                try {
                    $db->beginTransaction();
                    $stmt1 = $db->prepare("INSERT INTO User (uid, username, email, password) VALUES (0, ?, ?, ?)");
                    
                    $stmt1->execute([$username, $email, $password]);

                    $db->commit();

                    echo "<p>New User added successfully!</p>\n";
                } catch (Exception $e) {
                    $db->rollBack();
                    ?>
                    <script>alert("Database Error!");</script>
                    <?php
                }
        }
        }
        else{
            ?>
            <script>alert("Either username or password were left blank! Please make sure to fill both out, and try again!");</script>
            <?php
        }
    }
    ?>
</body>
</html>