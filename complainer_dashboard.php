<?php
include "config.php";
requireRole("complainer");

$user_id = $_SESSION["user_id"];

function countStatus($conn, $user_id, $status) {
    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM complaints WHERE user_id=? AND status=?");
    $stmt->bind_param("is", $user_id, $status);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc()["total"];
}

$pending = countStatus($conn, $user_id, "Pending");
$progress = countStatus($conn, $user_id, "In Progress");
$solved = countStatus($conn, $user_id, "Solved");

$stmt = $conn->prepare("SELECT * FROM complaints WHERE user_id=? ORDER BY created_at DESC LIMIT 5");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$recent = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Complainer Dashboard - SAFE BITE</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php include "partials/nav_complainer.php"; ?>
<div class="container">
    <h2>Welcome, <?php echo clean($_SESSION["name"]); ?></h2>

    <div class="stats">
        <div>Pending <strong><?php echo $pending; ?></strong></div>
        <div>In Progress <strong><?php echo $progress; ?></strong></div>
        <div>Solved <strong><?php echo $solved; ?></strong></div>
    </div>

    <h3>Recent Complaints</h3>

    <?php if ($recent->num_rows == 0): ?>
        <p>No complaints yet. Submit your first complaint.</p>
    <?php else: ?>
        <table>
            <tr><th>ID</th><th>Title</th><th>Status</th><th>Actions</th><th>Date</th></tr>
            <?php while($row = $recent->fetch_assoc()): ?>
            <tr>
                <td><?php echo clean($row["complaint_code"]); ?></td>
                <td><?php echo clean($row["title"]); ?></td>
                <td><?php echo clean($row["status"]); ?></td>
                <td><?php echo clean($row["actions_taken"] ?: "-"); ?></td>
                <td><?php echo clean($row["created_at"]); ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    <?php endif; ?>
</div>
</body>
</html>
