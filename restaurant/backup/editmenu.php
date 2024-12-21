<?php
session_start();
require 'C:/xampp/htdocs/UIT/config.php';

if (!isset($_SESSION['rs_id'])) {
    header("Location: restLogin.php");
    exit();
}

$restaurantId = $_SESSION['rs_id'];

$menuTable = "menu_rest" . $restaurantId;
$d_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$menuItem = null;
$showDetails = false;
$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (isset($_POST['show'])) {
        $d_id = intval($_POST['d_id']);
        $stmt = $conn->prepare("SELECT * FROM $menuTable WHERE d_id = ?");
        $stmt->bind_param("i", $d_id);
        $stmt->execute();
        $menuItem = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($menuItem) {
            $showDetails = true;
        } else {
            $error = '<div class="alert alert-danger">Menu item not found.</div>';
        }
    }

    if (isset($_POST['submit'])) {
        $d_id = intval($_POST['d_id']);
        $title = trim($_POST['title']);
        $slogan = trim($_POST['slogan']);
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $timeCategory = $_POST['time_category'];

        // Step 1: Check for duplicate title in the current table excluding the current item
        $stmt = $conn->prepare("SELECT COUNT(*) FROM $menuTable WHERE title = ? AND d_id != ?");
        $stmt->bind_param("si", $title, $d_id);
        $stmt->execute();
        $count = $stmt->get_result()->fetch_row()[0];
        $stmt->close();

        if ($count > 0) {
            $error = '<div class="alert alert-danger">This title already exists in the current menu.</div>';
        } else {
            // Step 2: Check for duplicate title across all menu_rest tables
            $tablesQuery = "SELECT TABLE_NAME 
                            FROM INFORMATION_SCHEMA.TABLES 
                            WHERE TABLE_NAME LIKE 'menu_rest%' 
                            AND TABLE_SCHEMA = DATABASE()";
            $result = $conn->query($tablesQuery);

            $tables = [];
            while ($row = $result->fetch_assoc()) {
                $tables[] = $row['TABLE_NAME'];
            }

            $unionQueries = [];
            foreach ($tables as $table) {
                if ($table !== $menuTable) { // Exclude the current table
                    $unionQueries[] = "SELECT title FROM `$table`";
                }
            }

            $duplicateCheckQuery = "SELECT COUNT(*) AS cnt FROM (" . implode(" UNION ALL ", $unionQueries) . ") AS combined_menus WHERE title = ?";
            $stmt = $conn->prepare($duplicateCheckQuery);
            $stmt->bind_param("s", $title);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();

            if ($count > 0) {
                $error = '<div class="alert alert-danger alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>The title already exists in this menu or in another restaurant\'s menu!</strong>
                </div>';
            } else {
                // Proceed with update
                $sql = "UPDATE $menuTable SET title = ?, slogan = ?, price = ?, stock = ?, time_category = ?";

                if (!empty($_FILES['image']['name'])) {
                    $target = "upload/" . basename($_FILES['image']['name']);
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                        $img = $_FILES['image']['name'];
                        $sql .= ", image = ?";
                    }
                }

                $sql .= " WHERE d_id = ?";

                if (!empty($_FILES['image']['name'])) {
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ssdsssi", $title, $slogan, $price, $stock, $timeCategory, $image, $d_id);
                } else {
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ssdssi", $title, $slogan, $price, $stock, $timeCategory, $d_id);
                }

                if ($stmt->execute()) {
                    $success = '<div class="alert alert-success">Menu item updated successfully.</div>';
                } else {
                    $error = '<div class="alert alert-danger">Error updating menu item.</div>';
                }
                $stmt->close();
            }
        }
    }

    if (isset($_POST['delete'])) {
        $d_id = intval($_POST['d_id']);
        $stmt = $conn->prepare("DELETE FROM $menuTable WHERE d_id = ?");
        $stmt->bind_param("i", $d_id);
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $success = '<div class="alert alert-success">Menu item deleted successfully.</div>';
                $menuItem = null;
            } else {
                $error = '<div class="alert alert-danger">Menu item not found.</div>';
            }
        } else {
            $error = '<div class="alert alert-danger">Error deleting menu item.</div>';
        }
        $stmt->close();
    }

    if (isset($_POST['cancel'])) {
        $d_id = 0;
        $menuItem = null;
        header("Location: editmenu.php");
        exit();
    }
}

