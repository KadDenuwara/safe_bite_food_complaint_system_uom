<?php
include "config.php";
if (isLoggedIn()) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: admin_dashboard.php");
    } elseif ($_SESSION['role'] == 'handler') {
        header("Location: handler_dashboard.php");
    } else {
        header("Location: complainer_dashboard.php");
    }
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>SAFE BITE - Food Complaint Management System UOM </title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="home">
<div class="card">
    <h1>SAFE BITE</h1>
    <h2>Food Complaint Management System UOM</h2>
    <p>Submit, track, and resolve university food complaints safely and transparently.</p>
    <a class="btn" href="login.php">Login</a>
    <a class="btn secondary" href="register.php">Register as Complainer</a>
</div>
</body>
</html>
