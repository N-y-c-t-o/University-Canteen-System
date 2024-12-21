<!DOCTYPE html>
<html lang="en">
<?php
// session_start();
// if (empty($_SESSION["a_id"])) {
//     header('location:index.php');
//     exit();
// }

session_start();
if (empty($_SESSION['ADMIN_SESSION_EMAIL'])) {
    header("Location: adminLogin.php");
    die();
}

include 'C:/xampp/htdocs/UIT/config.php';

// Fetch users details from the database
$query = "SELECT * FROM cancelled_orders";
$result = $conn->query($query);

if (!$result) {
    die("Error executing query: " . $conn->error);
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
    <link href="css/all_users.css" rel="stylesheet">
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/yay.css" rel="stylesheet">
    


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="../restaurant/css/helper.css" rel="stylesheet">
    <link href="../restaurant/css/yay.css" rel="stylesheet">
    <link href="../restaurant/css/viewmenu.css" rel="stylesheet">



</head>

<body class="fix-header">
    <div id="main-wrapper">
        <!-- Header -->
        <div class="header">
        <nav class="navbar top-navbar navbar-expand-md navbar-light">
            <div class="navbar-header">
                <a class="navbar-brand" href="dashboard.php">
                <span><img src="/UIT/photos/logo.png" alt="homepage" class="dark-logo" /></span>
                </a>
            </div>
                <div class="navbar-collapse">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a href="logout.php"><i class="fa fa-power-off"></i> Logout</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>

        <!-- Sidebar -->
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
                                <li><a href="all_restaurant.php"><i class="fa fa-list"></i><span>All Restaurants</span></a></li>
                                <li><a href="addrestaurant.php"><i class="fa fa-plus"></i><span>Add Restaurant</span></a></li>
                                <li><a href="editrestaurant.php"><i class="fa fa-edit"></i><span>Edit Restaurant</span></a></li>
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

        <!-- Main Content -->
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
                            <h2 class="m-b-0 text-white">Cancelled Orders</h2>
                            <!-- <img src="/UIT/photos/banner4.jpg" alt="homepage" class="board" /> -->
                        </div>
                        <div class="table-responsive m-t-40">
                            <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                <thead class="thead-dark">
                                    <tr class="oo">
                                        <th>Order Number</th> <!-- Added ID column -->
                                        <th>Restaurant Id</th>
                                        <th>User Id</th>
                                        <th>Username</th>
                                        <th>Title</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total price</th>
                                        <th>Updated time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            
                                            echo "</td>";
                                            echo "<td>" . htmlspecialchars($row['orderNum'], ENT_QUOTES, 'UTF-8') . "</td>";
                                            echo "<td>" . htmlspecialchars($row['rs_id'], ENT_QUOTES, 'UTF-8') . "</td>";
                                            echo "<td>" . htmlspecialchars($row['u_id'], ENT_QUOTES, 'UTF-8') . "</td>";
                                            echo "<td>" . htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8') . "</td>";
                                            echo "<td>" . htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') . "</td>";
                                            echo "<td>" . htmlspecialchars($row['quantity'], ENT_QUOTES, 'UTF-8') . "</td>";
                                            echo "<td>" . htmlspecialchars($row['price'], ENT_QUOTES, 'UTF-8') . "</td>";
                                            echo "<td>" . htmlspecialchars($row['total_price'], ENT_QUOTES, 'UTF-8') . "</td>";
                                            echo "<td>" . htmlspecialchars($row['cancelled_time'], ENT_QUOTES, 'UTF-8') . "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='7'><center>No users' information are available</center></td></tr>";
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
    <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/jquery.slimscroll.js"></script>
    <script src="js/sidebarmenu.js"></script>
    <script src="js/custom.min.js"></script>

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