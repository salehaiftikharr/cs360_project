<?php 
session_start();
//var_dump($_SESSION);
include_once("../whitco_util.php"); 
include_once("../casibe_util.php"); 


if(!$_SESSION["valid"]){
    header("Location:" . "login.php");
}

?>

<!DOCTYPE html>
<HTML>
<head>
<title> Profile Page </title>
<?php
	include("./bootstrap.php");
	$uid = $_SESSION['uid'];
?>
    
</head>
<body>
	<?php genHeader(); ?>

	<div class="main-content">
	
		<h2 style='text-align:center;'>New Post</h2>
	
	
		<h4>Caption</h4>
		<TEXTAREA name="caption" placeholder="caption" class="captionInput"></TEXTAREA>
	
	</div>

	<?php genFooter(); ?>
	

</body>

</HTML>
