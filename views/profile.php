<?php 
session_start();
include("../casibe_util.php");
include("../whitco_util.php");
?>

<!DOCTYPE html>
<HTML>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<title> Profile Page </title>
<?php 	include("./bootstrap.php");

	$uid = 1; //EVENTUALLY USE SESSION ONCE LOGIN IS SET UP!!!!!!!!!!!!
	$pid = 1; //PROFILE ID!!! Have link specific what user the profile is on!!!!

?>
    
</head>
<body>
	<nav class="navbar navbar-expand-md">
		<div class="container">
		<a class="navbar-brand" href="#">Fuerza</a>
			<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav ml-auto">
					<li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
					<li class="nav-item"><a class="nav-link" href="profile.php">My Profile</a></li> <!-- eventually set up for the link to contain the username of the profile data to use -->
					<li class="nav-item"><a class="nav-link" href="messages.php">Messages</a></li>
					<li class="nav-item"><a class="nav-link" href="newpost.php">New Post</a></li>
				</ul>
			</div>
		</div>
	</nav>

	<div class="main-content">

		<?php
			$str = "SELECT * FROM User WHERE uid=$pid";
			$profileData = $db->query($str)->fetch();
			$username = $profileData['name'];
			$image = $profileData['profile_picture'];
			
			print"<div>"; //make user and pfp on same line idk fix it
			print'<IMG src="'.$image.'" class="pfp-image">';
			print"<H5>$username</H5>";
			print"</div>";
		?>

	</div>

	<footer>
		<p>© 2025 Fuerza Inc.</p>
	</footer>

</body>

</HTML>
