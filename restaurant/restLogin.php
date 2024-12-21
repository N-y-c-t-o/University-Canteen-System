<?php
session_start();
if (isset($_SESSION['REST_SESSION_NAME'])) {
    header("Location: dashboard.php");
    die();
}

include 'C:/xampp/htdocs/UIT/config.php';

$msg = "";

if (isset($_POST['submit'])) {
    $restname = mysqli_real_escape_string($conn, $_POST['name']);
    $password = mysqli_real_escape_string($conn, md5($_POST['password']));
    
    $sql = "SELECT * FROM restaurant WHERE restname='{$restname}' AND password='{$password}'"; // Adjust the table name as necessary
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Error in query: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        echo "rs_id: " . $row['rs_id']; // Output for debugging
        $_SESSION['REST_SESSION_NAME'] = $restname;
        $_SESSION['rs_id'] = $row['rs_id']; // Ensure this line is executed
        header("Location: dashboard.php");
        exit(); // Ensure that after redirect, no further code is executed
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Restaurant Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="//fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/UIT/css/bounce.css" type="text/css" media="all" />
    <script src="https://kit.fontawesome.com/af562a2a63.js" crossorigin="anonymous"></script>
</head>

<body>

    <!-- form section start -->
    <section class="w3l-mockup-form">
        <div class="container">
            <div class="workinghny-form-grid">
                <div class="main-mockup">
                    <div class="alert-close">
                        <span class="fa fa-close"></span>
                    </div>
                    <div class="w3l_form align-self">
                        <div class="left_grid_info2">
                            <img src="/UIT/photos/papaya.png" alt="Glass" class="glass">
                            <img src="/UIT/photos/round.png" alt="Pepsi" class="pepsi">
                        </div>
                    </div>
                    <div class="content-wthree">
                        <h2>Restaurant Login</h2>
                        <p>Enter your credentials to access the dashboard.</p>
                        <?php echo $msg; ?>
                        <form action="restLogin.php" method="post"> <!-- Change the action to your file -->
                            <input type="text" class="name" name="name" placeholder="Enter Your Name" required>
                            <input type="password" class="password" name="password" placeholder="Enter Your Password" required>
                            <button name="submit" class="btn" type="submit">Login</button>
                        </form>
                        <div class="social-icons">
                            <p>
                                <a href="/UIT/index.php">Home</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- //form section start -->

    <script src="js/jquery.min.js"></script>
    <script>
        $(document).ready(function (c) {
            $('.alert-close').on('click', function (c) {
                $('.main-mockup').fadeOut('slow', function (c) {
                    $('.main-mockup').remove();
                });
            });
        });
    </script>

</body>
</html>
