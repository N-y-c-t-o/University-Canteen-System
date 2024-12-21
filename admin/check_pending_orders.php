<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch pending orders from database
require '../db_connection.php';
$d_id = $_SESSION['user_id']; // Assuming restaurant ID is user ID
// Implement order fetching logic as needed
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pending Orders</title>
</head>
<body>
    <h2>Pending Orders</h2>
    <!-- Display pending orders here -->
</body>
</html>
