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
    <link href="css/homePage.css" rel="stylesheet">
    <link href="css/home.css" rel="stylesheet">
    <!-- Box Icons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* Enhancements for promotions section */
        .promotions {
            background: linear-gradient(to right, #FFC0B3, #FFF8F5);
            border-radius: 20px;
            padding: 40px;
            
            width: 90%;
            margin-left:2%;
            height: 200px;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease-in-out;
        }
        .recommended h2{
            color: #fbb65e;
        }
        .promotions:hover {
            transform: scale(1.02);
        }
        .promotions .promo-text {
            z-index: 2;
            max-width: 50%;
        }
        .promotions .promo-text h2 {
            font-size: 42px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
            transition: color 0.3s ease;
        }
        .promotions .promo-text p {
            font-size: 20px;
            color: #555;
            margin-bottom: 20px;
        }
        .promotions .promo-buttons .btn {
            font-size: 18px;
            padding: 10px 20px;
            background: #FF6F61;
            color: #fff;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .promotions .promo-buttons .btn:hover {
            background: #FF3B2F;
        }
        .promotions .promo-image {
            position: absolute;
            top: 2px;
            right: 2px;
            width: 200px;
            height: 200px;
            background: url('photos/bibimbap.png') no-repeat center;
            background-size: cover;
            border-radius: 50%;
            z-index: 1;
            animation: rotateImage 40s linear infinite;
        }
        @keyframes rotateImage {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .circle-decor {
            position: absolute;
            bottom: 20px;
            right: 20px;
            width: 250px;
            height: 250px;
            background-color: #FFB6B9;
            border-radius: 50%;
            opacity: 0.3;
        }
        .recommended h2{
            font-size:40px;
        }
        /* Enhancements for recommended dishes section */
        .recommended {
            padding: 10px 0;
            text-align: center;
        }
        
        .recommended-items {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 20px;
        }
        .item-card {
            background-color: #FFFFFF;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            flex: 1;
            max-width: 250px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .item-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
        }
        .item-card img {
            width: 100px;
            height: 100px;
            border-radius: 10%;
            margin-bottom: 15px;
            transition: transform 0.3s ease;
        }
        .item-card img:hover {
            transform: scale(1.1);
        }
        .item-card h3 {
            font-size: 20px;
            margin-bottom: 10px;
            color: #333;
        }
        .item-card p {
            font-size: 18px;
            color: #888;
        }
        /* Button style for item cards */
.btn-order {
    display: inline-block;
    padding: 10px 20px;
    font-size: 16px;
    color: #fff;
    background-color: #FF6F61;
    border: none;
    border-radius: 25px;
    text-decoration: none;
    text-align: center;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.btn-order:hover {
    background-color: #FF3B2F;
    transform: scale(1.05);
}

        .home {
    text-align: center;
    padding: 0px 60px;
    background-color: transparent;
    animation: fadeIn 2s ease-in-out;
    color: var(--hover-color);
}
    </style>

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
                    <span class="link-name">Mode</span>
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
           
        <div class="card"> 
            <section class="home">

                <div class="col-md-10 main-content">
                    
                    
                    <div class="promotions">
                        <div class="promo-text">
                            <?php
                            // Include database connection
                            include 'config.php';

                            // Convert UTC to Myanmar Time (MMT)
                            function convertToMMT($datetime) {
                                $datetimeMMT = new DateTime($datetime, new DateTimeZone('UTC'));
                                $datetimeMMT->setTimezone(new DateTimeZone('Asia/Yangon'));
                                return $datetimeMMT->format('Y-m-d H:i:s');
                            }

                            // Get current time in UTC
                            $current_time = (new DateTime('now', new DateTimeZone('UTC')))->format('Y-m-d H:i:s');

                            // Prepare and execute the SQL query
                            $sql = "SELECT * FROM noticeboard WHERE startID <= ? AND endID >= ?";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("ss", $current_time, $current_time);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $title = htmlspecialchars($row['title']);
                                    $content = htmlspecialchars($row['content']);
                                    $startID = convertToMMT($row['startID']);
                                    $endID = convertToMMT($row['endID']);
                                    
                                    echo "<h2>$title</h2>";
                                    echo "<p style=' word-wrap: break-word;'>$content</p>";
                                    
                                }
                            } else {
                                echo "<div class='alert alert-warning text-center mt-5'>What would you like to  today <br><br></div>";
                            }

                            $stmt->close();
                            
                            ?>
                            <div class="promo-buttons">
                                <button id="order-now-button" class="btn btn-outline-danger">Order Now</button> 
                            </div>
                        </div>
                        <div class="promo-image"></div>
                        <div class="circle-decor"></div>
                    </div>
                    
                    <div class="recommended">
    <h2>Popular Dishes<br></h2>
    <div class="recommended-items">
        <?php 
        // Fetch top 4 popular dishes
        $popular_dishes_sql = "
            SELECT 
                title, 
                rs_id,
                SUM(quantity) AS total_quantity
            FROM 
                users_orders
            GROUP BY 
                title, rs_id
            ORDER BY 
                total_quantity DESC
            LIMIT 4";
        $popular_dishes_result = $conn->query($popular_dishes_sql);

        if ($popular_dishes_result->num_rows > 0) {
            while ($row = $popular_dishes_result->fetch_assoc()) {
                // Fetch more details for each popular dish
                $more_sql = "
                    SELECT 
                        title AS item_title,
                        price AS item_price,
                        img AS item_img
                    FROM 
                        menu_rest{$row['rs_id']} 
                    WHERE 
                        title = '{$row['title']}'
                    LIMIT 1";  // Ensure only one result per popular dish
                $more_result = $conn->query($more_sql);

                if ($more_result->num_rows > 0) {
                    while ($more_row = $more_result->fetch_assoc()) {
                        $popular_dish_title = htmlspecialchars($more_row['item_title']);
                        $popular_dish_price = htmlspecialchars($more_row['item_price']);
                        $popular_dish_img = htmlspecialchars($more_row['item_img']);
                        ?>
                        <div class="item-card">
                            <img src="restaurant/upload/<?php echo $popular_dish_img; ?>" alt="<?php echo $popular_dish_title; ?>">
                            <h3><?php echo $popular_dish_title; ?></h3>
                            <p><?php echo $popular_dish_price; ?></p>
                            <!-- Add the order button -->
                            <a href="dishes.php?rs_id=<?php echo $row['rs_id']; ?>" class="btn-order">Order Now</a>
                        </div>
                        <br><br>
                        <?php
                    }
                }
            }
        } else { 
            ?>
            <p>No popular dishes are available at the moment. Please check back later.</p><br><br>
            <?php  
        }
        $conn->close();
        ?>
    </div>
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
    <script>
        document.getElementById('order-now-button').addEventListener('click', function() {
            window.location.href = 'restaurant.php';
        });
    </script>
    <script src="js/home.js"></script>
    <script src="js/sideBar2.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>