<?php
include "config.php";
requireRole("complainer");

$message = "";
$handlers = $conn->query("SELECT id, name FROM users WHERE role='handler'");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST["title"]);
    $description = trim($_POST["description"]);
    $handler_id = $_POST["handler_id"];
    $user_id = $_SESSION["user_id"];
    $complaint_code = "UOM-FC-" . date("Ymd") . "-" . rand(1000,9999);
    $media_path = null;

    if (!empty($_FILES["media"]["name"])) {
        $upload_dir = "uploads/";
        if (!is_dir($upload_dir)) mkdir($upload_dir);
        $file_name = time() . "_" . basename($_FILES["media"]["name"]);
        $target = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES["media"]["tmp_name"], $target)) {
            $media_path = $target;
        }
    }

    $stmt = $conn->prepare("INSERT INTO complaints (complaint_code, user_id, handler_id, title, description, media_path) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("siisss", $complaint_code, $user_id, $handler_id, $title, $description, $media_path);

    if ($stmt->execute()) {
        $message = "Complaint submitted successfully. Complaint ID: " . $complaint_code;
    } else {
        $message = "Complaint submission failed.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Submit Complaint - SAFE BITE</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php include "partials/nav_complainer.php"; ?>
<div class="form-box wide">
    <h2>Submit New Food Complaint</h2>
    <p class="msg"><?php echo $message; ?></p>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Complaint Title" required>
        <textarea name="description" placeholder="Complaint Description" required></textarea>
        <select name="handler_id" required id="handlerSelect">
            <option value="" disabled selected hidden >Select Handler</option>
            <?php while($h = $handlers->fetch_assoc()): ?>
                <option value="<?php echo $h['id']; ?>"><?php echo $h['name']; ?></option>
            <?php endwhile; ?>
        </select>
        <label>Optional Media Upload: photo, audio, or video</label>
        <input type="file" name="media" accept="image/*,audio/*,video/*">
        <button type="submit">Submit Complaint</button>
    </form>
</div>
</body>
</html>
