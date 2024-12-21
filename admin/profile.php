<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user profile from database
require '../db_connection.php';
$userId = $_SESSION['user_id'];
// Implement profile fetching logic as needed
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
</head>
<body>
    <h2>Profile</h2>
    <!-- Display user profile information here -->
</body>
</html>
