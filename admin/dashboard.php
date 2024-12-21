<!DOCTYPE html>
<html lang="en">
<?php
session_start();

require 'C:/xampp/htdocs/UIT/config.php';
 
if (empty($_SESSION['ADMIN_SESSION_EMAIL'])) {
    header('location: adminLogin.php');
    exit();
}
?>
<head>
<link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Admin Dashboard</title>
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/dashboard.css" rel="stylesheet">
    <link href="css/yay.css" rel="stylesheet">
   
</head>
<body class="fix-header">
<div id="main-wrapper">

<div class="header">
     <nav class="navbar top-navbar navbar-expand-md navbar-light">
        <div class="navbar-header">
            <a class="navbar-brand" href="dashboard.php">
             <span><img src="/UIT/photos/logo.png" alt="homepage" class="dark-logo" /></span>
            </a>
        </div>
         <div class="navbar-collapse">
          
             <ul class="navbar-nav mr-auto mt-md-0">
             </ul>
        
             <ul class="navbar-nav my-lg-0">
                 <li class="nav-item dropdown">
                     <div class="dropdown-menu dropdown-menu-right mailbox animated zoomIn">
                         <ul>
                             <li>
                                 <div class="drop-title">Notifications</div>
                             </li>
                             <li>
                                 <a class="nav-link text-center" href="javascript:void(0);"> <strong>Check all notifications</strong> <i class="fa fa-angle-right"></i> </a>
                             </li>
                         </ul>
                     </div>
                 </li>
                 <li class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle text-muted  " href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="images/bookingSystem/user-icn.png" alt="user" class="profile-pic" /></a>
                     <div class="dropdown-menu dropdown-menu-right animated zoomIn">
                         <ul class="dropdown-user">
                            <li><a href="logout.php"><i class="fa fa-power-off"></i> Logout</a></li>
                         </ul>
                     </div>
                 </li>
             </ul>
         </div>
     </nav>
 </div>

 <div class="left-sidebar">
     <div class="scroll-sidebar">
         <nav class="sidebar-nav">
            <ul id="sidebarnav">
                 <li class="nav-devider"></li>
                 <li class="nav-label">Home</li>
                 <li> <a href="dashboard.php"><i class="fa fa-tachometer"></i><span>Dashboard</span></a></li>
                 <li class="nav-label">Log</li>
                 <li> <a href="all_users.php">  <span><i class="fa fa-user f-s-20 "></i></span><span>Users</span></a></li>
                 
                 <li>
                    <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fa fa-archive f-s-20 color-warning"></i>
                        <span class="hide-menu">Restaurant</span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="all_restaurant.php">All Restaurants</a></li>
                        <li><a href="addrestaurant.php">Add Restaurant</a></li>
                        <li><a href="editrestaurant.php">Edit Restaurant</a></li>
                    </ul>
                </li>

                <li>
                    <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fa fa-sticky-note f-s-20 color-warning"></i>
                        <span class="hide-menu">Noticeboard</span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="postNotice.php">Post Noticeboard</a></li>
                        <li><a href="editNotice.php">Edit Noticeboards</a></li>
                        <li><a href="pastNotice.php">Past Noticeboards</a></li>
                    </ul>
                </li>

                 <li> <a href="Cancelled_Orders.php"><i class="fa fa-shopping-cart" aria-hidden="true"></i><span>Cancelled Orders</span></a></li>
             </ul>
         </nav>
     </div>
 </div>      

           <!-- Main Content -->
        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="row card-container">
                    <div class="banner">
                        <div class="banner-content">
                        <h1>Welcome, Admin!</h1>
                        <p>Manage Orders, Track Deliveries, and Analyze Data</p>
                        </div>
                    </div>

                    <div class="col-md-3 ">
                        <div class="card pp-30">
                        <img src="/UIT/photos/restaurant.jpg" alt="homepage" class="board" />

                            <div class="card-header">
                                <h4 class="m-b-0 text-dark">Restaurant Management</h4>
                            </div>
                            <div class="card-body">
                                <a href="all_restaurant.php" class="btn ">All Restaurants</a>
                                <a href="addrestaurant.php" class="btn ">Add Restaurant</a>
                                <a href="editrestaurant.php" class="btn ">Edit Restaurant</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row card-container">

                    <div class="col-md-3 ">
                        <div class="card pp-30">
                        <img src="/UIT/photos/order.jpg" alt="homepage" class="board" />

                            <div class="card-header">
                                <h4 class="m-b-0 text-dark">Orders Management</h4>
                            </div>
                            <div class="card-body">
                                
                                <a href="Cancelled_orders.php" class="btn">View Cancelled Orders</a>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="row card-container">

                    <div class="col-md-3">
                        <div class="card pp-30">
                        <img src="/UIT/photos/notice.jpg" alt="homepage" class="board" />

                            <div class="card-header">
                                <h4 class="m-b-0 text-dark">Noticeboard</h4>
                            </div>
                            <div class="card-body">
                            <a href="postNotice.php" class="btn">Post Noticeboard</a>
                            <a href="editNotice.php" class="btn">Edit Noticeboard</a>
                            <a href="pastNotice.php" class="btn">Past Noticeboard</a>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="row card-container">

                    <div class="col-md-3 ">
                        <div class="card pp-30">
                        <img src="/UIT/photos/user.jpg" alt="homepage" class="board" />

                            <div class="card-header">
                                <h4 class="m-b-0 text-dark">User Info</h4>
                            </div>
                            <div class="card-body">
                                <a href="all_users.php" class="btn">View User Information</a>
                                <!-- <a href="profile.php" class="btn btn-primary btn-block">View User Information</a> -->
                            </div>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
    </div>

    <script src="js/lib/jquery/jquery.min.js"></script>
    <script src="js/lib/bootstrap/js/popper.min.js"></script>
    <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/jquery.slimscroll.js"></script>
    <script src="js/sidebarmenu.js"></script>
    <script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="js/custom.min.js"></script>
</body>
</html>
