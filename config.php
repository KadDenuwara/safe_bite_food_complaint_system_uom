<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "safebite_food_complaints";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: index.php");
        exit();
    }
}

function requireRole($role) {
    requireLogin();
    if ($_SESSION['role'] !== $role) {
        header("Location: index.php");
        exit();
    }
}

function clean($data) {
    return htmlspecialchars($data ?? "", ENT_QUOTES, "UTF-8");
}
?>
