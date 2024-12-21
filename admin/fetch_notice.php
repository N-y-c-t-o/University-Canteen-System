<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');
// fetch_notice.php
session_start();
require '../config.php'; // Adjust the path if necessary

// // Check if user is logged in and is an admin
// if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
//     http_response_code(403);
//     echo json_encode(['error' => 'Access denied.']);
//     exit();
// }

if (isset($_GET['noticeid'])) {
    $noticeid = intval($_GET['noticeid']);

    // Prepare and execute SELECT statement
    $stmt = $conn->prepare("SELECT * FROM noticeboard WHERE noticeid = ?");
    if ($stmt) {
        $stmt->bind_param("i", $noticeid);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $notice = $result->fetch_assoc();
            if ($notice) {
                echo json_encode($notice);
            } else {
                echo json_encode(['error' => 'Notice not found.']);
            }
        } else {
            echo json_encode(['error' => 'Error executing query: ' . htmlspecialchars($stmt->error)]);
        }
        $stmt->close();
    } else {
        echo json_encode(['error' => 'Error preparing statement: ' . htmlspecialchars($conn->error)]);
    }
} else {
    echo json_encode(['error' => 'No notice ID provided.']);
}
?>
