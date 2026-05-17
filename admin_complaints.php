<?php
include "config.php";
requireRole("admin");

if (isset($_GET["delete"])) {
    $id = intval($_GET["delete"]);
    $stmt = $conn->prepare("DELETE FROM complaints WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: admin_complaints.php");
    exit();
}

$sql = "SELECT c.*, u.name AS complainer_name, h.name AS handler_name 
        FROM complaints c 
        JOIN users u ON c.user_id=u.id 
        LEFT JOIN users h ON c.handler_id=h.id 
        ORDER BY c.created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Maintain Complaints - SAFE BITE</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php include "partials/nav_admin.php"; ?>
<div class="container">
    <h2>System Integrity and Complaint Maintenance</h2>
    <table>
        <tr><th>ID</th><th>Title</th><th>Complainer</th><th>Handler</th><th>Status</th><th>Actions Taken</th><th>Date</th><th>Action</th></tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo clean($row["complaint_code"]); ?></td>
            <td><?php echo clean($row["title"]); ?></td>
            <td><?php echo clean($row["complainer_name"]); ?></td>
            <td><?php echo clean($row["handler_name"] ?: "Not assigned"); ?></td>
            <td><?php echo clean($row["status"]); ?></td>
            <td><?php echo clean($row["actions_taken"] ?: "No action yet"); ?></td>
            <td><?php echo clean($row["created_at"]); ?></td>
            <td><a class="danger" onclick="return confirm('Delete this complaint permanently?')" href="?delete=<?php echo $row["id"]; ?>">Delete</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
