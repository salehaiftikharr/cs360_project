<?php 
session_start();
//var_dump($_SESSION);
include_once("../whitco_util.php"); 
include_once("../casibe_util.php"); 


if(!$_SESSION["valid"]){
    header("Location:" . "login.php");
}

if(isset($_GET['account'])){
	$account = $_GET['account'];
}
else{
    header("Location:" . "profile.php?account=" . $_SESSION['uname']);
}

?>

<!DOCTYPE html>
<HTML>
<head>

<title> Profile Page </title>
<?php
	include("./bootstrap.php");
	$uid = $_SESSION['uid'];
	$str = "SELECT uid FROM User WHERE username = '$account'";
	$pid = $db->query($str)->fetch()['uid']; // PROFILE ID!!!

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
		<?php genPosts($db, $pid);
		?>
	</div>

	<?php genFooter(); ?>
	

</body>

</HTML>
