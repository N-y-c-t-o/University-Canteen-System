<!DOCTYPE html>
<html lang="en">

<?php
include 'config.php';
error_reporting(0);
session_start();
include_once 'product-action.php'; 


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
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="#">
    <title>UIT Cafeteria</title>
    <!-- <link href="css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <!-- <link href="css/style.css" rel="stylesheet"> -->
    <!-- <link href="css/noside.css" rel="stylesheet"> -->
    <link href="css/homePage.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>

    :root{
    --msym-color: #ff6f61;
    --msym-button-color:#ffcd69;
    --msym-hover-color: #fec554;
    }
        .content-wrapper {
            padding: 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            background: linear-gradient(135deg, #f8f0ff, #ffe8f3);
            border-radius: 20px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            margin: 2rem auto;
            max-width: 1200px;
        }
        .row {
    display: flex;
    flex-wrap: nowrap;
}
        /* Container for restaurant info and logo */
.restaurant-info-container {
    display: flex;
    justify-content: space-between; /* Space between logo and details */
    align-items: center; /* Vertically center content */
    padding-top: 100px;
    
    background-color: #f9f9f9;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
}

/* Restaurant details (left) */
.restaurant-details {
    flex: 1; /* Take up more space */
    color: #333;
    padding: 30px;
}

.restaurant-title {
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 10px;
    color: #fbbc5e;
}

.restaurant-description {
    font-size: 1.1rem;
    line-height: 1.5;
}

/* Restaurant logo (right) */
.restaurant-logo {
    max-width: 200px; /* Limit logo size */
}

.restaurant-logo img {
    width: 100%; /* Ensure the logo fits in the container */
    border-radius: 10px; /* Rounded edges for logo */
}

/* Responsive Design: Stack content vertically on smaller screens */
@media (max-width: 768px) {
    .restaurant-info-container {
        flex-direction: column;
        text-align: center;
    }

    .restaurant-logo {
        margin-top: 20px;
    }
}



        .profile-img img {
            border-radius: 50%;
            border: 5px solid #ffc0cb;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }

        .profile-img img:hover {
            transform: scale(1.1);
        }
        
        .profile-desc {
            background: rgba(255, 255, 255, 0.9);
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
            font-family: 'Arial', sans-serif;
            color: #333;
        }

        .profile-desc h6 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #ff69b4;
        }

        .profile-desc p {
            color: #777;
        }

        .cart-widget {
            background-color: #fff5fa;
            padding: 1.5rem;
            border-radius: 20px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
            font-family: 'Arial', sans-serif;
        }

        .cart-widget h3 {
            font-size: 1.8rem;
            color: #ff69b4;
        }

        .category-btn {
            background-color: #ff7b6e;
            border: none;
            color: white;
            padding: 0.8rem 1.5rem;
            font-size: 1.2rem;
            border-radius: 10px;
            margin: 0.5rem;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s;
        }

        .category-btn:hover {
            background-color: #fbbc5e;
        }

        .menu-widget {
            width: 800px;
            background-color: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        .menu-widget h3 {
            color: #ff69b4;
            font-size: 2rem;
        }

        .food-item {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .food-item h6 a {
            color: #ff7b6e;
            font-weight: bold;
            text-decoration: none;
            font-style: italic;
        }

        .food-item .price {
            color: #333;
            font-weight: 600;
        }

        .btn.theme-btn {
            background-color: #ff7b6e;
            color: white;
            border: none;
            margin-top: 10px;
            margin-right: 50px;
            padding: 0.6rem 1.5rem;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s ease;
        }

        .btn.theme-btn:hover {
            background-color: #fbbc5e;
        }
        .container {
            max-width: 1270px;
            width: 90%;
            margin-left: 30px;
        }
/* Sidebar Cart */
.sidebar-cart {
    background: #f8f9fa;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 15px;
}

.sidebar-cart .widget-heading {
    margin-bottom: 20px;
}

.sidebar-cart .widget-body {
    padding: 10px;
}

.sidebar-cart .title-row {
    font-weight: bold;
    margin-bottom: 10px;
}

.sidebar-cart .price-wrap {
    border-top: 1px solid #ddd;
    padding-top: 10px;
    margin-top: 10px;
}

/* Responsive Design */
@media (max-width: 767px) {
    .row {
        flex-direction: column;
    }
    .col-md-8, .col-md-4 {
        max-width: 100%;
    }
}
.widget-cart {
        background: #fff; /* Soft pink background */
        border-radius: 15px;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        padding: 20px;
        font-family: 'Arial', sans-serif;
        border: 1px solid #f1c6d0; /* Light pink border */
    }

    .widget-heading {
        border-bottom: 2px solid #f8e1e7;
        margin-bottom: 15px;
        padding-bottom: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: #d9534f; /* Soft red color */
    }

    .widget-title {
        font-size: 1.6rem;
        font-weight: bold;
        color: #fbbc5e; 
        text-align: center;
    }

    .order-row {
        /* border-bottom: 1px dashed #f1c6d0; */
        padding: 10px 0;
        margin-bottom: 10px;
    }

    .title-row {
        font-size: 1rem;
        color: #ff7b6e; /* Deep purple color */
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        position: relative;
    }

    .title-row a {
        color: #333;
        font-size: 1.3rem;
        transition: color 0.3s ease;
    }

    .title-row a:hover {
        color: #c82333;
    }

    .form-group {
        margin-bottom: 10px;
    }

    .form-control {
        border: 1px solid #f1c6d0;
        border-radius: 8px;
        padding: 10px;
        max-width: 70px;
        background-color: #fff; /* White background for inputs */
    }
   
    .price-wrap {
        margin-top: 15px;
        padding-top: 10px;
        border-top: 2px solid #f8e1e7;
        text-align: center;
    }

    .price-wrap p {
    font-size: 1.2rem;
    color: #ff6f61;
    }

    .price-wrap .value {
        font-size: 2rem;
        color: #fbbc5e; /* Bright pink */
        font-weight: bold;
        padding: 20px;
    }

    .btn {
    border-radius: 10px;
    font-size: 1rem;
    padding: 10px 10px;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

    .btn-lg {
        width: 50%;
        padding: 15px 25px;
        font-size: 1.3rem;
    }

    .btn-danger {
        /* background-color: #ff6f61; */
        border-color: #ff6f61;
    }

    .btn-danger.disabled {
        /* background-color: #ff6f61; */
        color: #ff6f61;
        text-decoration:none;
    }

        .btn-danger.disabled:hover {
        /* background-color: #fbbc5e; */
        color: #fbbc5e;
        text-decoration: none;
    }

    .btn-success {
        /* background-color: #ff6f61; */
        color: #ff6f61; 
        text-decoration:none;
    }

    .btn-success.active {
        /* background-color: #fbb65e; */
        color: #fbb65e;
        text-decoration:none;
    }
    .col-md-8{
        margin-left: 5%;
    }
    .value{
        padding: 10px;
    }
    .alert-danger {
        margin-top: 15px;
        padding: 10px;
        border-radius: 8px;
        background-color: #f8d7da;
        color: #721c24;
        font-size: 1rem;
    }
.card{
    padding: 30px 10px;
}
.header {
    position: relative;
    left: 250px;
    background-color: var(--panel-color);
    height: 100%;
    width: calc(100% - 200px);
    padding: 0px 14px;
    font-size: 20px;
    transition: var(--tran-05);
}
.col-md-4{
    max-width: 20%;
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

                <a href="index.php"><img src="photos/logo1.png" alt=""></a>

            </div>

        </div>
        
       <div class="card">
    <?php 
    $ress = mysqli_query($conn, "select * from restaurant where rs_id='$_GET[rs_id]'");
    $rows = mysqli_fetch_array($ress);
    ?>
    <div class="restaurant-banner">

        <?php
        $ress = mysqli_query($conn, "select * from restaurant where rs_id='$_GET[rs_id]'");
        $rows = mysqli_fetch_array($ress);
        ?>

<!-- Banner Section -->
<section class="restaurant-info-banner">
    <div class="restaurant-info-container">
        <!-- Restaurant Info on the Left -->
        <div class="restaurant-details">
            <h2 class="restaurant-title"><?php echo $rows['title']; ?></h2>
            <p class="restaurant-description">
                <?php echo $rows['description']; ?>
                <br>
                <strong>Email:</strong> <?php echo $rows['email']; ?>
            </p>
        </div>
        <!-- Restaurant Logo on the Right -->
        <div class="restaurant-logo">
            <img src="admin/upload/<?php echo $rows['image']; ?>" alt="Restaurant logo" class="img-fluid">
        </div>
    </div>
</section>
</div>

   
    <div class="container m-t-30">
        <div class="row">
            <!-- Cart Section -->
            <div class="col-md-4">
                <div class="widget widget-cart">
                    <div class="widget-heading">
                        <h3 class="widget-title text-dark">Your Cart</h3>
                        <div class="clearfix"></div>
                    </div>
                    <div class="order-row bg-white">
                        <div class="widget-body">
                            <?php
                            $item_total = 0;
                            foreach ($_SESSION["cart_item"] as $item) {
                            ?>  
                            <div class="title-row">
                                <?php echo $item["title"]; ?>
                                <a href="dishes.php?rs_id=<?php echo $_GET['rs_id']; ?>&action=remove&id=<?php echo $item["d_id"]; ?>">
                                    <i class="fa fa-trash pull-right"></i>
                                </a>
                            </div>
                            <div class="form-group row no-gutter">
                                <div class="col-xs-8" style="padding-right: 10px;">
                                    <input type="text" class="form-control b-r-0" value="<?php echo $item["price"]."ks"; ?>" readonly>
                                </div>
                                <div class="col-xs-4" style="padding-left: 10px;">
                                    <input class="form-control" type="text" readonly value='<?php echo $item["quantity"]; ?>'>
                                </div>
                            </div>
                            <?php 
                            $item_total += ($item["price"]*$item["quantity"]); 
                            }
                            ?>
                        </div>
                    </div>
                    <div class="widget-body">
                        <div class="price-wrap text-xs-center">
                            <p style="font-weight: 500;">TOTAL</p>
                            <h3 class="value"><strong><?php echo  $item_total."ks"; ?></strong></h3>
                            <?php
                            if ($item_total == 0) {
                                echo '<a href="checkout.php" class="btn btn-danger btn-lg disabled"><h3>Checkout</h3></a>';
                            } else {
                                $checkout_link = count($_SESSION["cart_categories"]) > 1 ? '#' : "checkout.php?rs_id=" . $_GET['rs_id'] . "&action=check";
                                $checkout_class = count($_SESSION["cart_categories"]) > 1 ? 'btn-danger disabled' : 'btn-success active';
                                echo '<a href="' . $checkout_link . '" class="btn ' . $checkout_class . ' btn-lg"><p><strong>Checkout<strong></p></a>';
                            }
                            if (isset($_GET['error']) && $_GET['error'] === 'multiple_categories') {
                                echo '<div class="alert alert-danger">You can only select dishes from one category. Please adjust your cart.</div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Menu and Category Section -->
            <div class="col-md-8">
                <div class="alert alert-info text-center">
                    <p style="color: #de4233;"><strong>Note:</strong> Please be mindful to order from only one category at a time.</p><br>
                </div>

                <!-- Category Buttons -->
                <div class="category-buttons">
                    <button class="btn btn-primary category-btn" data-category="Breakfast">Breakfast</button>
                    <button class="btn btn-primary category-btn" data-category="Lunch">Lunch</button>
                    <button class="btn btn-primary category-btn" data-category="PostNoon">PostNoon</button>
                </div><br>

                <!-- Menu Widget -->
                <div class="menu-widget">
                    <div class="widget-heading">
                        <h3 class="widget-title text-dark" style="color: #fbb65e">MENU</h3>
                        <div class="clearfix"></div>
                    </div>
                    <?php
                    $time_categories = ['Breakfast', 'Lunch', 'PostNoon'];
                    $restaurant_table = "menu_rest" . $_GET['rs_id'];
                    foreach ($time_categories as $category) {
                        $stmt = $conn->prepare("SELECT * FROM $restaurant_table WHERE time_category=?");
                        $stmt->bind_param('s', $category);
                        $stmt->execute();
                        $products = $stmt->get_result();
                        echo "<div class='category-menu' data-category='$category' style='display: none;'>";
                        if ($products->num_rows > 0) {
                            foreach ($products as $product) {
                                $stock = $product['stock'];
                                $available = ($category === 'Breakfast' || $category === 'PostNoon' || $category === 'Lunch');
                                ?>
                                <div class="food-item">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-lg-8">
                                            <form method="post" action='dishes.php?rs_id=<?php echo $_GET['rs_id']; ?>&action=add&id=<?php echo $product['d_id']; ?>'>
                                                <div class="rest-logo pull-left">
                                                    <a class="restaurant-logo pull-left" href="#">
                                                        <?php echo '<img src="restaurant/upload/' . $product['img'] . '" alt="Food logo">'; ?>
                                                    </a>
                                                </div>
                                                <div class="rest-descr">
                                                    <h6><a href="#"><?php echo $product['title']; ?></a></h6>
                                                    <p style="text-align: justify; margin-right:20px; font-size:16px;"><?php echo $product['slogan']; ?></p>
                                                </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-lg-3 pull-right item-cart-info">
                                            <span class="price pull-left"><?php echo $product['price']; ?>ks</span>
                                            <input class="b-r-0" type="text" name="quantity" style="margin-left:30px;" value="1" size="2" />
                                            <input type="submit" class="btn theme-btn<?php echo $available ? '' : ' btn-disabled'; ?>" style="margin-left:40px;" value="Add To Cart" <?php echo $available ? '' : 'disabled'; ?> />
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        echo "</div>";
                    }
                    ?>

                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            const categoryButtons = document.querySelectorAll('.category-btn');
                            const categoryMenus = document.querySelectorAll('.category-menu');
                            categoryButtons.forEach(button => {
                                button.addEventListener('click', () => {
                                    const category = button.getAttribute('data-category');
                                    categoryMenus.forEach(menu => {
                                        if (menu.getAttribute('data-category') === category) {
                                            menu.style.display = 'block';
                                        } else {
                                            menu.style.display = 'none';
                                        }
                                    });
                                });
                            });
                            if (categoryButtons.length > 0) {
                                categoryButtons[0].click();
                            }
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>




    <!-- </section> -->
    </div>

<footer>
        <div class="foot">
            <div class="sec">
                <h2>About Us</h2><br>
                <p>Our mission is to enhance the dining experience for our customers by providing
                    a user-friendly platform that simplifies food ordering. We aim to connect food lovers
                    with their favorite restaurants while ensuring a seamless and efficient ordering process.</p>

                <ul class="sci">
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
                        <span><i class='bx bxs-envelope'></i></span>
                        <p><a href="mailto:knowmore@uit.edu.mm">university@xyz.edu.mm</a></p>
                    </li>
                </ul>
            </div>
        </div>
    </footer>
    <div class="copyrightText">
        <p>
            © 2024 University Canteen. All Rights Reserved.
        </p>
    </div>


    </section>

   
    <script src="js/sideBar2.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/tether.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/animsition.min.js"></script>
    <script src="js/bootstrap-slider.min.js"></script>
    <script src="js/jquery.isotope.min.js"></script>
    <script src="js/headroom.js"></script>
    <script src="js/foodpicky.min.js"></script>

</body>

</html>