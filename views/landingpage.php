<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
   	<?php include("./bootstrap.php"); ?>
	<title>Fuerza</title>

</head>
<body>
    <nav class="navbar navbar-expand-md">
        <div class="container">
            <a class="navbar-brand" href="#">Fuerza</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link" href="index.html">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="product.html">Product</a></li>
                    <li class="nav-item"><a class="nav-link" href="mission.html">Mission</a></li>
                    <li class="nav-item"><a class="nav-link" href="new.html">What's New</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.html">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="main-content">
        <h2>Connect and Grow</h2>
        <br>
        <p>Join your friends and build a community centered around health, fitness and self-improvement.</p>
        <br>
        <img class ="main-pic" src="../img/lifting_class.png">
    </div>


    <h5>Features</h5>

        <div class="features">
            <div class="fcontainer">
                <div class="gallery">
                    <img src="../img/spotting.png">                 
                </div>
                <div class="gallery">
                    <img src="../img/measure.png">    
                </div>
                <div class="gallery">
                    <img src="../img/coach.png">
                </div>
                <div>
                    <h6>Connect With Friends</h6>
                    <p>Fuerza allows you to share and track workouts with your favorite people, as well as find others on a similar journey.</p>
                </div>
                <div>
                    <h6>Track Your Progress</h6>
                    <p>See how you've improved using our analysis features.</p>
                </div>
                <div>
                    <h6>Learn From Others</h6>
                    <p>Gain unique perspectives on fitness through collaboration and community.</p>
                </div>
            </div>
        </div>

    <footer>
        <p>© 2025 Fuerza Inc.</p>
    </footer>
</body>
</html>
