<?php
session_start();
if (!isset($_SESSION['rs_id'])) {
    header("Location: restLogin.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_SANITIZE_STRING);

    // Fetch pending orders from the database
include 'C:/xampp/htdocs/UIT/config.php';
    $rs_id = $_SESSION['rs_id']; // Assuming restaurant ID is user ID

    $sql = "DELETE FROM `users_orders` WHERE orderNum='$id' AND rs_id='$rs_id' ";

    if ($conn->query($sql) === TRUE) {
        header("Location: view_pending_order.php");
        exit();
    } else {
        echo "<script>alert('Error deleting record: " . $conn->error."')</script>";
    }

    $conn->close();
} else {
    header("Location: view_pending_order.php");
    exit();
}
?>
