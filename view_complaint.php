<?php
include "config.php";
requireRole("handler");

$handler_id = $_SESSION["user_id"];
$id = intval($_GET["id"]);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["status"])) {
        $status = $_POST["status"];
        $actions = trim($_POST["actions_taken"]);

        $stmt = $conn->prepare("UPDATE complaints SET status=?, actions_taken=? WHERE id=? AND handler_id=?");
        $stmt->bind_param("ssii", $status, $actions, $id, $handler_id);
        $stmt->execute();
    }

    if (isset($_POST["delete"])) {
        $stmt = $conn->prepare("DELETE FROM complaints WHERE id=? AND handler_id=?");
        $stmt->bind_param("ii", $id, $handler_id);
        $stmt->execute();
        header("Location: handler_dashboard.php");
        exit();
    }
}

$stmt = $conn->prepare("SELECT c.*, u.name AS complainer_name, u.email FROM complaints c JOIN users u ON c.user_id=u.id WHERE c.id=? AND c.handler_id=?");
$stmt->bind_param("ii", $id, $handler_id);
$stmt->execute();
$c = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Complaint - SAFE BITE</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php include "partials/nav_handler.php"; ?>
<div class="container">
    <h2>Complaint Details</h2>

    <?php if(!$c): ?>
        <p>Complaint not found.</p>
    <?php else: ?>
        <div class="detail-card">
            <p><b>ID:</b> <?php echo clean($c["complaint_code"]); ?></p>
            <p><b>Title:</b> <?php echo clean($c["title"]); ?></p>
            <p><b>Description:</b> <?php echo clean($c["description"]); ?></p>
            <p><b>Complainer:</b> <?php echo clean($c["complainer_name"]); ?> (<?php echo clean($c["email"]); ?>)</p>
            <p><b>Status:</b> <?php echo clean($c["status"]); ?></p>
            <p><b>Actions Taken:</b> <?php echo clean($c["actions_taken"] ?: "No action yet"); ?></p>
            <p><b>Date:</b> <?php echo clean($c["created_at"]); ?></p>
            <p><b>Media:</b>
                <?php if($c["media_path"]): ?>
                    <a href="<?php echo clean($c["media_path"]); ?>" target="_blank">Open Media</a>
                <?php else: ?>
                    No media uploaded
                <?php endif; ?>
            </p>

            <form method="POST">
                <label>Status</label>
                <select name="status">
                    <option value="Pending" <?php if($c["status"]=="Pending") echo "selected"; ?>>Pending</option>
                    <option value="In Progress" <?php if($c["status"]=="In Progress") echo "selected"; ?>>In Progress</option>
                    <option value="Solved" <?php if($c["status"]=="Solved") echo "selected"; ?>>Solved</option>
                </select>

                <label>Actions Taken</label>
                <textarea name="actions_taken" placeholder="Describe the actions taken..."><?php echo clean($c["actions_taken"]); ?></textarea>

                <button type="submit">Update Complaint</button>
            </form>

            <form method="POST" onsubmit="return confirm('Delete this complaint?')">
                <input type="hidden" name="delete" value="1">
                <button class="danger-btn" type="submit">Delete Complaint</button>
            </form>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
