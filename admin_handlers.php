<?php
include "config.php";
requireRole("admin");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_handler"])) {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'handler')");
    $stmt->bind_param("sss", $name, $email, $password);

    if ($stmt->execute()) {
        $message = "Handler account created successfully.";
    } else {
        $message = "Unable to create handler. Email may already exist.";
    }
}

if (isset($_GET["delete"])) {
    $id = intval($_GET["delete"]);
    $stmt = $conn->prepare("DELETE FROM users WHERE id=? AND role ='handler'");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: admin_handlers.php");
    exit();
}

$handlers = $conn->query("SELECT * FROM users WHERE role='handler' ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Handlers - SAFE BITE</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php include "partials/nav_admin.php"; ?>
<div class="container">
    <h2>Manage Handler Accounts</h2>
    <p class="msg"><?php echo clean($message); ?></p>

    <div class="form-panel">
        <h3>Add New Handler</h3>
        <form method="POST">
            <input type="hidden" name="add_handler" value="1">
            <input type="text" name="name" placeholder="Handler Name" required>
            <input type="email" name="email" placeholder="Handler Email" required>
            <input type="password" name="password" placeholder="Temporary Password" required>
            <button type="submit">Add Handler</button>
        </form>
    </div>

    <h3>Existing Handlers</h3>
    <table>
        <tr><th>Name</th><th>Email</th><th>Created</th><th>Action</th></tr>
        <?php while($h = $handlers->fetch_assoc()): ?>
        <tr>
            <td><?php echo clean($h["name"]); ?></td>
            <td><?php echo clean($h["email"]); ?></td>
            <td><?php echo clean($h["created_at"]); ?></td>
            <td><a class="danger" onclick="return confirm('Delete this handler?')" href="?delete=<?php echo $h["id"]; ?>">Delete</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
