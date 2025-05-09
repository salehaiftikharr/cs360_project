<?php

session_start();

include_once("../iftisa_util.php");
include_once("../whitco_util.php"); 
include_once("../casibe_util.php"); 
include("./bootstrap.php");

if (!isset($_SESSION["valid"]) || $_SESSION["valid"] !== true) {
    	
    	header("Location: login.php");
    	exit;
}

$uid = $_SESSION['uid'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    	
    	$caption = $_POST["caption"];
	
	if (isset($_POST['isPrivate'])) {
    		$isPrivate = 1;
	} 
	else {
    		$isPrivate = 0;
	}

    	$postTime = date("Y-m-d H:i:s");
    	$date = date("Y-m-d");

    	// Insert Workout and Post using helpers
    	$wid = insertWorkout($db, $uid, $date);
    	insertPost($db, $uid, $wid, $caption, $postTime, $date, $isPrivate);

    	/// Insert RepSchemes
for ($i = 1; isset($_POST["exercise$i"]); $i++) {
    
    	$reps = $_POST["reps$i"];
    	$sets = $_POST["sets$i"];
    	$weights = $_POST["weights$i"];
    	$restTime = $_POST["restTime$i"];
    	$exerciseName = $_POST["exercise$i"];

    	if (
        	$reps === '' || $sets === '' || $weights === '' || $restTime === '' ||
        	!is_numeric($reps) || !is_numeric($sets) || !is_numeric($weights) || !is_numeric($restTime)
    	) {
        	continue;
    	}

    	// Step 1: Insert into RepScheme and get the new rid
    	$stmt = $db->prepare("INSERT INTO RepScheme (reps, sets, weights, restTime) VALUES (?, ?, ?, ?)");
    	$stmt->execute([$reps, $sets, $weights, $restTime]);
    	$rid = $db->lastInsertId();

    	// Step 2: Insert into Exercise using wid, name, and that rid
    	$stmt2 = $db->prepare("INSERT INTO Exercise (wid, name, rid) VALUES (?, ?, ?)");
    	$stmt2->execute([$wid, $exerciseName, $rid]);
}

    	header("Location: profile.php");
    	exit;
}
?>

<!DOCTYPE html>
<html>
<head>

	<title>New Post</title>
    
    	<script>
        	let numExercises = 0;
        	let eTypes = [<?php
            
            	$res = $db->query("SELECT name FROM ExerciseTypes");
            	$names = [];
            	
            	while($row = $res->fetch()) {
                	$names[] = '"' . $row['name'] . '"';
            	}
            	echo implode(", ", $names);
        	
        	?>];

	function addExerciseBox() {
		
            	numExercises++;
            	const container = document.getElementById('workout-section');
            	const div = document.createElement('div');
            	div.className = 'd-flex gap-2 mb-2';

            	div.innerHTML = `
                	
                	<input name="reps${numExercises}" placeholder="Reps" class="form-control" />
                	<input name="sets${numExercises}" placeholder="Sets" class="form-control" />
                	<input name="weights${numExercises}" placeholder="Weight" class="form-control" />
                	<input name="restTime${numExercises}" placeholder="Rest Time" class="form-control" />
                	
                	<select name="exercise${numExercises}" class="form-select">
                    		${eTypes.map(t => `<option value="${t.replace(/"/g, '')}">${t.replace(/"/g, '')}</option>`).join('')}
                	</select>
                
                	<button type="button" onclick="this.parentElement.remove()" class="btn btn-danger">-</button>
            `	;

            	container.appendChild(div);
        }

	window.onload = () => addExerciseBox();
    	</script>
    	
</head>
<body class="bg-dark text-light">

<?php 
	genHeader(); 
?>

<div class="main-content">

	<h2>New Post</h2>
	
    	<form method="POST">
    	
        	<div class="mb-3">
            		<label for="caption" class="form-label">Caption</label>
            		<textarea name="caption" class="form-control" placeholder="Write your caption here..."></textarea>
        	</div>
        	
        	<div class="form-check mb-3">
            		<input type="checkbox" name="isPrivate" class="form-check-input" id="isPrivate" />
            		<label for="isPrivate" class="form-check-label">Make this post private</label>
        	</div>
        	
        	<div id="workout-section">
        	</div>
        	
        	<button type="button" class="btn btn-secondary" onclick="addExerciseBox()">Add Another Exercise</button>
        	<br><br>
        	<button type="submit" class="btn btn-primary">Post</button>
    	</form>
</div>

<?php 
	genFooter();
?>

</body>
</html>
