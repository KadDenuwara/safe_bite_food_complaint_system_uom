<?php
include "config.php";
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // Security rule: public registration can create complainer accounts only.
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'complainer')");
    $stmt->bind_param("sss", $name, $email, $password);

    if ($stmt->execute()) {
        $message = "Registration successful. You can login now.";
    } else {
        $message = "Email already exists or registration failed.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register - SAFE BITE</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="form-box">
    <h2>Complainer Registration</h2>
    <p class="msg"><?php echo clean($message); ?></p>
    <form method="POST">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Register</button>
    </form>
    <a href="login.php">Already have an account? Login</a>
</div>
</body>
</html>
