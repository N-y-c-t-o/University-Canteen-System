<?php
session_start();


if (!isset($_SESSION['rs_id'])) {
    header("Location: restLogin.php");
    exit();
}

include 'C:/xampp/htdocs/UIT/config.php';
$rs_id = $_SESSION['rs_id'];



// Fetch menu items from the database
$query = "SELECT * FROM success_orders WHERE rs_id=$rs_id ORDER BY id DESC";
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
    <title>Menu Items</title>
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <link href="css/helper.css" rel="stylesheet">
    <link href="css/yay.css" rel="stylesheet">
    <link href="css/viewmenu.css" rel="stylesheet">

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
                    
                    <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-archive f-s-20 color-warning"></i><span class="hide-menu">Menu</span></a>
                        <ul aria-expanded="false" class="collapse">
                            <li><a href="viewmenu.php"><i class="fa fa-list"></i><span>View Menu</span></a></li>
                            <li><a href="addmenu.php"><i class="fa fa-plus"></i><span>Add Menu Item</span></a></li>
                            <li><a href="editmenu.php"><i class="fa fa-edit"></i><span>Edit Menu Item</span></a></li>
                        </ul>
                    </li>
                    
                    <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-shopping-cart f-s-20 color-warning"></i><span class="hide-menu">Orders</span></a>
                        <ul aria-expanded="false" class="collapse">
                            <li><a href="view_pending_order.php"><i class="fa fa-truck"></i><span>View Pending Orders</span></a></li>
                            <li> <a href="Delivered_orders.php"><i class="bi bi-check-square-fill"></i><span>Delivered Orders</span></a></li>
                            <li> <a href="Cancelled_orders.php"><i class="bi bi-x-octagon-fill"></i><span>Cancelled orders</span></a></li>
                        </ul>
                    </li>
                            
                    <li><a href="profile.php"><i class="fa fa-user"></i><span>View Profile</span></a></li>
                </ul>       
            </nav>
        </div>
    </div>

        <div class="page-wrapper">
            <div class="container-fluid">
                <!-- Display error or success messages -->
                <?php
                // Show messages if any
                if (isset($error) && $error !== '') {
                    echo $error;
                }
                if (isset($success) && $success !== '') {
                    echo $success;
                }
                ?>

                <!-- Start Page Content -->
                <div class="col-lg-12">
                    <div class="card card-outline-primary">

                        <div class="card-header">
                            <h2 class="m-b-0 text-white">Delivered Orders</h2>
                            <!-- <img src="/UIT/photos/banner4.jpg" alt="homepage" class="board" /> -->
                        </div>
                        <div class="table-responsive m-t-40">
                            <table id="example23" class="display nowrap table  table-bordered" cellspacing="0" width="100%">
                                <thead class="thead-dark">
                                    <tr class="oo">
                                        <th>Order_Num</th> <!-- Added ID column -->
                                        <th>Username</th>
                                        <th>Title</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total_price</th>
                                        <th>Updated_time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $currentOrderNum = null;
                                    $rowCount = 0;
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            if ($currentOrderNum !== $row['orderNum']) {
                                                // New order number
                                                $currentOrderNum = $row['orderNum'];
                                                $sql_count = "SELECT COUNT(*) as count FROM success_orders WHERE orderNum = '$currentOrderNum'";
                                                $count_result = $conn->query($sql_count);
                                                $count_row = $count_result->fetch_assoc();
                                                $rowCount = $count_row['count'];
                                                
                                                echo "<tr>";
                                                echo "<td rowspan='{$rowCount}' class='text-center'>" . htmlspecialchars($row['orderNum'], ENT_QUOTES, 'UTF-8') . "</td>";
                                                echo "<td rowspan='{$rowCount}' class='text-center'>" . htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8') . "</td>";
                                                echo "<td>" . htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') . "</td>";
                                                echo "<td class='text-center'>" . htmlspecialchars($row['quantity'], ENT_QUOTES, 'UTF-8') . "</td>";
                                                echo "<td class='text-center'>" . htmlspecialchars($row['price'], ENT_QUOTES, 'UTF-8') . "</td>";
                                                echo "<td rowspan='{$rowCount}' class='text-center'>" . htmlspecialchars($row['total_price'], ENT_QUOTES, 'UTF-8') . "</td>";
                                                echo "<td rowspan='{$rowCount}' class='text-center'>" . htmlspecialchars($row['soldout_time'], ENT_QUOTES, 'UTF-8') . "</td>";
                                                echo "</tr>";
                                            } else {
                                                // Subsequent rows for the same order
                                                echo "<tr>";
                                                echo "<td>" . htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') . "</td>";
                                                echo "<td class='text-center'>" . htmlspecialchars($row['quantity'], ENT_QUOTES, 'UTF-8') . "</td>";
                                                echo "<td class='text-center'>" . htmlspecialchars($row['price'], ENT_QUOTES, 'UTF-8') . "</td>";
                                                echo "</tr>";
                                            }
                                        }
                                    } else {
                                        echo "<tr><td colspan='7'><center>No Orders available</center></td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        
                    </div>
                </div>
            </div>  
            
            <!-- <footer class="footer">
                <div class="container">
                    <div class="row">
                        <div class="col-12 text-center">
                            <p>© 2024 University Canteen System</p>
                        </div>
                    </div>
                </div>
            </footer> -->
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
