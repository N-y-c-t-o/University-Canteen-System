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
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $contact_email = mysqli_real_escape_string($conn, $_POST['email']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    if (!filter_var($contact_email, FILTER_VALIDATE_EMAIL)) {
        $msg = "<div class='alert alert-danger'>Invalid email address.</div>";
    } else {
        // Send email using PHP's mail function
        $to = "hninshweyiwint2022@gmail.com"; // Replace with your email
        $headers = "From: " . $contact_email;
        $full_message = "Name: $name\nEmail: $contact_email\nSubject: $subject\n\nMessage:\n$message";

        if (mail($to, $subject, $full_message, $headers)) {
            $msg = "<div class='alert alert-success'>Your message has been sent successfully.</div>";
        } else {
            $msg = "<div class='alert alert-danger'>Failed to send your message. Please try again later.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Contact Us - Your Website</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8" />
    <meta name="keywords" content="Contact Form" />
    <link href="//fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/bounce.css" type="text/css" media="all" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

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
                        <!-- <div class="contact-info">
                            <img src="photos/ramen.png" alt="Ramen" class="ramen">
                        </div> -->
                                              
                        <div class="info-content">
                            <h2>Let's get in touch!</h2> 
                            <p> <br> We would love to hear from you! Please fill out the form below with any questions or feedback you may have.</p> <br>
                    
                            <div class="contact">
                                <ul class="info">
                                    <li>
                                        <span><i class='bx bxs-map'></i></span>
                                        <p><a href="#">123 Main Street, City, Country</a></p>
                                    </li>
                                    <li>
                                        <span><i class='bx bxs-phone-call'></i></span>
                                        <p><a href="tel:+1234567890">+1 123 456 7890</a></p>
                                    </li>
                                    <li>
                                        <span><i class='bx bxs-envelope' ></i></span>
                                        <p><a href="mailto:knowmore@uit.edu.mm">knowmore@uit.edu.mm</a></p>
                                    </li>
                                </ul>
                            </div>

                            <div class="sec">
                                <ul  class="sci">
                                <li calss="facebook"><a href="#"><i class='bx bxl-facebook'></i></a></li>
                                <li class="instagram"><a href="#"><i class='bx bxl-instagram'></i></a></li>
                                <li class="tiktok"><a href="#"><i class='bx bxl-tiktok'></i></a></li>
                                <li class="viber"><a href="#"><i class="fa-brands fa-viber"></i></a></li>
                                </ul>
                            </div>

                        </div>
                    </div> 
        
                    <div class="content-wthree">
                        <h2>Contact Us</h2>
                        <?php if (isset($msg)) { echo "<p>$msg</p>"; } ?>
                        <form action="" method="post">
                            <input type="text" name="name" placeholder="Enter Your Name" required>
                            <input type="email" name="email" placeholder="Enter Your Email" required>
                            <input type="text" name="subject" placeholder="Enter Subject" required>
                            <textarea name="message" placeholder="Enter Your Message" rows="5" cols="37" required></textarea>
                            <button name="submit" class="btn" type="submit">Send Message</button>
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
