<?php

session_start();

include_once("../db_connect.php");

//could do input validation if we want/have the time
$regexUsername = "";
$regexEmail = "";
$regexPassword = "";

function genLoginForm() {
    ?>
    <FORM name='fmLogin' method='POST' action='login.php?login'>
    <label for="loginUsername">Username:</label><br>
    <INPUT type='text' id="loginUsername" name='username' size='5' placeholder='Username' /><br>
    <label for="loginPassword">Password:</label><br>
    <INPUT type='text' id="loginPassword" name='password' size='5' placeholder='Password' /><br><br>
    <INPUT type='submit' value='Login' />
    </FORM>
    
    <?php
}

function genPosts($db){
    
    $uid = $_SESSION['uid'];
    
    $query = "SELECT caption
                FROM User u JOIN Follows f ON follower_uid = u.uid
                JOIN Post p ON p.uid = following_uid
                WHERE u.uid = $uid
                ORDER BY p.date";

    $res = $db->query($query);
    if($res->rowCount() > 0){
        while($row = $res->fetch()){
            $caption = $row['caption'];
            echo "<p>$caption</p>\n";
        }
    }
    else{
        echo "No Posts!";
    }
}

function genSignupForm() {
    ?>
    <FORM name='fmSignup' method='POST' action='signup.php?signup'>
    <label for="signupUsername">Username:</label><br>
    <INPUT type='text' id ='signupUsername' name='username' size='5' placeholder='Username' /><br>
    <label for="signupEmail">Email:</label><br>
    <INPUT type='text' id ='signupEmail' name='email' size='5' placeholder='Email' /><br>
    <label for="signupPassword">Password:</label><br>
    <INPUT type='text' id = 'signupPassword' name='password' size='5' placeholder='Password' /><br><br>
    <INPUT type='submit' value='Signup' />
    </FORM>
    
    <?php
}

function processSignup() {
    ?>
    <FORM name='fmSignup' method='POST' action='signup.php?signup'>
    <label for="signupUsername">Username:</label><br>
    <INPUT type='text' id = 'signupUsername' name='uid' size='5' placeholder='Username' /><br>
    <label for="signupPassword">Password:</label><br>
    <INPUT type='text' id = 'signupPassword' name='uid' size='5' placeholder='Password' /><br><br>
    <INPUT type='submit' value='Signup' />
    </FORM>
    
    <?php
}

function processLogin($db, $formData) {
    $username = $formData['username'];
    $password = $formData['password'];

    $query = "SELECT uid 
                FROM User   
                WHERE username='$username' AND password='$password'";
    
    $res = $db->query($query);

    if ($res == false || $res->rowCount() != 1) {
        header("refresh:2;url=login.php?login");
        print "<P>Login as $username failed</P>\n";    
    }
    else {
        header("refresh:2;url=login.php?login");
        $row = $res->fetch();
        $_SESSION['uid'] = $row['uid'];
        $_SESSION['uname'] = $username;
        $_SESSION["valid"] = true;
        ?>
        <p>Successfully logged in as <?php echo($username) ?> User with UID: <?php echo($row['uid']) ?> </p>
        <?php
        $homepage = 'homepage.php';
        header('Location: '. $homepage);
    }
}

function checkDuplicateUsername($db, $username):bool {
    $query = "SELECT *
                        FROM User
                        WHERE username = '$username'";

    $res = $db->query($query);
    
    if($res->rowCount() != 0){
        ?>
        <script>alert("You have entered a username that has already been taken. Please try a different username.");</script>
        <?php
        return false;
    }
    return true;
}

function checkDuplicateEmail($db, $email):bool {
    $query = "SELECT *
                        FROM User
                        WHERE email = '$email'";
    $res = $db->query($query);

    if($res->rowCount() != 0){
        ?>
        <script>alert("An account is already associated with that email. Please use a different one.");</script>
        <?php
        return false;
    }
    return true;
}

function redirectToHomePage(){}

?>
