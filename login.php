<!-- Code by Brave Coder - https://youtube.com/BraveCoder -->

<?php
    session_start();
    if (isset($_SESSION['SESSION_EMAIL'])) {
        header("Location: homePage.php");
        die();
    }

    include 'config.php'; 

    $msg = "";

    if (isset($_GET['verification'])) {
        if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE code='{$_GET['verification']}'")) > 0) {
            $query = mysqli_query($conn, "UPDATE users SET code='' WHERE code='{$_GET['verification']}'");
            
            if ($query) {
                $msg = "<div class='alert alert-success'>Account registration has been successfully completed.</div>";
            }
            
        } else {
            header("Location: login.php");
        }
    }

    if (isset($_POST['submit'])) {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, md5($_POST['password']));

        $sql = "SELECT * FROM users WHERE email='{$email}' AND password='{$password}'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);

            if (empty($row['code'])) {
                $_SESSION['SESSION_EMAIL'] = $email;
                header("Location: homePage.php");
            } else {
                $msg = "<div class='alert alert-info'>First verify your account and try again.</div>";
            }
        } else {
            $msg = "<div class='alert alert-danger'>Email or password do not match.</div>";
        }

        if(is_array($row)) 
        {
                $_SESSION["user_id"] = $row['u_id']; 
                 header("refresh:1;url=index.php"); 
        } 
    else
        {
                  $message = "Invalid Username or Password!"; 
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
                    <!-- <a class="text-decoration: none;" href="homePage.php"> -->

                        <span class="fa fa-close"></span>
                    </div>
                    <div class="w3l_form align-self">
                    <div class="left_grid_info2">
                        <img src="photos/papaya.png" alt="Glass" class="glass">
                        <img src="photos/round.png" alt="Pepsi" class="pepsi">
                        
                    </div>


                    </div>
                    <div class="content-wthree">
                        <h2>Login Now</h2>
                        <p>Welcome Back!
                            Stay connected with all the delicious updates at Cafeteria.</p>
                        <?php echo $msg; ?>
                        <form action="" method="post">
                            <input type="email" class="email" name="email" placeholder="Enter Your Email" required>
                            <input type="password" class="password" name="password" placeholder="Enter Your Password" style="margin-bottom: 2px;" required>
                            <p><a href="forgot-password.php" style="margin-bottom: 15px; display: block; text-align: right;">Forgot Password?</a></p>
                            <button name="submit" name="submit" class="btn" type="submit">Login</button>
                        </form>
                        <div class="social-icons">
                            <p>
                                <a href="homePage.php">Home</a>
                                <span style="margin: 0 15px;">|</span> <!-- Adds a separator with space -->
                                <a href="register.php">Register</a>
                            </p>
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