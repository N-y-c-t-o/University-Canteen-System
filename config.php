<?php

$conn = mysqli_connect("localhost", "root", "", "unicanteen");

// if (!$conn) {
//     echo "Connection Failed";
// }

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>