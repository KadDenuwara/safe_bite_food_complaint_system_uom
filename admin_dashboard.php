<?php
include "config.php";
requireRole("admin");

$totalUsers = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role != 'admin'")->fetch_assoc()["total"];
$totalHandlers = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role='handler'")->fetch_assoc()["total"];
$totalComplainers = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role='complainer'")->fetch_assoc()["total"];
$totalComplaints = $conn->query("SELECT COUNT(*) AS total FROM complaints")->fetch_assoc()["total"];
$pending = $conn->query("SELECT COUNT(*) AS total FROM complaints WHERE status='Pending'")->fetch_assoc()["total"];
$progress = $conn->query("SELECT COUNT(*) AS total FROM complaints WHERE status='In Progress'")->fetch_assoc()["total"];
$solved = $conn->query("SELECT COUNT(*) AS total FROM complaints WHERE status='Solved'")->fetch_assoc()["total"];

$recent = $conn->query("SELECT c.*, u.name AS complainer_name FROM complaints c JOIN users u ON c.user_id=u.id ORDER BY c.created_at DESC LIMIT 5");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - SAFE BITE</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php include "partials/nav_admin.php"; ?>
<div class="container">
    <h2>Administrator Dashboard</h2>

    <div class="stats">
        <div>Total Users <strong><?php echo $totalUsers; ?></strong></div>
        <div>Handlers <strong><?php echo $totalHandlers; ?></strong></div>
        <div>Complainers <strong><?php echo $totalComplainers; ?></strong></div>
        <div>Complaints <strong><?php echo $totalComplaints; ?></strong></div>
    </div>

    <div class="stats">
        <div>Pending <strong><?php echo $pending; ?></strong></div>
        <div>In Progress <strong><?php echo $progress; ?></strong></div>
        <div>Solved <strong><?php echo $solved; ?></strong></div>
    </div>

    <h3>Recent Complaints</h3>
    <table>
        <tr><th>ID</th><th>Title</th><th>Complainer</th><th>Status</th><th>Date</th></tr>
        <?php while($row = $recent->fetch_assoc()): ?>
        <tr>
            <td><?php echo clean($row["complaint_code"]); ?></td>
            <td><?php echo clean($row["title"]); ?></td>
            <td><?php echo clean($row["complainer_name"]); ?></td>
            <td><?php echo clean($row["status"]); ?></td>
            <td><?php echo clean($row["created_at"]); ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
