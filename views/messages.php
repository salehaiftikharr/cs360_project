<?php

// Start session if not started already.
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

include_once("../iftisa_util.php");
include_once("../whitco_util.php");
include_once("../casibe_util.php");
include("./bootstrap.php");

// Redirect to login if not logged in already.
if (!isset($_SESSION["valid"]) || $_SESSION["valid"] !== true) {
	
    	header("Location: ../login.php");
   	exit;
}

// uid, uname from session
$uid = $_SESSION['uid'];
$uname = $_SESSION['uname'];

// MESSAGE HANDLER SECTION

if (isset($_POST['send']) && isset($_GET['chat'])) {
    
    	$to_uid = $_GET['chat'];
    	$contents = trim($_POST['contents']);

	// if message not empty insert in the db.
    	if (!empty($contents)) {
        	insertMessage($db, $uid, $to_uid, $contents);
    	}
    
    	header("Location: messages.php?chat=$to_uid");
    	exit;
}

// Function to show chat w a user.
function showChat($db, $uid, $chatUid) {

    	$chatName = getUserName($db, $chatUid);
    	echo "<h4 class='text-info mb-3'>Chat with $chatName</h4>";

    	echo "<div class='chat-wrapper'>";
    	echo "<div class='chat-container' id='chatBox'>";

    	$messages = getMessagesBetweenUsers($db, $uid, $chatUid);
    	foreach ($messages as $row) {
   
    		if ($row['from_uid'] == $uid) {
        		$bubbleClass = 'from-me';
    		} 
    		else {
        		$bubbleClass = 'from-them';
    		}

    	echo "<div class='message-bubble $bubbleClass'>
            	{$row['contents']}<br>
            	<small>{$row['postTime']}</small>
              </div>";
	}


    	echo "</div>"; // close chat-container

    	echo "<form method='POST' class='message-form mt-3'>
			
			<div class='mb-3'>
                		<label for='contents' class='form-label'>Message:</label>
                		<textarea name='contents' rows='2' class='form-control' placeholder='Type your reply...'></textarea>
            		</div>
            	
            		<input type='submit' name='send' class='btn btn-primary' value='Send'>
              </form>";

    	echo "</div>"; // close chat-wrapper
}

// Function to show the nbox and search for users to chat with.
function showInboxList($db, $uid) {
    	
    	// Display "Start new convo section"
    	echo "<div class='card bg-secondary p-3 mb-4'>
			<h5 class='text-light'>Start a New Conversation</h5>
            		
            		<form method='GET' action='messages.php' class='d-flex gap-2'>
                		<input type='text' name='searchName' class='form-control' placeholder='Search by name...' required>
                		<button type='submit' class='btn btn-light'>Search</button>
            		</form>
               </div>";

	// If there is a search term, show the results.
    	if (isset($_GET['searchName'])) {
        	
        	$term = trim($_GET['searchName']);
        	$results = getSearchResults($db, $term, $uid);

        	if ($results) {
            		echo "<div class='bg-dark p-3 rounded'><h6 class='text-info'>Search Results:</h6><ul class='list-group'>";
            			
            			foreach ($results as $row) {
                			$foundUid = $row['uid'];
                			$name = $row['name'];
                			echo "<li class='list-group-item bg-dark border-secondary'>
                        		<a href='messages.php?chat=$foundUid' class='text-light'>$name</a>
                      			</li>";
            			}
            		echo "</ul></div>";
        	} 
        	else {
            		echo "<p class='text-warning mt-2'>No users found with that name.</p>";
        	}
    	}

	// Display Existing Convos
    	echo "<h5 class='text-info mt-4'>Messages</h5>";
    	
    	$existingConvos = getUserConversations($db, $uid);
    	
    	echo "<ul class='list-group'>";
    		
    		foreach ($existingConvos as $row) {
        		$name = $row['name'];
        		$chatId = $row['uid'];
        		echo "<li class='list-group-item bg-dark border-secondary'>
                	<a href='messages.php?chat=$chatId' class='text-light'>$name</a>
              		</li>";
    		}
    	echo "</ul>";
}
?>

<!DOCTYPE html>
<html>
<head>
    
    <title>Fuerza Messages</title>
    <link rel="stylesheet" href="./myStyle.css" />
    
</head>

<body class="bg-dark text-light">

<?php
	genHeader();	
?>

<div class="container mt-4">
    <div class="row">
        
        <!--Inbox Sidebar -->
        <div class="col-md-4">
        
		<h3 class="text-light mb-3">
    		Welcome, <?php echo $uname; ?>!
		</h3>

            	<?php showInboxList($db, $uid); ?>
        </div>

        <!-- Chat Section -->
        <div class="col-md-8">
            
            <?php
                if (isset($_GET['chat'])) {
                	showChat($db, $uid, $_GET['chat']);
                } 
                else {
                    	echo "<p class='text-muted'>Select a conversation to start chatting.</p>";
                }
            ?>
        </div>
    </div>
</div>

<?php
	genFooter();	
?>


<script>
    	const chatBox = document.getElementById("chatBox");
    	if (chatBox) {
        	chatBox.scrollTop = chatBox.scrollHeight;
    	}
</script>

</body>
</html>
