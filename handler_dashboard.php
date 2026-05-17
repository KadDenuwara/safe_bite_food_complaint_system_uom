<?php
include "config.php";
requireRole("handler");

$handler_id = $_SESSION["user_id"];

function countHandlerStatus($conn, $handler_id, $status) {
    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM complaints WHERE handler_id=? AND status=?");
    $stmt->bind_param("is", $handler_id, $status);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc()["total"];
}

$pending = countHandlerStatus($conn, $handler_id, "Pending");
$progress = countHandlerStatus($conn, $handler_id, "In Progress");
$solved = countHandlerStatus($conn, $handler_id, "Solved");

$stmt = $conn->prepare("SELECT c.*, u.name AS complainer_name FROM complaints c JOIN users u ON c.user_id=u.id WHERE c.handler_id=? ORDER BY c.created_at DESC");
$stmt->bind_param("i", $handler_id);
$stmt->execute();
$complaints = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Handler Dashboard</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php include "partials/nav_handler.php"; ?>
<div class="container">
    <h2>Handler Dashboard</h2>

    <div class="stats">
        <div>Pending <strong><?php echo $pending; ?></strong></div>
        <div>In Progress <strong><?php echo $progress; ?></strong></div>
        <div>Solved <strong><?php echo $solved; ?></strong></div>
    </div>

    <h3>Assigned Complaints</h3>
    <table>
        <tr><th>ID</th><th>Title</th><th>Complainer</th><th>Status</th><th>Date</th><th>Action</th></tr>
        <?php while($row = $complaints->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row["complaint_code"]; ?></td>
            <td><?php echo $row["title"]; ?></td>
            <td><?php echo $row["complainer_name"]; ?></td>
            <td><?php echo $row["status"]; ?></td>
            <td><?php echo $row["created_at"]; ?></td>
            <td><a href="view_complaint.php?id=<?php echo $row["id"]; ?>">View</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
