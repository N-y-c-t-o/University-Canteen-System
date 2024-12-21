<!DOCTYPE html>
<html lang="en">

<?php 
    session_start();
    include 'config.php';
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width-device-width, initial-scale=1.0">
    <link rel="icon" href="#">
    <title>UIT Cafeteria</title>
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/index2.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
    <style>
        .leaf {
        position: absolute;
        width: 30px; /* Adjust size as needed */
        height: 30px; /* Adjust size as needed */
        background-image: url('photos/leaf.png'); /* Ensure the path is correct */
        background-size: cover;
        z-index: 10; /* Make sure it's above other elements */
        animation: scatter 3s ease-in-out forwards;
        opacity: 0;
        }

        @keyframes scatter {
            0% {
                opacity: 1;
                transform: translate(0, 0) rotate(0deg);
            }
            100% {
                transform: translate(var(--x), var(--y)) rotate(var(--r));
                opacity: 0;
            }
        }
    </style>
    
</head>

<body>  
    <div class="container active" id="container">

        <div class="leaf" id="leaf1"></div>
        <div class="leaf" id="leaf2"></div>
        <div class="leaf" id="leaf3"></div>
        <div class="leaf" id="leaf4"></div>
        <div class="leaf" id="leaf5"></div>
        <div class="leaf" id="leaf6"></div>
        <div class="leaf" id="leaf7"></div>
        <div class="leaf" id="leaf8"></div>
        <div class="leaf" id="leaf9"></div>
        <div class="leaf" id="leaf10"></div>
        <div class="leaf" id="leaf11"></div>
        <div class="leaf" id="leaf12"></div>

        
        <header class="header">
            <a href="index.php" class="logo"> <img src="photos/logo1.png" alt="" width="18%"> </a>

            <nav class="navbar">
                <a href="homePage.php">Home</a>

                <a href="register.php">Register</a>

                <?php if (isset($_SESSION['SESSION_EMAIL'])): ?>
                    <a href="logout.php">Logout</span></a>
                <?php else: ?>
                    <a href="login.php">Login</span></a>
                <?php endif; ?>

                <a href="contact.php">Contact</a>
                <a href="about.php">About</a>
            </nav>

            <div class="toggle-icon">
                <i class='bx bx-moon'></i>
            </div>

        </header>

        <section class="home">
        <div class="home-content">
            <h3>Welcome To University Canteen!</h3>
                <h1>The Flavor Haven</h1>
            <h3>Ting <span>Tong</span></h3>
                <p>Your daily dose of deliciousness is here. <br> 
                    Relax, unwind, and savor the flavors at Cafeteria.</p>

                <a href="homePage.php" class="bth" data-aos="fade-up">Explore dishes</a>

        </div>

            <div class="home-img">
                <img src="photos/rice.png" alt="Rice" class="static">
            </div>
        </section>
        <a href="restaurant.php" class="floating-cta">Order Now</a>

    </div>

    <footer>
        <div class="foot">
        <div class="sec">
            <h2>About Us</h2><br>
            <p>Our mission is to enhance the dining experience for our customers by providing 
                a user-friendly platform that simplifies food ordering. We aim to connect food lovers 
                with their favorite restaurants while ensuring a seamless and efficient ordering process.</p>
                
                <ul  class="sci">
                    <li calss="facebook"><a href="#"><i class='bx bxl-facebook'></i></a></li>
                    <li class="instagram"><a href="#"><i class='bx bxl-instagram'></i></a></li>
                    <li class="tiktok"><a href="#"><i class='bx bxl-tiktok'></i></a></li>
                    <li class="viber"><a href="#"><i class="fa-brands fa-viber"></i></a></li>
                </ul>
        </div>

    <div class="quicklink">
        <h2>Members</h2><br>
        <ul>
            <li>Kyi Sin Shun Lett</li>
            <li>Myat Shwe Yi Moe</li>
            <li>Hnin Shwe Yi Wint</li>
            <li>Kyaw Min Wai</li>
            <li>Aung Khant Kyaw</li>
        </ul>
    </div>      
    <div class="aboutus">
        <h2>Authorities</h2><br>            
            <ul class="admin">
            
                <li>
                    <span><i class='bx bxs-log-in'></i></span>
                    <p><a href="admin/adminLogin.php" class="line">Admin Login</a></p>
                </li>
                <li>
                    <span><i class='bx bxs-log-in'></i></span>
                    <p><a href="restaurant/restLogin.php" class="line">Restaurant Login</a></p>
                </li>
                
            </ul>
    </div>
    <div class="contact">
        <h2>Contact Us</h2><br>
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
    </div>
    </footer>
        <div class = "copyrightText">
            <p>
                © Copyright 2024 University Cateen. All Rights Reserved.
            </p>
        </div>

        <script>
        window.onload = function() {
    let leaves = document.querySelectorAll('.leaf');
    leaves.forEach((leaf, index) => {
        setTimeout(() => {
            let x = Math.random() * window.innerWidth - 50; // Random X position
            let y = Math.random() * window.innerHeight - 50; // Random Y position
            let r = Math.random() * 360; // Random rotation

            leaf.style.setProperty('--x', `${x}px`);
            leaf.style.setProperty('--y', `${y}px`);
            leaf.style.setProperty('--r', `${r}deg`);

            leaf.style.opacity = '1';
            leaf.style.animationDelay = `${Math.random() * 2}s`; // Random delay for each leaf
        }, index * 100); // Adjust delay between leaves
    });
};

    </script>
    <script src="js/dark.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            delay: 400
        });
    </script>
</body>
</html>