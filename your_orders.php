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
        if (empty($_SESSION['SESSION_EMAIL'])) {
            header('location:login.php');
        } else {
            $u_id = $_SESSION['user_id'];
            $SQL = "SELECT * FROM users_orders WHERE u_id = ? ORDER BY orderNum DESC";
            $stmt = $conn->prepare($SQL);
            $stmt->bind_param('i', $u_id);
            $stmt->execute();
            $result = $stmt->get_result();
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
    
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/home.css" rel="stylesheet">
    <!-- Box Icons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    
<style>
    .header {
    position: relative;
    left: 250px;
    background-color: var(--panel-color);
    height: 100vh;
    width: calc(100% - 200px);
    padding: 0px 14px;
    font-size: 20px;
    transition: var(--tran-05);
}

.order{
    padding: 5px;
    font-size: 16px;
}
th, td{
    padding : 10px;
}
.card {
    
    margin-left: -14px;
    padding:120px 10px;
    margin-bottom: 0px;
    margin-right: -30px;
    background-color: var(--panel-color);
    transition: var(--tran-05);
}

.home {
    text-align: center;
    padding: 0px 100px;
    background-color: transparent;
    animation: fadeIn 2s ease-in-out;
    color: var(--hover-color);
}

.container{
    margin-right: 20px;
}

table{
    
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
            <section class="order">
          
            <div class="page-wrapper">

       <div class="inner-page-hero bg-image" data-image-src="images/img/pimg.jpg">
           <div class="container"> </div>
   
       </div>
       <div class="result-show">
           <div class="container">
               <div class="row">
                  
                  
               </div>
           </div>
       </div>

       <section class="restaurants-page"> <h2 style=" text-align: center; font-weight: 600px; color: #fbb65e; margin-bottom: 20px;"> Order History </h2>
           <div class="container">
               <div class="row">
                   <div class="col-xs-12">
                     </div>
                   <div class="col-xs-12">
                       <div class="bg-gray">
                           <div class="row">
                           
                       <table class="table table-bordered table-hover">
                     <thead style = "background: #404040; color:white;">
                       <tr>
                       
                         <th>Item</th>
                         <th>Quantity</th>
                         <th>Price</th>
                          <th>Status</th>
                            <th>Date</th>
                             <th>Expected Delivery Time</th>
                             <th>Restaurant</th>
                             <th>Payment Method</th>
                             <th>Delivery Method</th>
                         
                       </tr>
                     </thead>
                     <tbody class="font">
<?php 
$query_res = mysqli_query($conn, "SELECT * FROM users_orders WHERE u_id='" . $_SESSION['user_id'] . "' ORDER BY date DESC, o_id");
if (!mysqli_num_rows($query_res) > 0) {
echo '<tr><td colspan="9"><center>You have No orders Placed yet.</center></td></tr>';
} else {
$currentDate = '';       // Variable to track the current date

while ($row = mysqli_fetch_array($query_res)) {
   echo '<tr>';
   $rs_id = $row['rs_id'];
   $ress = mysqli_query($conn, "SELECT * FROM restaurant WHERE rs_id='$rs_id'");
   $restaurant_row = mysqli_fetch_array($ress);
   $restaurantName = $restaurant_row['title']; 

   // Check if the date is the same as the previous row
   if ($row['date'] != $currentDate) {
       // Add an empty row for the previous date if it’s not the first row
       if ($currentDate != '') {
           echo '<tr class="blank-row"><td colspan="9" style="padding: 0em; height: .75rem; background-color: #d3d3d3;">&nbsp;</td></tr>';

       }

       $currentDate = $row['date'];
       // Display all fields for the first occurrence of this date
       echo '<td data-column="Item">' . $row['title'] . '</td>';
       echo '<td data-column="Quantity">' . $row['quantity'] . '</td>';
       echo '<td data-column="price">$' . $row['price'] . '</td>';
       echo '<td data-column="status">' . $row['status'] . '</td>';
       echo '<td data-column="Date">' . $row['date'] . '</td>';
       echo '<td data-column="deliTime">' . $row['DeliTime'] . '</td>';
       echo '<td data-column="restaurant">' . $restaurantName . '</td>';
       echo '<td data-column="payment">' . $row['payment'] . '</td>';
       echo '<td data-column="deliOption">' . $row['deliOption'] . '</td>';
   } else {
       // For subsequent rows with the same date, leave the relevant columns empty
       echo '<td data-column="Item">' . $row['title'] . '</td>';
       echo '<td data-column="Quantity">' . $row['quantity'] . '</td>';
       echo '<td data-column="price">$' . $row['price'] . '</td>';
       echo '<td data-column="status">' . $row['status'] . '</td>';
       echo '<td data-column="Date"></td>';  // Empty date column
       echo '<td data-column="deliTime"></td>';  // Empty deliTime column
       echo '<td data-column="restaurant">' . $restaurantName . '</td>';
       echo '<td data-column="payment"></td>';  // Empty payment column
       echo '<td data-column="deliOption"></td>';  // Empty deliOption column
   }
   
   echo '</tr>';
}
}
?>					
</tbody>    
</table>
                    
                                </div>
                            </div>       
                       </div>                     
                   </div>
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
    <script src="js/jquery.min.js"></script>
    <script src="js/tether.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/animsition.min.js"></script>
    <script src="js/bootstrap-slider.min.js"></script>
    <script src="js/jquery.isotope.min.js"></script>
    <script src="js/headroom.js"></script>
    <script src="js/foodpicky.min.js"></script>
    <script src="js/sideBar2.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>