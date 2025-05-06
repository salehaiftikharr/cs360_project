<?php
session_start();
include_once("db_connect.php"); //global variable $db created

function genHeader(){

?>	
<nav class="navbar navbar-expand-md">
		<div class="container">
		<a class="navbar-brand" href="homepage.php">Fuerza</a>
			<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav">
					<li class="nav-item"><a class="nav-link" href="homepage.php">Home</a></li>
					<li class="nav-item"><a class="nav-link" href="profile.php">My Profile</a></li>
					<li class="nav-item"><a class="nav-link" href="messages.php">Messages</a></li>
					<li class="nav-item"><a class="nav-link" href="newpost.php">New Post</a></li>
				</ul>
			</div>
		</div>
	</nav>
<?php
}

function genFooter(){
?> <footer>
		<p>© 2025 Fuerza Inc.</p>
	</footer>
<?php
}



function genPost($db, $pid){

	$str = "SELECT * FROM Post NATURAL JOIN User WHERE pid = $pid";
	$row = $db->query($str)->fetch();
	$caption = $row['caption'];
	$date = $row['date'];
	$name = $row['name'];
	$user = $row['username'];
	
	print"<p>$date <a href='profile.php?account=$user'> $name <a> </p>";
	print"<h6>$caption</h6>";
	
	
	print"<div class='horizontalscroll'>";
	print"<table>";

	$str = "SELECT reps, sets, weights, restTime, Exercise.name FROM RepScheme JOIN Exercise ON RepScheme.rid = Exercise.rid WHERE Exercise.wid = (SELECT Workout.wid FROM Workout JOIN Post ON Workout.wid = Post.wid WHERE pid=$pid)";
	$res = $db->query($str);

	while($row = $res->fetch()){
		print"<td class='posttable'>";
		print"<table>";
		$name = $row['name'];
		$reps = $row['reps'];
		$sets = $row['sets'];
		$weights = $row['weights'];
		$restTime = $row['restTime'];
		print"<TH>$name</TH>";
		print"<TR><TD>reps: $reps</TD></TR><TR><TD>sets: $sets</TD></TR><TR><TD>weights: $weights</TD></TR><TR><TD>restTime: $restTime</TD></TR>";
		print"</table>";
		print"</td>";
	}
	
	print"</table>";
	
	print"</div>";

}

function genPosts($db, $uid){

		?><div class='scrolltainer'>
		<?php
		$str = "SELECT pid FROM Post WHERE uid = $uid";
		$res = $db->query($str);
		
		while($row = $res->fetch()){
			genPost($db, $row['pid']);
		}
		?>
		</div>
<?php
}
?>
