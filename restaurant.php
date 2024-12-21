<!DOCTYPE html>
<html lang="en">

    <?php
        session_start();
        include 'config.php'; // Make sure to include your database connection setup

        $name = "Guest"; // Default value
        $phone = "Not Available"; // Default value
        $email = "Not Available"; // Default value
        $profile = "profile.jpg"; // Default profile picture

        if (isset($_SESSION['SESSION_EMAIL'])) {
            // Fetch the username and phone number from the database
            $email = $_SESSION['SESSION_EMAIL'];
            $query = mysqli_query($conn, "SELECT name, phone, email, profile FROM users WHERE email='$email'");
            $row = mysqli_fetch_assoc($query);

            if ($row) {
                $name = $row['name'];
                $phone = $row['phone'];
                $email = $row['email'];
                $profile = $row['profile'];
            }
        }
    ?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width-device-width, initial-scale=1.0">
    <link rel="icon" href="#">
    <title>UIT Cafeteria</title>
    <!-- <link href="css/animate.css" rel="stylesheet"> -->
    <!-- <link href="css/index.css" rel="stylesheet"> -->
    <link href="css/restaurant.css" rel="stylesheet">
    <link href="css/homePage.css" rel="stylesheet">
    

    <!-- <link href="restaurant/css/font-awesome.min.css" rel="stylesheet">
    <link href="restaurant/css/animsition.min.css" rel="stylesheet">
    <link href="restaurant/css/animate.css" rel="stylesheet">
    <link href="restaurant/css/style.css" rel="stylesheet">  -->
    <!-- Box Icons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body> 
    <nav class="close">
        <div class="logo-name">
            <div class="logo-image">
            <img src="photos/<?php echo $profile; ?>" alt="Profile Picture">
            </div>

            <span class="logo_name">Account</span>
        </div>

        <div class="menu-items">
            <ul class="nav-links">
                <li><a href="#" id="nameLink">
                <i class='bx bx-user'></i>
                    <span class="link-name"><?php echo $name; ?></span>
                </a> 
                </li>
                <li><a href="#"  id="emailLink">
                    <i class='bx bx-envelope'></i>
                    <span class="link-name"><?php echo $email; ?></span>
                </a>
                </li>
                <li><a href="#"  id="phoneLink">
                    <i class='bx bx-phone'></i>
                    <span class="link-name"><?php echo $phone; ?></span>
                </a>
                </li>
                <li><a href="editProfile.php">
                    <i class='bx bx-edit'></i>
                    <span class="link-name">Edit Profile</span>
                </a></li>
                
                <?php if (isset($_SESSION['SESSION_EMAIL'])): ?>
                    <li><a href="reset-password.php">
                        <i class='bx bx-lock-open'></i>
                        <span class="link-name">Reset Password</span>
                    </a></li>
                <?php else: ?>
                    <li><a href="forgot-password.php">
                        <i class='bx bx-lock'></i>
                        <span class="link-name">Forgot Password</span>
                    </a></li>
                <?php endif; ?>

                <li><a href="your_orders.php">
                    <i class='bx bx-history'></i>
                    <span class="link-name">History</span>
                </a></li>
            </ul>

            <ul class="logout-mode">
                <?php if (isset($_SESSION['SESSION_EMAIL'])): ?>
                    <li><a href="logout.php">
                        <i class='bx bx-log-out'></i>
                        <span class="link-name">Logout</span>
                    </a></li>
                <?php else: ?>
                    <li><a href="login.php">
                        <i class='bx bx-log-in'></i>
                        <span class="link-name">Log in</span>
                    </a></li>
                <?php endif; ?>

                <li class="mode">
                    <a href="#">
                    <i class='bx bx-moon'></i>
                    <span class="link-name">Dark Mode</span>
                </a>
            
                <div class="mode-toggle">
                    <span class="switch"></span>
                </div>
            </li>
            </ul>
        </div>
    </nav>

    <section class="header">
        <div class="top"> 
            <i class='bx bx-menu sidebar-toggle'></i>

            <div class="navbar">
                <a href="homePage.php">Home</a>
                <a href="restaurant.php">Restaurant</a>
                <a href="contact.php">Contact</a>
                <a href="about.php">About</a>
                
            <a href="index.php"><img src="photos/logo1.png" alt="" ></a>
            
            </div>
                    
        </div>

        <div class="page-wrapper">
        
        
        <section class="restaurants-page">
        <div class="header-container">
        <h1>Freely Browse Through!</h1>
        <div class="filter-bar">
            <form method="GET" action="restaurant.php">
                <input type="text" name="search" placeholder="..." class="search-bar" 
                       value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                <button type="submit" class="filter-button">Search</button>
            </form>
        </div>
    </div>
    <div class="restaurant-grid">

   
        <?php 
         $search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
         $query = "SELECT * FROM restaurant";
    if ($search) {
        $query .= " WHERE title LIKE '%$search%'";
    }

    $ress = mysqli_query($conn, $query);
    if (mysqli_num_rows($ress) > 0) {
        while ($rows = mysqli_fetch_array($ress)) {
             echo '<a href="dishes.php?rs_id=' . $rows['rs_id'] . '">
           <div class="restaurant-card">
                    <img src="admin/upload/' . $rows['image'] . '" alt="Food logo" class="restaurant-img">
                    <div class="restaurant-info">
                        <h2>' . $rows['title'] . '</h2>
                        
                        <p>' . $rows['description'] . '</p>
                        <p>' . $rows['phone'] . '</p>
                        <p>' . $rows['email'] . '</p>
                        
                    </div>
                </div>
       </a>';
        }
    } 
    
    ?>
    </div>
</section>

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
                            <span><i class='bx bxs-phone-call'></i></span>
                            <p><a href="tel:+1234567890">+1 123 456 7890</a></p>
                        </li>
                        <li>
                            <span><i class='bx bxs-envelope' ></i></span>
                            <p><a href="mailto:knowmore@uit.edu.mm">university@xyz.edu.mm</a></p>
                        </li>
                    <ul>
                </div>
            </div>
        </footer>
         
        <div class = "copyrightText">
            <p>
                © 2024 University Canteen. All Rights Reserved.
            </p>
        </div> 
    </section>

    <script src="js/home.js"></script>
    <script src="js/sideBar2.js"></script>
    <script>



</script>

</body>
</html>