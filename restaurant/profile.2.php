<?php
session_start();

require 'C:/xampp/htdocs/UIT/config.php';

if (!isset($_SESSION['rs_id'])) {
    header("Location: restLogin.php");
    exit();
}

$userId = $_SESSION['rs_id'];
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
