<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="#">
    <title>UIT Cafeteria</title>
    <!-- <link href="css/animate.css" rel="stylesheet"> -->
    <link href="css/index2.css" rel="stylesheet">
    <link href="css/test.css" rel="stylesheet">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
    <style>
        
        /* Custom styles */

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
        .home {
            position: relative;
            overflow: hidden;
            height: 100vh;
        }
        .home-video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
            opacity: 0.8;
        }
        .home-content h1, .home-content p {
            animation: fadeInUp 1s ease-in-out;
        }
        .home-content .bth {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #ff5733;
            color: #fff;
            border-radius: 5px;
            transition: transform 0.3s ease;
        }
        .home-content .bth:hover {
            transform: scale(1.1);
        }

        .cafeteria-highlights {
        padding: 50px 0;
        background: #f8f9fa;
        text-align: center;
        }

        .cafeteria-highlights h2 {
            margin-bottom: 20px;
            font-size: 2rem;
            color: #333;
        }

        .highlights-grid {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .highlight {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 300px;
            text-align: center;
        }

        .highlight img {
            width: 100%;
            height: auto;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .highlight h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: #ff5733;
        }

        .highlight p {
            color: #666;
        }


        .floating-cta {
            position: fixed;
            bottom: 50px;
            right: 80px;
            background-color: #ff5733;
            color: #fff;
            padding: 15px 20px;
            border-radius: 50px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            text-decoration: none;
            z-index: 1000;
            transition: transform 0.3s ease;
        }
        .floating-cta:hover {
            transform: scale(1.1);
        }
        .foot {
            bottom: 30px;
        }
        .copyrightText{
            width: 100%;
            background: #212222;
            padding: 20px 100px 30px;
            text-align: center;
            color: #fcc280;
            border-top: 1px solid #fcc280;
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
                <a href="contact.php">Contact</a>
                <a href="about.php">About</a>
            </nav>

            <div class="toggle-icon">
                <i class='bx bx-moon'></i>
            </div>
        </header>

        <section class="home">
            <div class="home-content">
                <h3 data-aos="fade-up">Welcome To The University Cateen!</h3>
                <h1 data-aos="fade-up">Cafeteria</h1>
                <h3 data-aos="fade-up">Ting <span>Tong</span></h3>
                <p data-aos="fade-up">Your daily dose of deliciousness is here.<br> 
                    Relax, unwind, and savor the flavors at Cafeteria.</p>
                <a href="homePage.php" class="bth" data-aos="fade-up">Explore dishes</a>
            </div>

            <div class="home-img">
                <img src="photos/rice.png" alt="Rice" class="static">
                <div class="steam steam1"></div>
                <div class="steam steam2"></div>
                <div class="steam steam3"></div>
            </div>
            
        </section>

        <!-- Upcoming Events Section -->
        <section class="cafeteria-highlights" data-aos="fade-up">
            <h2>Why Choose Our Cafeteria?</h2>
            <div class="highlights-grid">
                <div class="highlight">
                    <img src="photos/juice.png" alt="Ambiance">
                    <h3>Cozy Ambiance</h3>
                    <p>Enjoy your meals in a comfortable and welcoming atmosphere.</p>
                </div>
                <div class="highlight">
                    <img src="photos/tealeaf.png" alt="Sustainability">
                    <h3>Sustainable Choices</h3>
                    <p>We use eco-friendly practices and source local ingredients.</p>
                </div>
                <div class="highlight">
                    <img src="photos/ice.png" alt="Events">
                    <h3>Special Events</h3>
                    <p>Join us for themed nights, live music, and more exciting events.</p>
                </div>
            </div>
        </section>

        <!-- Floating Call to Action Button -->
        <a href="orderPage.php" class="floating-cta">Order Now</a>
    </div>

    <footer>
        <div class="foot">
            <div class="sec">
                <h2>About Us</h2><br>
                <p>Our mission is to enhance the dining experience for our customers by providing 
                    a user-friendly platform that simplifies food ordering. We aim to connect food lovers 
                    with their favorite restaurants while ensuring a seamless and efficient ordering process.</p>
                
                <ul class="sci">
                    <li class="facebook"><a href="#"><i class='bx bxl-facebook'></i></a></li>
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
                <h2>Admin</h2><br>            
                <ul class="admin">
                    <li>
                        <span><i class='bx bxs-phone-call'></i></span>
                        <p><a href="tel:+1234567890">+1 987 654 3210</a></p>
                    </li>
                    <li>
                        <span><i class='bx bxs-envelope' ></i></span>
                        <p><a href="mailto:admin@uit.edu.mm">admin@xyz.edu.mm</a></p>
                    </li>
                    <li>
                        <span><i class='bx bxs-log-in'></i></span>
                        <p><a href="#" class="line">Admin & Owner Login</a></p>
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
                        <p><a href="tel:+1234567890">+1 234 567 890</a></p>
                    </li>
                    <li>
                        <span><i class='bx bxs-envelope' ></i></span>
                        <p><a href="mailto:info@xyz.edu.mm">info@xyz.edu.mm</a></p>
                    </li>
                </ul>
            </div>
        </div>
        <div class="copyrightText">
            <p>Copyright © 2024 UIT Canteen. All rights reserved.</p>
        </div>
    </footer>

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>

        AOS.init({
            duration: 1000,
            delay: 400
        });
    </script>
</body>
</html>
