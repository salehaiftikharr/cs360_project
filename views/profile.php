<?php 
session_start();
include_once("../whitco_util.php"); 
include_once("../casibe_util.php"); 
var_dump($_SESSION);
?>

<!DOCTYPE html>
<HTML>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<title> Profile Page </title>
<?php
	$uid = $_SESSION['uid'];
	$pid = $uid; //PROFILE ID!!! Have link specific what user the profile is on!!!!
	include("./bootstrap.php");
?>
    
</head>
<body>
	<?php genHeader(); ?>

	<div class="main-content">

		<?php
			$str = "SELECT * FROM User WHERE uid=$pid";
			$profileData = $db->query($str)->fetch();
			$name = $profileData['name'];
			$username = $profileData['username'];
			$image = $profileData['profile_picture'];
			$bio = $profileData['profile_bio'];
			
			print"<div>";
			print'<IMG src="'.$image.'" class="pfp-image">';
			print"<div class='profileDiv'><H4>$name</H4>";
			print"<H6>$username</H6>$bio</div>";
		?>
		<DIV class="col-4 menuItem"><A href="?menu=follow">Follow</A></DIV>
		</div>
		<?php genPosts($db, $uid);
		?>
	</div>

	<?php genFooter(); ?>
	

</body>

</HTML>
