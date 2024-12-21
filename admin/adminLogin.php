<?php
    session_start();
    
    if (isset($_SESSION['ADMIN_SESSION_EMAIL'])) {
        header("Location: dashboard.php");
        die();
    }

    include 'C:/xampp/htdocs/UIT/config.php';

    $msg = "";

    if (isset($_POST['submit'])) {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, md5($_POST['password']));
        
        $sql = "SELECT * FROM admin WHERE email='{$email}' AND password='{$password}'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);

            $_SESSION['ADMIN_SESSION_EMAIL'] = $email;
            header("Location: dashboard.php");
        } else {
            $msg = "<div class='alert alert-danger'>Email or password do not match.</div>";

        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<head>
    <title>Admin Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8" />
    <meta name="keywords" content="Admin Login Form" />
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
                        <h2>Admin Login</h2>
                        <p>Enter your admin credentials to access the dashboard.</p>
                        <?php echo $msg; ?>
                        <form action="adminLogin.php" method="post">
                            <input type="email" class="email" name="email" placeholder="Enter Your Email" required>
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
