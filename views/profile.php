<?php 
session_start();
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
	
	if($_GET['menu'] == "follow"){
		$str = "INSERT INTO Follows (follower_uid, following_uid) VALUES ($uid, $pid)";
		$db->query($str);
		header("refresh:0.2;url=profile.php?account=$account");
	}
	
	
	if($_GET['menu'] == "unfollow"){
		$str = "DELETE FROM Follows WHERE follower_uid = $uid AND following_uid = $pid";
		$db->query($str);
		header("refresh:0.2;url=profile.php?account=$account");	
	}


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
		
		
		if($uid !== $pid){
			
			$str = "SELECT * FROM Follows WHERE follower_uid = $uid AND following_uid = $pid";
			$res = $db->query($str)->fetch();
			
			if(!empty($res)){
				?>
				<DIV class="col-4 menuItem"><A href="?account=<?php print"$account";?>&menu=unfollow">Unfollow</A></DIV>
				</div>
				<?php
			}
			else{
				?>
				<DIV class="col-4 menuItem"><A href="?account=<?php print"$account";?>&menu=follow">Follow</A></DIV>
				</div>
				<?php
			}
		}
		else{
		?>
		<p><a href="editprofile.php">Edit Profile</a></p>
		<?php
		}
		
		genPosts($db, $pid);
		?>
	</div>

	<?php genFooter(); ?>
	

</body>

</HTML>
