<?php
session_start();
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
    } else {
    $isPrivate = 0;
    }
    
    $postTime = date("Y-m-d H:i:s");
    $date = date("Y-m-d");

    $stmt = $db->prepare("INSERT INTO Post (uid, caption, postTime, date, isPrivate) VALUES (:uid, :caption, :postTime, :date, :isPrivate)");
    $stmt->execute([
        ":uid" => $uid,
        ":caption" => $caption,
        ":postTime" => $postTime,
        ":date" => $date,
        ":isPrivate" => $isPrivate
    ]);

    $pid = $db->lastInsertId();

    // echo "<div class='alert alert-success'>Post created! Post ID: $pid</div>";
    echo "<script>const newPostID = $pid;</script>"; // for JS usage
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>New Post</title>
</head>
<body class="bg-dark text-light">
<?php genHeader(); ?>

<div class="main-content">
    <h2 style='text-align:center;'>New Post</h2>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="caption" class="form-label">Caption</label>
            <textarea name="caption" placeholder="Write your caption here..." class="form-control captionInput"></textarea>
        </div>

        <div class="mb-3">
            <label for="isPrivate" class="form-label">Privacy:</label><br>
            <input type="checkbox" name="isPrivate" id="isPrivate" />
            <label for="isPrivate">Make this post private</label>
        </div>

        <!-- Placeholder for JS-generated workout input fields -->
        <div id="workout-section"></div>

        <button type="submit" class="btn btn-primary mt-2">Post</button>
    </form>
</div>

<?php genFooter(); ?>
</body>
</html>
