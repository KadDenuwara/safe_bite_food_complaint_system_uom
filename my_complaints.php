<?php
include "config.php";
requireRole("complainer");

$user_id = $_SESSION["user_id"];

if (isset($_GET["delete"])) {
    $id = intval($_GET["delete"]);
    $stmt = $conn->prepare("DELETE FROM complaints WHERE id=? AND user_id=?");
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
    header("Location: my_complaints.php");
    exit();
}

$stmt = $conn->prepare("SELECT c.*, u.name AS handler_name FROM complaints c LEFT JOIN users u ON c.handler_id=u.id WHERE c.user_id=? ORDER BY c.created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Complaints - SAFE BITE</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php include "partials/nav_complainer.php"; ?>
<div class="container">
    <h2>My Complaints</h2>
    <table>
        <tr>
            <th>ID</th><th>Title</th><th>Handler</th><th>Status</th>
            <th>Actions Taken</th><th>Media</th><th>Date</th><th>Delete</th>
        </tr>

        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo clean($row["complaint_code"]); ?></td>
            <td><?php echo clean($row["title"]); ?></td>
            <td><?php echo clean($row["handler_name"] ?: "Not assigned"); ?></td>
            <td><?php echo clean($row["status"]); ?></td>
            <td><?php echo clean($row["actions_taken"] ?: "No action yet"); ?></td>
            <td>
                <?php if($row["media_path"]): ?>
                    <a href="<?php echo clean($row["media_path"]); ?>" target="_blank">View</a>
                <?php else: ?>
                    No media
                <?php endif; ?>
            </td>
            <td><?php echo clean($row["created_at"]); ?></td>
            <td><a class="danger" href="?delete=<?php echo $row["id"]; ?>" onclick="return confirm('Delete this complaint?')">Delete</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
