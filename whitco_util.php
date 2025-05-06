<script src="toggleSearchView.js"></script>

<?php

session_start();

include_once("casibe_util.php");

//could do input validation if we want/have the time
$regexUsername = "";
$regexEmail = "";
$regexPassword = "";
$nores = "No results found.";

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

function genHomepagePosts($db){
    
    $uid = $_SESSION['uid'];
    
    $query = "SELECT pid
                FROM User u JOIN Follows f ON follower_uid = u.uid
                JOIN Post p ON p.uid = following_uid
                WHERE u.uid = $uid
                ORDER BY p.date";

    $res = $db->query($query);
    if($res->rowCount() > 0){
        while($row = $res->fetch()){
            // $caption = $row['caption'];
            // echo "<p><style>p {text-align:center}</style>$caption</p>\n";
            genPost($db, $row['pid']);
        }
    }
    else{
        echo "<p><style>p {color:white} </style>No Posts!</p>";
    }
}

function genExplorePage($db){
    
    $uid = $_SESSION['uid'];
    
    $query = "SELECT pid
                FROM Post
                ORDER BY date";

    $res = $db->query($query);
    if($res->rowCount() > 0){
        while($row = $res->fetch()){
            genPost($db, $row['pid']);
        }
    }
    else{
        echo "<p><style>p {color:white} </style>No Posts!</p>";
    }
}

function genSearchBar(){
    ?>
    <div class='searchBox' id='searchBox'>
    <span id="close" class = "closeX" onclick="toggleSearchView('searchBox'); return false;">x</span>
    </div>
    <FORM name='userSearch' method='POST' action = 'homepage.php'>
    <label for="searchBy">Search By:</label>
        <select name="searchBy" id="searchOptions">
        <option value="Username">Username</option>
        <option value="Name">Name</option>
        <option value="Gym">Gym</option>
        </select>
        <INPUT type='text' name='searchTerm' id='searchTerm' size = 5 />
        <?php print "<input type='submit' value='search' onclick='toggleSearchView(\"searchBox\"); return false;'/>"; 
        ?>
    </FORM>
    <?php
}

function processSearch($db, $formData){
    $category = $formData['searchBy'];
    switch ($category) {
        case 'Username':
            searchOnUsername($db, $formData);
            break;
        case 'Gym':
            searchOnGym($db, $formData);
            break;
        case 'Name':
            searchOnLegalName($db, $formData);
            break;
    }
}

function searchOnUsername($db, $formData){
    $username = $formData['searchTerm'] . '%';
        $query = "SELECT u.username AS username, u.name AS name, gym_name AS gym
                    FROM User AS u
                        LEFT JOIN MemberOf AS m ON m.uid=u.uid
                        WHERE u.username LIKE :username";
    
        $stmt = $db->prepare($query);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        
        if($stmt->rowCount() < 1){
            print "<p> No results found </p><br>";
        }
        else{
            while($row = $stmt->fetch()){
                $index = 0;
                $username = $row['username'];
                ?>
                <script>
                alert("hi");
                </script>
                <?php
                // echo "name" . $index;
                // //setCookie("name" . $index, $username);
                // 
                // <script> 
                // var cookies = document.cookie.split(';');
                // alert(cookies);
                // var splitUsername = cookies[0].split('=');
                // var username = splitUsername[1];
                // document.getElementById('searchBox').innerHTML += username + "<br>";
                // </script> 
                // <?php
                // $index += 1;
                // //setCookie("name", $username, time());
                // //print "<p>$username</p> <br>";
            }
        }
}

function searchOnGym($db, $formData){
    $gymName = $formData['searchTerm'];
        $query = "SELECT username FROM User u 
                    JOIN MemberOf m ON u.uid = m.uid 
                        WHERE gym_name = :gymname";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':gymname', $gymName, PDO::PARAM_STR);
        $stmt->execute();
        ?>
            <div id="searchBox" class="searchBox">
            <span id="close" class = "closeX" onclick="toggleSearchView('searchBox'); return false;">x</span>
        <?php
        if($stmt->rowCount() < 1){
            print "<p>No results found</p>";
        }
        else{
            while($row = $stmt->fetch()){
                $username = $row['username'];
                print "<p>$username</p><br>";
            }
            ?>
            </div>
            <?php
        }
}

function searchOnLegalName($db, $formData){
    $name = $formData['searchTerm'] . '%';
        $query = "SELECT u.username, u.name, gym_name
                    FROM User AS u
                        JOIN MemberOf AS mo ON u.uid = mo.uid
                            WHERE u.name LIKE :name";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->execute();
        if($stmt->rowCount() < 1){
            echo "No results found";
        }
        else{
            while($row = $stmt->fetch()){
                $username = $row['username'];
                echo "<p>$username</p><br>";
            }
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
   
    $query = 'SELECT uid FROM User WHERE username = :username AND password = :password';
    

    $stmt = $db->prepare($query);

    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);

    $stmt->execute();

    

    if ($stmt->rowCount() != 1) {
        //header("refresh:2;url=login.php?login");
        print "<P>Login as $username failed</P>\n";    
    }
    else {
        header("refresh:2;url=login.php?login");
        $row = $stmt->fetch();
        $_SESSION['uid'] = $row['uid'];
        $_SESSION['uname'] = $username;
        $_SESSION["valid"] = true;
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

function genExploreToggle() {
    ?>
    <button id="exploreBtn" onclick="toggleExplore()">
        Explore
    </button>
    <?php 
}

function genHomeToggle() {
    ?>
    <button id="homeBtn" onclick="toggleHome()">
        Home
    </button>
    <?php 
}


function redirectToHomePage(){}

?>
