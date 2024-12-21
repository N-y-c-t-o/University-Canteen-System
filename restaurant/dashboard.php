<!DOCTYPE html>
<html lang="en">
<?php
session_start();

require 'C:/xampp/htdocs/UIT/config.php';

if (empty($_SESSION['rs_id'])) {
     header('location: restLogin.php');
     }

    //  echo "Restaurant ID: " . $_SESSION['rs_id'];
    $rs_id = $_SESSION['rs_id'];
    // Calculate the number of unique pending orders
    $pending_sql = "SELECT COUNT(DISTINCT orderNum) as pending_count FROM users_orders WHERE rs_id = $rs_id AND status = 'Pending'";
    $pending_result = $conn->query($pending_sql);
    $pending_count = 0; // Default to 0 if no pending orders are found

    if ($pending_result && $pending_result->num_rows > 0) {
        $pending_row = $pending_result->fetch_assoc();
        $pending_count = $pending_row['pending_count'];
    }
 ?>
<head>
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Restaurant Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link href="css/helper.css" rel="stylesheet">
    <link href="css/yay.css" rel="stylesheet">
    <link href="css/dashboard.css" rel="stylesheet">

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
                 
                 <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-archive f-s-20 color-warning"></i><span class="hide-menu">Menu</span></a>
                     <ul aria-expanded="false" class="collapse">
                        <li><a href="viewmenu.php"><i class="fa fa-list"></i><span>View Menu</span></a></li>
                        <li><a href="addmenu.php"><i class="fa fa-plus"></i><span>Add Menu Item</span></a></li>
                        <li><a href="editmenu.php"><i class="fa fa-edit"></i><span>Edit Menu Item</span></a></li>
</ul>
                        </li>
                        <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-shopping-cart f-s-20 color-warning"></i><span class="hide-menu">Orders</span></a>
                     <ul aria-expanded="false" class="collapse">
                     
                        <li><a href="view_pending_order.php"><i class="fa fa-truck"></i><span style="font-size:15px">View Pending Orders</span></a></li>
                        <li><a href="Delivered_orders.php"><i class="bi bi-check-square-fill"></i><span>Delivered Orders</span></a></li>
                        <li><a href="Cancelled_orders.php"><i class="bi bi-x-octagon-fill"></i><span>Cancelled Orders</span></a></li>
</ul>
</li>
                        
                        <li><a href="profile.php"><i class="fa fa-user"></i><span>View Profile</span></a></li>
                    
                </nav>
            </div>
        </div>
        <!-- Main Content -->
        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="row card-container">
                    <div class="banner">
                        <div class="banner-content">
                        <h1>Welcome, Mr. and Mrs.!</h1>
                        <p>Manage Orders, Track Deliveries, and Analyze Data</p>
                        </div>
                    </div>

                    <div class="col-md-3 ">
                        <div class="card pp-30">
                        <img src="/UIT/photos/restaurant.jpg" alt="homepage" class="board" />

                            <div class="card-header">
                                <h4 class="m-b-0 text-dark">Menu Management</h4>
                            </div>
                            <div class="card-body">
                                <a href="viewmenu.php" class="btn ">View Menu</a>
                                <a href="addmenu.php" class="btn ">Add Menu Item</a>
                                <a href="editmenu.php" class="btn ">Edit Menu Item</a>
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
                                
                                <a href="view_pending_order.php" class="btn">View Pending Orders</a>
                                <a href="Delivered_orders.php" class="btn">Delivered Orders</a>
                                <a href="Cancelled_orders.php" class="btn">Cancelled Orders</a>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="row card-container">

                    <div class="col-md-3">
                        <div class="card pp-30">
                        <img src="/UIT/photos/notice.jpg" alt="homepage" class="board" />

                            <div class="card-header">
                                <h4 class="m-b-0 text-dark">Profile</h4>
                            </div>
                            <div class="card-body">
                            <a href="profile.php" class="btn">View Profile</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row card-container">

                    <div class="col-md-3 ">
                        <div class="card pp-30">
                        <img src="/UIT/photos/user.jpg" alt="homepage" class="board" />

                            <div class="card-header">
                                <h4 class="m-b-0 text-dark">Additonal Actions</h4>
                            </div>
                            <div class="card-body">
                                <!-- <a href="profile.php" class="btn">View User Information</a>
                                <a href="profile.php" class="btn btn-primary btn-block">View User Information</a> -->
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
