<?php
session_start();
include 'C:/xampp/htdocs/UIT/config.php';

// Check if the user is already logged in
if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'restaurant') {
    header("Location: dashboard.php"); // Redirect to dashboard if already logged in
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $restname = $_POST['restname'];
    $password = $_POST['password'];

    // Prepare to check for restaurant user type
    $stmt = $conn->prepare("SELECT rs_id, password FROM restaurant WHERE restname = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . $conn->error);
    }

    $stmt->bind_param("s", $restname);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($fetchedId, $storedPassword);
    $stmt->fetch();

    // Check if any rows were returned and then verify password
    if ($stmt->num_rows > 0) {
        if ($password === $storedPassword) {
            $_SESSION['user_id'] = $fetchedId;
            $_SESSION['role'] = 'restaurant'; // Set role as restaurant
            header("Location: dashboard.php"); // Redirect to restaurant dashboard
            exit();
        } else {
            $error = "Invalid restname or password.";
        }
    } else {
        $error = "Invalid restname or password.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Restaurant Login</title>
</head>
<body>
    <h2>Restaurant Login</h2>
    <form method="post" action="">
        <label for="restname">restname:</label>
        <input type="text" id="restname" name="restname" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <input type="submit" value="Login">
        <?php if (isset($error)) echo "<p>$error</p>"; ?>
    </form>
</body>
</html>
