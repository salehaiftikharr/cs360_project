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
<title> New Post </title>
<?php
	include("./bootstrap.php");
	$uid = $_SESSION['uid'];
?>
<script>
let numExercises = 0;

let eTypes = [
<?php
		$str = "SELECT name FROM ExerciseTypes";
		$res = $db->query($str);
		$curr = 0;
		while($row = $res->fetch()){
			$curr = $curr + 1;
			$et = $row['name'];
			echo'"'."$et".'"';
			$rc = $res->rowCount();
			if($curr != $rc){
			echo", ";	
			}
		}
?>
];


function addExerciseBox(){
	numExercises = numExercises + 1;
	const div = document.createElement('div');
	let divID = "exercises" + numExercises;
	div.id = divID;
	document.getElementById('exerForm').appendChild(div);
	
	const button = document.createElement('button');
	button.textContent = 'Add Another Exercise';
	button.addEventListener('click', function() {
		this.remove();
		addExerciseBox();
	});

	const repsInput = document.createElement('input');
	repsInput.setAttribute('placeholder', 'numReps');
	repsInput.setAttribute('name', 'reps' + numExercises);

	const setsInput = document.createElement('input');
	setsInput.setAttribute('placeholder', 'numSets');
	setsInput.setAttribute('name', 'sets' + numExercises);

	const weightsInput = document.createElement('input');
	weightsInput.setAttribute('placeholder', 'weights');
	weightsInput.setAttribute('name', 'weights' + numExercises);

	const restInput = document.createElement('input');
	restInput.setAttribute('placeholder', 'restTime');
	restInput.setAttribute('name', 'weights' + numExercises);

	const exerciseDropdown = document.createElement('select');
	exerciseDropdown.setAttribute('name', 'exercise' + numExercises);

	eTypes.forEach(function(element) {
  		const exerciseOpt = document.createElement('option');
  		exerciseOpt.setAttribute('label', element);
  		exerciseOpt.setAttribute('value', element);
		exerciseDropdown.appendChild(exerciseOpt);
	});

	div.appendChild(repsInput);
	div.appendChild(setsInput);
	div.appendChild(weightsInput);
	div.appendChild(restInput);
	div.appendChild(exerciseDropdown);

	if(numExercises > 1){
		const remove = document.createElement('button');
		remove.textContent = '-';
		remove.addEventListener('click', function() {
			div.remove();
		});
		
		div.appendChild(remove);	
	}
	
	document.getElementById('exerForm').appendChild(button);
	
	
}
</script>
    
</head>
<body>
	<?php genHeader(); ?>
	<div class="main-content" id="main">
	
		<h2 style='text-align:center;'>New Post</h2>
	
	
	<?php
	if($_GET['menu'] == 'submit'){
	
		var_dump($_POST);
	
	}else{ 		
	?>
		<h4>Caption</h4>
		<FORM id='exerForm' method="POST" action="newpost.php?menu=submit">
		<div>
		<TEXTAREA name="caption" placeholder="caption" class="captionInput"></TEXTAREA>
		<INPUT type="submit" value="Submit Post"/>
		</div>
		<script>addExerciseBox();</script>
		
		</FORM>
	<?php } ?>	
	</div>

	<?php genFooter(); ?>
	

</body>

</HTML>
