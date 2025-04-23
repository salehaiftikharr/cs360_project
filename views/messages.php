<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

@include_once("../whitco_util.php");
@include_once("../casibe_util.php");
@include("./bootstrap.php");

if (!isset($_SESSION["valid"]) || $_SESSION["valid"] !== true) {
    header("Location: ../login.php");
    exit;
}

$uid = $_SESSION['uid'];
$uname = $_SESSION['uname'];

// === SEND MESSAGE HANDLER ===
if (isset($_POST['send']) && isset($_GET['chat'])) {
    $to_uid = $_GET['chat'];
    $contents = trim($_POST['contents']);
    $now = date('Y-m-d H:i:s');

    if (!empty($contents)) {
        $stmt = $db->prepare("INSERT INTO Message (from_uid, to_uid, postTime, contents)
                              VALUES (:from_uid, :to_uid, :postTime, :contents)");
        $stmt->execute([
            ':from_uid' => $uid,
            ':to_uid' => $to_uid,
            ':postTime' => $now,
            ':contents' => $contents
        ]);
    }
    header("Location: messages.php?chat=$to_uid");
    exit;
}

function getUserName($db, $uid) {
    $stmt = $db->prepare("SELECT name FROM User WHERE uid = :uid");
    $stmt->execute([':uid' => $uid]);
    $row = $stmt->fetch();
    return $row ? $row['name'] : 'Unknown';
}

function showChat($db, $uid, $chatUid) {
    $chatName = getUserName($db, $chatUid);
    echo "<h3 class='text-info'>Chat with $chatName</h3>";

    $query = "SELECT * FROM Message
              WHERE (from_uid = :me AND to_uid = :them) OR (from_uid = :them AND to_uid = :me)
              ORDER BY postTime ASC";
    $stmt = $db->prepare($query);
    $stmt->execute([':me' => $uid, ':them' => $chatUid]);

    echo "<div class='chat-container'>";
    while ($row = $stmt->fetch()) {
        $bubbleClass = ($row['from_uid'] == $uid) ? 'from-me' : 'from-them';
        echo "<div class='message-bubble $bubbleClass'>
                {$row['contents']}<br>
                <small>{$row['postTime']}</small>
              </div>";
    }
    echo "</div><br>";

    echo "<form method='POST'>
            <div class='mb-3'>
                <label for='contents' class='form-label'>Message:</label>
                <textarea name='contents' rows='3' class='form-control' placeholder='Type your reply...'></textarea>
            </div>
            <input type='submit' name='send' class='btn btn-primary' value='Send'>
          </form>
          <br><a href='messages.php' class='btn btn-secondary'>Back to Inbox</a>";
}

function showInboxList($db, $uid) {
    echo "<div class='card bg-secondary p-4 mb-3'>
            <h4 class='text-light'>Start a New Conversation</h4>
            <form method='GET' action='messages.php' class='d-flex gap-2'>
                <input type='text' name='searchName' class='form-control' placeholder='Search by name...' required>
                <button type='submit' class='btn btn-light'>Search</button>
            </form>
          </div>";

    if (isset($_GET['searchName'])) {
        $term = trim($_GET['searchName']);

        $query = "SELECT uid, name FROM User WHERE name LIKE :term AND uid != :uid";
        $stmt = $db->prepare($query);
        $stmt->execute([
            ':term' => $term . '%',
            ':uid' => $uid
        ]);

        if ($stmt->rowCount() > 0) {
            echo "<div class='card bg-dark p-3'><h5 class='text-info'>Search Results:</h5><ul class='list-group'>";
            while ($row = $stmt->fetch()) {
                $foundUid = $row['uid'];
                $name = $row['name'];
                echo "<li class='list-group-item bg-dark border-secondary'>
                        <a href='messages.php?chat=$foundUid' class='text-light'>$name</a>
                      </li>";
            }
            echo "</ul></div>";
        } else {
            echo "<p class='text-warning'>No users found with that name.</p>";
        }
    }

    echo "<h3 class='text-info mt-4'>Inbox</h3>";
    $query = "SELECT DISTINCT U.uid, U.name
              FROM User U
              JOIN Message M ON (M.from_uid = U.uid OR M.to_uid = U.uid)
              WHERE U.uid != :uid AND (M.from_uid = :uid OR M.to_uid = :uid)";
    $stmt = $db->prepare($query);
    $stmt->execute([':uid' => $uid]);

    $existingConvos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<ul class='list-group mb-4'>";
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="./myStyle.css" />
    <style>
        .message-bubble {
            border-radius: 15px;
            padding: 12px 16px;
            margin-bottom: 10px;
            display: inline-block;
            max-width: 70%;
            word-wrap: break-word;
            line-height: 1.4;
            font-size: 16px;
        }
        .from-them {
            background-color: #343a40;
            color: #f5f5f5;
        }
        .from-me {
            background-color: #007bff;
            color: white;
            margin-left: auto;
        }
        .chat-container {
            display: flex;
            flex-direction: column;
            gap: 12px;
            padding: 10px;
        }
    </style>
</head>
<body class="bg-dark text-light">
<?php if (function_exists('genHeader') && isset($_SESSION['valid']) && $_SESSION['valid'] === true) genHeader(); ?>

<div class="main-content">
    <h2>Welcome to your Messages, <?php echo htmlspecialchars($uname); ?>!</h2>
    <?php
        if (isset($_GET['chat'])) {
            showChat($db, $uid, $_GET['chat']);
        } else {
            showInboxList($db, $uid);
        }
    ?>
</div>

<?php if (function_exists('genFooter') && isset($_SESSION['valid']) && $_SESSION['valid'] === true) genFooter(); ?>
</body>
</html>