if ($d_id > 0 && !$menuItem) {
    $stmt = $conn->prepare("SELECT * FROM $menuTable WHERE d_id = ?");
    $stmt->bind_param("i", $d_id);
    $stmt->execute();
    $menuItem = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($menuItem) {
        $showDetails = true;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Menu Item</title>
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/yay.css" rel="stylesheet">
    <link href="css/addmenu.css" rel="stylesheet">
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
                
                <li><a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-shopping-cart f-s-20 color-warning"></i><span class="hide-menu">Orders</span></a>
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
                <?php echo $error; ?>
                <?php echo $success; ?>

                <div class="col-lg-12">
                    <div class="card card-outline-primary">
                        <div class="add">
                            <h2 class="m-b-0 text-white">Edit Menu Items</h2>
                            <!-- <img src="/UIT/photos/banner4.jpg" alt="homepage" class="board" /> -->
                        </div>
                        <div class="card-body">
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="form-body">
                                    <hr>
                                    <div class="row p-t-20">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Dish ID</label>
                                                
                                                <input type="text" name="d_id" placeholder="First enter dish id and click save." class="form-control" value="<?php echo $d_id > 0 ? htmlspecialchars($d_id, ENT_QUOTES, 'UTF-8') : ''; ?>" <?php echo $showDetails ? 'readonly' : ''; ?> required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Dish Name</label>
                                                <input type="text" name="title" class="form-control" value="<?php echo $showDetails ? htmlspecialchars($menuItem['title'], ENT_QUOTES, 'UTF-8') : ''; ?>" <?php echo !$showDetails ? 'readonly' : ''; ?>>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row p-t-20">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Description</label>
                                                <input type="text" name="slogan" class="form-control" value="<?php echo $showDetails ? htmlspecialchars($menuItem['slogan'], ENT_QUOTES, 'UTF-8') : ''; ?>" <?php echo !$showDetails ? 'readonly' : ''; ?>>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Price</label>
                                                <input type="number" name="price" class="form-control" value="<?php echo $showDetails ? htmlspecialchars($menuItem['price'], ENT_QUOTES, 'UTF-8') : ''; ?>" <?php echo !$showDetails ? 'readonly' : ''; ?>>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row p-t-20">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Available Stock</label>
                                                <input type="number" name="stock" class="form-control" value="<?php echo $showDetails ? htmlspecialchars($menuItem['stock'], ENT_QUOTES, 'UTF-8') : ''; ?>" <?php echo !$showDetails ? 'readonly' : ''; ?>>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Available Time</label>
                                                <select name="time_category" class="form-control" <?php echo !$showDetails ? 'disabled' : ''; ?>>
                                                    <option value="breakfast" <?php echo $showDetails && $menuItem['time_category'] == 'breakfast' ? 'selected' : ''; ?>>Breakfast</option>
                                                    <option value="lunch" <?php echo $showDetails && $menuItem['time_category'] == 'lunch' ? 'selected' : ''; ?>>Lunch</option>
                                                    <option value="postNoon" <?php echo $showDetails && $menuItem['time_category'] == 'postNoon' ? 'selected' : ''; ?>>Post Noon</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row p-t-20">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Image</label>
                                                <input type="file" name="img" class="form-control" <?php echo !$showDetails ? 'disabled' : ''; ?>>
                                                <?php if ($showDetails && !empty($menuItem['img'])): ?>
                                                    <img src="upload/<?php echo htmlspecialchars($menuItem['img'], ENT_QUOTES, 'UTF-8'); ?>" alt="Menu Image" width="100">
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <?php if ($showDetails): ?>
                                            <button type="submit" name="submit" class="btn btn-success">Confirm</button>
                                            <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                                        <?php endif; ?>
                                        <!-- <button type="submit" name="show" class="btn btn-primary">Show</button>
                                        <button type="submit" name="cancel" class="btn btn-secondary">Cancel</button> -->

                                        <div class="form-actions">
                                            <button type="submit" name="show" class="btn" value="Save">Save</button>
                                            <button type="submit" name="cancel" class="btn" value="Cancel">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="footer">
                <div class="container">
                    <div class="row">
                        <div class="col-12 text-center">
                            <p>© 2024 University Canteen System</p>
                        </div>
                    </div>
                </div>
            </footer>
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
