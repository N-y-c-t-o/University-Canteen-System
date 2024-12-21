<?php
// Include database connection
include '../db_connection.php';

// Convert Myanmar Time (MMT) to UTC
function convertToUTC($datetime) {
    $datetimeUTC = new DateTime($datetime, new DateTimeZone('Asia/Yangon'));
    $datetimeUTC->setTimezone(new DateTimeZone('UTC'));
    return $datetimeUTC->format('Y-m-d H:i:s');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Convert input times from Myanmar Time (MMT) to UTC
    $startID = convertToUTC($_POST['startID']);
    $endID = convertToUTC($_POST['endID']);

    // Prepare and execute the SQL query
    $sql = "INSERT INTO noticeboard (title, content, startID, endID) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $title, $content, $startID, $endID);

    if ($stmt->execute()) {
        echo '<div class="alert alert-success alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                Notice added successfully.
              </div>';
    } else {
        $error = "Error executing statement: " . $stmt->error;
        echo '<div class="alert alert-danger alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Error:</strong> ' . $error . '
              </div>';
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Notice</title>
</head>
<body>
    <h1>Add Notice</h1>
    <form method="POST">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required><br><br>
        <label for="content">Content:</label>
        <textarea id="content" name="content" required></textarea><br><br>
        <label for="startID">Start Date and Time (MMT):</label>
        <input type="datetime-local" id="startID" name="startID" required><br><br>
        <label for="endID">End Date and Time (MMT):</label>
        <input type="datetime-local" id="endID" name="endID" required><br><br>
        <input type="submit" value="Submit">
    </form>
</body>
</html>
