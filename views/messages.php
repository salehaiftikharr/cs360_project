<?php
// Shows errors during development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include_once("../db_connect.php");
include_once("../whitco_util.php");
include_once("../casibe_util.php");
include("bootstrap.php");

if (!isset($_SESSION["valid"]) || $_SESSION["valid"] !== true) {
    header("Location: login.php");
    exit;
}

$uid = $_SESSION['uid'];
$uname = $_SESSION['uname'];

// === SEND MESSAGE HANDLER ===
if (isset($_POST['send'])) {
    $to_uid = $_POST['to_uid'];
    $contents = addslashes($_POST['contents']);
    $now = date('Y-m-d H:i:s');

    $query = "INSERT INTO Message (from_uid, to_uid, postTime, contents)
              VALUES ($uid, $to_uid, '$now', '$contents')";
    $res = $db->query($query);

    if ($res) {
        echo "<div class='alert alert-success'> Message sent!</div>";
    } else {
        echo "<div class='alert alert-danger'> Failed to send message.</div>";
    }
}

// === INBOX ===
function showInbox($db, $uid) {
    echo "<h3>Inbox</h3>";
    $query = "SELECT M.contents, M.postTime, U.name AS sender
              FROM Message M
              JOIN User U ON M.from_uid = U.uid
              WHERE M.to_uid = $uid
              ORDER BY M.postTime DESC";
    $res = $db->query($query);

    echo "<table class='table table-bordered table-striped'>
            <thead><tr><th>Time</th><th>From</th><th>Message</th></tr></thead><tbody>";
    while ($row = $res->fetch()) {
        echo "<tr>
                <td>{$row['postTime']}</td>
                <td>{$row['sender']}</td>
                <td>{$row['contents']}</td>
              </tr>";
    }
    echo "</tbody></table><br>";
}

// === COMPOSE FORM ===
function composeForm($db, $uid) {
    $res = $db->query("SELECT uid, name FROM User WHERE uid != $uid");

    echo "<h3>Send a Message</h3>
          <form method='POST' class='mb-4'>
            <div class='mb-3'>
                <label for='to_uid' class='form-label'>To:</label>
                <select name='to_uid' class='form-select'>";
    while ($row = $res->fetch()) {
        echo "<option value='{$row['uid']}'>{$row['name']}</option>";
    }
    echo "</select>
            </div>
            <div class='mb-3'>
                <label for='contents' class='form-label'>Message:</label>
                <textarea name='contents' rows='4' class='form-control' placeholder='Type your message here'></textarea>
            </div>
            <input type='submit' name='send' class='btn btn-primary' value='Send Message'>
          </form>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Fuerza Messages</title>
</head>
<body>
<?php genHeader(); ?>

<div class="container mt-4">
    <h2>Welcome to your Messages, <?php echo $uname; ?>!</h2>
    <?php
        showInbox($db, $uid);
        composeForm($db, $uid);
    ?>
</div>

<?php genFooter(); ?>
</body>
</html>

