<?php
session_start();
include 'config.php';

if (!isset($_SESSION['SESSION_EMAIL'])) {
    header("Location: login.php");
    die();
}

$email = $_SESSION['SESSION_EMAIL'];
$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $old_password = mysqli_real_escape_string($conn, md5($_POST['old_password']));
    $new_password = mysqli_real_escape_string($conn, md5($_POST['new_password']));
    $confirm_password = mysqli_real_escape_string($conn, md5($_POST['confirm_password']));

    // Validate that the new password length is at least 8 characters
    if (strlen($_POST['new_password']) < 8) {
        $msg = "<div class='alert alert-info'>New password must be at least 8 characters long.</div>";
    } 
    // Validate that the new passwords match
    elseif ($_POST['new_password'] !== $_POST['confirm_password']) {
        $msg = "<div class='alert alert-danger'>New passwords do not match.</div>";
    }   else {
        // Fetch the user's current password
        $stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Verify the old password
        if ($old_password === $user['password']) {
            // Update the new password in the database
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
            $stmt->bind_param("ss", $new_password, $email);
            $stmt->execute();


            if ($stmt->affected_rows === 1) {
                $msg = "<div class='alert alert-success'>Password has been reset successfully.</div>";

            } else {
                $msg = "<div class='alert alert-danger'>Failed to update password.</div>";

            }
        } else {
            $msg = "<div class='alert alert-danger'>Old password is incorrect.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="zxx">

<head>
    <title>Login Form - Brave Coder</title>
    <!-- Meta tag Keywords -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8" />
    <meta name="keywords"
        content="Login Form" />
    <!-- //Meta tag Keywords -->

    <link href="//fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!--/Style-CSS -->
    <link rel="stylesheet" href="css/bounce.css" type="text/css" media="all" />
    <!--//Style-CSS -->

    <script src="https://kit.fontawesome.com/af562a2a63.js" crossorigin="anonymous"></script>

</head>

<body>

    <!-- form section start -->
    <section class="w3l-mockup-form">
        <div class="container">
            <!-- /form -->
            <div class="workinghny-form-grid">
                <div class="main-mockup">
                    <div class="alert-close">
                        <span class="fa fa-close"></span>
                    </div>
                    <div class="w3l_form align-self">
                        <div class="left_grid_info3">
                            <img src="photos/tealeaf.png" alt="Ramen" class="ramen">
                        </div>
                    </div>
                    <div class="content-wthree">
                    <h2>Reset Password</h2>
                        <?php if (isset($msg)) { echo "<p>$msg</p>"; } ?>
                        <form action="" method="post">                        
                            <input type="password" class="old_password" name="old_password" placeholder="Enter Old Password" required>                           
                            <input type="password" class="new_password" name="new_password" placeholder="Enter New Password" required>                          
                            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm New Password" required>
                            <button name="submit" class="btn" type="submit">Reset Password</button>
                        </form>
                        <div class="social-icons">
                            <p>Back to! <a href="homePage.php">Home</a>.</p>
                        </div>
                        
                    </div>
                </div>
            </div>
            <!-- //form -->
        </div>
    </section>
    <!-- //form section start -->

    <script src="js/jquery.min.js"></script>
    <script>
    $(document).ready(function () {
        $('.alert-close').on('click', function () {
            $('.main-mockup').fadeOut('slow', function () {
                window.location.href = 'homePage.php'; // Redirect to homePage.php
            });
        });
    });
    </script>

</body>

</html>