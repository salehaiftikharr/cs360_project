<?php 
session_start();
include_once("../whitco_util.php"); 
include_once("../casibe_util.php"); 

//ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);


if(!$_SESSION["valid"]){
    header("Location:" . "login.php");
}

?>

<!DOCTYPE html>
<HTML>
<head>

<title> Edit Profile </title>
<?php
	include("./bootstrap.php");
	$uid = $_SESSION['uid'];
	$uname = $_SESSION['uname'];
?>
    
</head>
<body>
	<?php genHeader(); ?>

	<div class="main-content">
		<?php
		if($_GET['menu'] == "upload"){

			$file = $_FILES["fileToUpload"]["name"];
			if(isset($_POST["submit"])){
				//change pfp
				if($file !== ""){
				
					$dir = "../img/";
					$tmp = $_FILES["fileToUpload"]["tmp_name"];
					$fileType = strtolower(pathinfo($file,PATHINFO_EXTENSION));
					$file_target = $dir.$uname.'.'.$fileType;
					$isPicture = 1;
				
					$check = getimagesize($tmp);
					//var_dump($check);
					
					if($check == false){
						print"<p>ERROR: Uploaded file is not an image, or may be too large! Max file size is 2MB.</p>";
						$isPicture = 0;
					}
				
			
					if($isPicture == 1){
						//check if there's already a pfp for this user
						
						$dir = scandir("../img");
						foreach($dir as $f){
							$str = pathinfo($f, PATHINFO_FILENAME);
							if($str == $uname){
								unlink("../img/".$f);
							}
						}
						//print"<p>$tmp</p>";
						//print"<p>$file_target</p>";
						
						
						fopen($file_target, "w"); 
						
						$res = copy($tmp, $file_target);
						
						if(!$res){
							print"There was an issue uploading your file.";
						}
						
						$str = "UPDATE User SET profile_picture = '$file_target' WHERE uid = $uid";
						$res = $db->query($str);
									
					}
				}
			}
			//change Name
			$newName = $_POST['name'];
			$str = "UPDATE User SET name = '$newName' WHERE uid = $uid";
			$res = $db->query($str);
					
			//change Bio
			$newBio = $_POST['bio'];
			$str = "UPDATE User SET profile_bio = '$newBio' WHERE uid = $uid";
			$res = $db->query($str);
			
			header("refresh:0.5;url=profile.php");
			
		}
		
		$str = "SELECT name, profile_bio FROM User WHERE uid = $uid";
		$row = $db->query($str)->fetch();
		$bio = $row['profile_bio'];
		$name = $row['name'];
		
		?>
		<h1>Edit Profile</h1>
	
		<form action="editprofile.php?menu=upload" method="post" enctype="multipart/form-data">
		  <p>Select image to upload (Max 2MB):</p>
		  <p><input type="file" name="fileToUpload" id="fileToUpload"></p>
		  <p>Name:</p>
		  <textarea id="name" name="name" rows="1" cols="20"><?php print"$name"; ?></textarea>
		  <p>Bio:</p>
		  <textarea id="bio" name="bio" rows="4" cols="50"><?php print"$bio"; ?></textarea>
		  </br></br>
		  <input type="submit" value="Update" name="submit">
		</form>

	</div>

	<?php genFooter(); ?>
	

</body>

</HTML>
