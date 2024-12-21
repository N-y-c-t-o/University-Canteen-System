<!-- Code by Brave Coder - https://youtube.com/BraveCoder -->

<?php
    //Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    session_start();
    if (isset($_SESSION['SESSION_EMAIL'])) {
        header("Location: homePage.php");
        die();
    }

    //Load Composer's autoloader
    require 'vendor/autoload.php';

    include 'config.php';
    $msg = "";

    if (isset($_POST['submit'])) {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, md5($_POST['password']));
        $confirm_password = mysqli_real_escape_string($conn, md5($_POST['confirm-password']));
        $code = mysqli_real_escape_string($conn, md5(rand()));

    // Validate username
    if (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $msg = "<div class='alert alert-danger'>Username should contain only letters and spaces.</div>";
    }
    // Validate phone number
    elseif (!preg_match("/^\d{8,11}$/", $phone)) {
        $msg = "<div class='alert alert-danger'>Phone number should have between 8 and 11 digits.</div>";
    }
    // Check password length
    elseif (strlen($_POST['password']) < 8) {
        $msg = "<div class='alert alert-danger'>Password must be at least 8 characters long.</div>";
    } elseif ($password !== $confirm_password) {
        $msg = "<div class='alert alert-danger'>Password and Confirm Password do not match.</div>";
    } elseif (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE email='{$email}'")) > 0) {
        $msg = "<div class='alert alert-danger'>{$email} - This email address has been already existed.</div>";
    } else {
        $sql = "INSERT INTO users (name, phone, email, password, code) VALUES ('{$name}', '{$phone}', '{$email}', '{$password}', '{$code}')";
        $result = mysqli_query($conn, $sql);

        // if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE email='{$email}'")) > 0) {
        //     $msg = "<div class='alert alert-danger'>{$email} - This email address has a registered account already.</div>";
        // } else {
        //     if ($password === $confirm_password) {
        //         $sql = "INSERT INTO users (name, phone, email, password, code) VALUES ('{$name}', '{$phone}', '{$email}', '{$password}', '{$code}')";
        //         $result = mysqli_query($conn, $sql);

                if ($result) {
                    echo "<div style='display: none;'>";
                    //Create an instance; passing `true` enables exceptions
                    $mail = new PHPMailer(true);

                    try {
                        //Server settings
                        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                        $mail->isSMTP();                                            //Send using SMTP
                        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                        $mail->Username   = 'hninshweyiwint2022@gmail.com';                     //SMTP username
                        $mail->Password   = 'givw ovku mvla yjqe';                               //SMTP password //wrmn gclc lcrn tcbm
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
                        $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS` "use 465 if PHPMailer::ENCRYPTION_SMTPS"

                        //file_put_contents('mail_error.log', $mail->ErrorInfo, FILE_APPEND);

                        //Recipients
                        $mail->setFrom('hninshweyiwint2022@gmail.com', 'University Canteen');
                        $mail->addAddress($email);

                        //Content
                        $mail->isHTML(true);                                  //Set email format to HTML
                        $mail->Subject = 'Account Registration - University Canteen';
                        // $mail->Body    = 'Here is the verification link hsyw<b><a href="http://localhost/UIT/login.php?verification='.$code.'">http://localhost/UIT/login.php?verification='.$code.'</a></b>';

                        $mail->Body = 'Hi there!<br><br>
                            Thank you for joining our community. To complete your registration and start enjoying all the delicious perks we offer, please take a moment to verify your email address by clicking the link below:<br><br>
                            <b><a href="http://localhost/UIT/login.php?verification=' . $code . '">Verify Your Email</a></b><br><br>
                            If you didn’t create an account with us, no worries! You can safely ignore this email.<br><br>
                            Cheers,<br>
                            The University Canteen Team';

                        $mail->send();
                        echo 'Message has been sent';
                    } catch (Exception $e) {
                        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    }
                    echo "</div>";
                    $msg = "<div class='alert alert-info'>We've sent a verification link on your email address.</div>";
                } else {
                    $msg = "<div class='alert alert-danger'>Something wrong went.</div>";
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
            <!-- Triangle Background -->
        <!-- <div class="triangle-container">
            <div class="triangle triangle-1"></div>
            <div class="triangle triangle-2"></div>
            <div class="triangle triangle-3"></div>
        </div> -->
            <!-- /form -->
            <div class="workinghny-form-grid">
                <div class="main-mockup">
                    <div class="alert-close">
                        <span class="fa fa-close"></span>
                    </div>
                    <div class="w3l_form align-self">
                    <div class="left_grid_info">
                        <img src="photos/splash.png" alt="Splash" class="background">
                        <img src="photos/juice.png" alt="Juice" class="foreground">
                        
                    </div>

                    </div>
                    <div class="content-wthree">
                        <h2>Register Now</h2>
                            <p>Sign up today to enjoy exclusive perks and make your time at Cafeteria even more delightful.</p>                        
                            <?php echo $msg; ?>
                        <form action="" method="post">
                            <input type="text" class="name" name="name" placeholder="Enter Your Name" value="<?php if (isset($_POST['submit'])) { echo $name; } ?>" required>
                            <input type="phone" class="phone" name="phone" placeholder="Enter Your Phone Number" value="<?php if (isset($_POST['submit'])) { echo $phone; } ?>" required>
                            <input type="email" class="email" name="email" placeholder="Enter Your Email" value="<?php if (isset($_POST['submit'])) { echo $email; } ?>" required>
                            <input type="password" class="password" name="password" placeholder="Enter Your Password" required>
                            <input type="password" class="confirm-password" name="confirm-password" placeholder="Enter Your Confirm Password" required>
                            <button name="submit" class="btn" type="submit">Register</button>
                        </form>
                        <div class="social-icons">
                            <p>
                                <a href="homePage.php">Home</a>
                                <span style="margin: 0 15px;">|</span> <!-- Adds a separator with space -->
                                <a href="login.php">Login</a>
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