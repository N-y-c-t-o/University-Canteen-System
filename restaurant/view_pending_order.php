<?php
session_start();
if (!isset($_SESSION['rs_id'])) {
    header("Location: restLogin.php");
    exit();
}


include 'C:/xampp/htdocs/UIT/config.php';
$rs_id = $_SESSION['rs_id'];


$sql = "SELECT  
            o.o_id,
            o.orderNum,
            o.title, 
            o.quantity, 
            o.price,   
            o.status AS order_status,
            o.payment,
            o.deliOption,
            o.special,
            o.DeliTime,
            u.*
        FROM users_orders o
        LEFT JOIN users u ON o.u_id = u.u_id
        WHERE o.rs_id = $rs_id
        ORDER BY  o.o_id DESC";

$result = $conn->query($sql);

?>

<!-- HTML and CSS -->

<head>
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/yay.css" rel="stylesheet">
    <link href="css/viewmenu.css" rel="stylesheet">

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Restaurant Order Table</title>
    <link href="css/ok.css" rel="stylesheet">
    <style>
        .table-container {
            max-height: 550px;
            overflow-y: auto;
        }
        .table {
            width: 100%;
        }
        .thead-sticky th {
            position: sticky; 
            top: 0;
            background-color: #343a40; 
            color: white;
            z-index: 10;
        }
        .btn {
            border-radius: 5px;
        }
        .btn-custom-sm {
            padding: 2px 6px;
            font-size: 0.75rem;
            width: 30px;
            height: 30px;
        }
        td, th {
            text-align: center;
            vertical-align: middle; /* Center align vertically */
        }
        .no-border {
            border-top: none;
            border-bottom: none;
        }
        .add {
            background: linear-gradient(45deg, #ff6257, #febf76, #ff6257);
            width: 920px;
            height: 60px;
            top: 50px;
            text-align: center;
            border-radius: 5px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            /* background-color: #ff6f61; */
        }

        .add h2{
            color: #333;
            font-weight: 700;
            margin-top: 10px;
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

        <!-- Main Content -->
        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card pp-30">
                            <div class="add">
                                <h2 class="m-b-0 text-white">Orders</h2>
                                <!-- <img src="/UIT/photos/banner4.jpg" alt="homepage" class="board" /> -->
                            </div>
                            <div class="card-body">
                                <?php if ($result->num_rows > 0) { ?>
                                    <div class="table-container m-t-20">
                                        <table class="display nowrap table  table-bordered" cellspacing="0" width="100%" style="font-size:14px;">
                                            <thead class="thead-sticky">
                                                <tr>
                                                    <th scope="col">Order_Number</th>
                                                    <th scope="col">Title</th>
                                                    <th scope="col">Quantity</th>
                                                    <th scope="col">Price</th>
                                                    <th scope="col">Status</th>
                                                    <th scope="col">Payment</th>
                                                    <th scope="col">Delivery_Option</th>
                                                    <th scope="col">Delivery_Time</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $currentOrderNum = null;
                                                $rowCount = 0;

                                                while ($row = $result->fetch_assoc()) {
                                                    if ($currentOrderNum !== $row['orderNum']) {
                                                        // New order number, calculate the number of rows for this order
                                                        $currentOrderNum = $row['orderNum'];
                                                        $sql_count = "SELECT COUNT(*) as count FROM users_orders WHERE orderNum = '$currentOrderNum' AND rs_id = $rs_id";
                                                        $count_result = $conn->query($sql_count);
                                                        $count_row = $count_result->fetch_assoc();
                                                        $rowCount = $count_row['count'];
                                                        
                                                        // Output the merged cells with rowspan
                                                        ?>
                                                        <tr>
                                                            <td rowspan="<?php echo $rowCount; ?>"><?php echo htmlspecialchars($row['orderNum']); ?></td>
                                                            <td class="text-left"><?php echo htmlspecialchars($row['title']); ?></td>
                                                            <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                                                            <td><?php echo htmlspecialchars($row['price']); ?></td>
                                                            <td rowspan="<?php echo $rowCount; ?>">
                                                                <?php
                                                                switch (htmlspecialchars($row['order_status'])) {
                                                                    case "Pending":
                                                                        echo '<button class="btn btn-warning"><i class="fas fa-hourglass-half"></i> Pending</button>';
                                                                        break;
                                                                    case "Way":
                                                                        echo '<button class="btn btn-info"><i class="bi bi-gear-fill"></i> On the Way</button>';
                                                                        break;
                                                                    case "Cancelled":
                                                                        echo '<button class="btn btn-danger"><i class="fas fa-times-circle"></i> Cancelled</button>';
                                                                        break;
                                                                    case "Delivered":
                                                                        echo '<button class="btn btn-success"><i class="fas fa-check-circle"></i> Delivered</button>';
                                                                        break;
                                                                }
                                                                ?>
                                                            </td>
                                                            <td rowspan="<?php echo $rowCount; ?>"><?php echo htmlspecialchars($row['payment']); ?></td>
                                                            <td rowspan="<?php echo $rowCount; ?>"><?php echo htmlspecialchars($row['deliOption']); ?></td>
                                                            <td rowspan="<?php echo $rowCount; ?>"><?php echo htmlspecialchars($row['DeliTime']); ?></td>
                                                            <td rowspan="<?php echo $rowCount; ?>">
                                                                <button class="btn btn-info btn-custom-sm " onclick="viewUserDetails('<?php echo htmlspecialchars($row['u_id']); ?>')">
                                                                <i class="bi bi-person-square"></i>
                                                                </button>
                                                                <button class="btn btn-secondary btn-custom-sm " onclick="openEditWindow('<?php echo htmlspecialchars($row['orderNum']); ?>')">
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                                <button class="btn btn-danger btn-custom-sm" onclick="return confirm('Are you sure you want to delete this item?');">
                                                                    <a href="delete_order.php?id=<?php echo htmlspecialchars($row['orderNum']); ?>" class="text-white">
                                                                        <i class="fas fa-trash-alt"></i>
                                                                    </a>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    <?php } else { ?>
                                                        <!-- Subsequent rows for the same order -->
                                                        <tr>
                                                            <td class="text-left"><?php echo htmlspecialchars($row['title']); ?></td>
                                                            <td class="text-center"><?php echo htmlspecialchars($row['quantity']); ?></td>
                                                            <td class="text-center"><?php echo htmlspecialchars($row['price']); ?></td>
                                                        </tr>
                                                    <?php }
                                                } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                
                                <?php } else {
                                        echo "<tr><td colspan='7'><center>No Order today!!!!</center></td></tr>";
                                    } ?>
                            </div>
                        </div>
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
        <script>
            function openEditWindow(orderNum) {
                const url = 'update_status.php?id=' + orderNum;

                const width = window.screen.width;
                const height = window.screen.height;
                const windowFeatures = `width=${width},height=${height},scrollbars=yes,resizable=yes`;
                window.open(url, 'EditOrderWindow', windowFeatures);
            }
            function viewUserDetails(id) {
                const url = 'userDetails.php?id=' + id;

                const width = window.screen.width;
                const height = window.screen.height;
                const windowFeatures = `width=${width},height=${height},scrollbars=yes,resizable=yes`;
                window.open(url, 'userDetailsWindow', windowFeatures);
            }
        </script>
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
