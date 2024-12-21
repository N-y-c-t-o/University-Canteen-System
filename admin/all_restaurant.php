<?php
session_start();

require 'C:/xampp/htdocs/UIT/config.php';
 
if (empty($_SESSION['ADMIN_SESSION_EMAIL'])) {
    header('location: adminLogin.php');
    exit();
}

// Fetch restaurants from the database
$query = "SELECT * FROM restaurant"; // Assuming the table name is 'restaurant'
$result = $conn->query($query);

if (!$result) {
    die("Error executing query: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Restaurants</title>
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/yay.css" rel="stylesheet">
    <link href="css/general.css" rel="stylesheet">

    <style>
        tr.clickable-row {
            cursor: pointer;
        }
    </style>
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
                 <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-archive f-s-20 color-warning"></i><span class="hide-menu">Restaurant</span></a>
                     <ul aria-expanded="false" class="collapse">
                         <li><a href="all_restaurant.php">All Restaurants</a></li>
                         <li><a href="addrestaurant.php">Add Restaurant</a></li>
                         <li><a href="editrestaurant.php">Edit Restaurant</a></li>
                     </ul>
                 </li>
                 <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-sticky-note f-s-20 color-warning"></i><span class="hide-menu">Noticeboard</span></a>
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


        <div class="page-wrapper">
            <div class="container-fluid">
               

                <!-- Start Page Content -->
                <div class="col-lg-12">
                    <div class="card card-outline-primary">
                        <div class="card-header">
                            <h2 class="m-b-0 text-white">Restaurants</h2>
                        </div>
                        <div class="table-responsive m-t-40">
                            <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>ID</th> <!-- Added ID column -->
                                        <th>Image</th>
                                        <th>Title</th>
                                        <th>Restname</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $imgPath = 'upload/' . htmlspecialchars($row['image']);
                                            echo "<tr class='clickable-row' data-href='editrestaurant.php?id=" . htmlspecialchars($row['rs_id'], ENT_QUOTES, 'UTF-8') . "'>";
                                            echo "<td>" . htmlspecialchars($row['rs_id'], ENT_QUOTES, 'UTF-8') . "</td>"; // Display restaurant ID
                                            echo "<td>";
                                            if (file_exists($imgPath)) {
                                                echo "<image src='" . htmlspecialchars($imgPath, ENT_QUOTES, 'UTF-8') . "' alt='Image' class='img-responsive radius' style='max-height:100px;max-width:150px;'/>";
                                            } else {
                                                echo "No Image";
                                            }
                                            echo "</td>";
                                            echo "<td>" . htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') . "</td>";
                                            echo "<td>" . htmlspecialchars($row['restname'], ENT_QUOTES, 'UTF-8') . "</td>";
                                            echo "<td>" . htmlspecialchars($row['phone'], ENT_QUOTES, 'UTF-8') . "</td>";
                                            echo "<td>" . htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8') . "</td>";
                                            echo "<td>" . htmlspecialchars($row['description'], ENT_QUOTES, 'UTF-8') . "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='7'><center>No restaurants available</center></td></tr>";
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

    <script src="js/lib/jquery/jquery.min.js"></script>
    <script src="js/lib/bootstrap/js/popper.min.js"></script>
    <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/jquery.slimscroll.js"></script>
    <script src="js/sidebarmenu.js"></script>
    <script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="js/custom.min.js"></script>
    <script src="js/lib/datatables/datatables.min.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
    <script src="js/lib/datatables/cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="js/lib/datatables/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="js/lib/datatables/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
    <script src="js/lib/datatables/datatables-init.js"></script>
</body>
</html>
