<?php
include "config.php";
requireLogin();

$user_id = $_SESSION["user_id"];
$message = "";

$stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);

    if (!empty($_POST["password"])) {
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET name=?, email=?, password=? WHERE id=?");
        $stmt->bind_param("sssi", $name, $email, $password, $user_id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET name=?, email=? WHERE id=?");
        $stmt->bind_param("ssi", $name, $email, $user_id);
    }

    if ($stmt->execute()) {
        $_SESSION["name"] = $name;
        $message = "Account updated successfully.";
    } else {
        $message = "Update failed.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Account</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php
if ($_SESSION["role"] == "admin") include "partials/nav_admin.php";
elseif ($_SESSION["role"] == "handler") include "partials/nav_handler.php";
else include "partials/nav_complainer.php";
?>
<div class="form-box">
    <h2>Account Management</h2>
    <p class="msg"><?php echo $message; ?></p>
    <form method="POST">
        <input type="text" name="name" value="<?php echo $user['name']; ?>" required>
        <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
        <input type="password" name="password" placeholder="New Password (optional)">
        <button type="submit">Update Account</button>
    </form>
</div>
</body>
</html>
