<?php
session_start();

require 'C:/xampp/htdocs/UIT/config.php';

if (empty($_SESSION['ADMIN_SESSION_EMAIL'])) {
    header('location: adminLogin.php');
    exit();
}

// Fetch restaurants from the database
$query = "SELECT * FROM restaurant";
$result = $conn->query($query);

if (!$result) {
    die("Error executing query: " . $conn->error);
}

$rs_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$res = null;
$showDetails = false;
$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (isset($_POST['show'])) {
        $rs_id = intval($_POST['rs_id']);
        $stmt = $conn->prepare("SELECT * FROM restaurant WHERE rs_id = ?");
        $stmt->bind_param("i", $rs_id);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($res) {
            $showDetails = true;
        } else {
            $error = '<div class="alert alert-danger">Restaurant not found.</div>';
        }
    }

    if (isset($_POST['submit'])) {
        $rs_id = intval($_POST['rs_id']);
        $title = $_POST['title'];
        $restname = $_POST['restname'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $psw = $_POST['Password'];
        $img = isset($_POST['image']) ? $_POST['image'] : '';
        $desc = $_POST['description'];
    
        $sql = "UPDATE restaurant SET title = ?, restname = ?, phone = ?, email = ?, Password = ?, description = ?";
    
        // Handle image without uploading
        if (!empty($_FILES['image']['name'])) {
            $img = $_FILES['image']['name'];
            $sql .= ", image = ?";
        }
    
        $sql .= " WHERE rs_id = ?";
    
        // Prepare the statement based on whether an image was selected or not
        if (!empty($img)) {
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssi", $title, $restname, $phone, $psw, $email, $desc, $img, $rs_id);
        } else {
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssi", $title, $restname, $phone, $psw, $email, $desc, $rs_id);
        }
    
        if ($stmt->execute()) {
            $success = '<div class="alert alert-success">Restaurant updated successfully.</div>';
        } else {
            $error = '<div class="alert alert-danger">Error updating restaurant.</div>';
        }
        $stmt->close();
    }

    if (isset($_POST['delete'])) {
        $rs_id = intval($_POST['rs_id']);
        
        // Determine the menu_rest table to drop based on rs_id
        // Assuming rs_id corresponds to menu_rest1 for rs_id 1, menu_rest2 for rs_id 2, etc.
        $tableIndex = $rs_id; // Adjust this logic based on your table naming scheme
        $menuTable = "menu_rest" . $tableIndex;
    
        // Delete from users_orders
        $stmt = $conn->prepare("DELETE FROM users_orders WHERE rs_id = ?");
        $stmt->bind_param("i", $rs_id);
        $stmt->execute();
        $stmt->close();
    
        // Drop the identified menu_rest table
        $dropTableQuery = "DROP TABLE IF EXISTS $menuTable";
        $conn->query($dropTableQuery);
    
        // Delete the restaurant
        $stmt = $conn->prepare("DELETE FROM restaurant WHERE rs_id = ?");
        $stmt->bind_param("i", $rs_id);
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $success = '<div class="alert alert-success">Restaurant and associated menu table deleted successfully.</div>';
            } else {
                $error = '<div class="alert alert-danger">Restaurant not found.</div>';
            }
        } else {
            $error = '<div class="alert alert-danger">Error deleting restaurant.</div>';
        }
        $stmt->close();
    }
    

    if (isset($_POST['cancel'])) {
        $rs_id = 0;
        $res = null;
        header("Location: editrestaurant.php");
        exit();
    }
}

if ($rs_id > 0 && !$res) {
    $stmt = $conn->prepare("SELECT * FROM restaurant WHERE rs_id = ?");
    $stmt->bind_param("i", $rs_id);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($res) {
        $showDetails = true;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Restaurant</title>
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/yay.css" rel="stylesheet">
    <link href="css/general.css" rel="stylesheet">
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
                <!-- Display error or success messages -->
                <?php echo $error; ?>
                <?php echo $success; ?>
                <div class="col-lg-12">
                    <div class="card card-outline-primary">
                        <div class="card-header">
                            <h2 class="m-b-0 text-white">Edit Restaurant</h2>
                        </div>
                        <div class="card-body">
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="form-body">
                                    <hr>
                                    <div class="row p-t-20">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Restaurant ID</label>
                                                <input type="text" name="rs_id" class="form-control" value="<?php echo $rs_id > 0 ? htmlspecialchars($rs_id, ENT_QUOTES, 'UTF-8') : ''; ?>" <?php echo $showDetails ? 'readonly' : ''; ?> required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Restaurant Name</label>
                                                <input type="text" name="title" class="form-control" value="<?php echo $showDetails ? htmlspecialchars($res['title'], ENT_QUOTES, 'UTF-8') : ''; ?>" <?php echo !$showDetails ? 'readonly' : ''; ?>>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row p-t-20">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Restname</label>
                                                <input type="text" name="restname" class="form-control" value="<?php echo $showDetails ? htmlspecialchars($res['restname'], ENT_QUOTES, 'UTF-8') : ''; ?>" <?php echo !$showDetails ? 'readonly' : ''; ?>>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Phone</label>
                                                <input type="number" name="phone" class="form-control" value="<?php echo $showDetails ? htmlspecialchars($res['phone'], ENT_QUOTES, 'UTF-8') : ''; ?>" <?php echo !$showDetails ? 'readonly' : ''; ?>>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row p-t-20">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Email Address</label>
                                                <input type="text" name="email" class="form-control" value="<?php echo $showDetails ? htmlspecialchars($res['email'], ENT_QUOTES, 'UTF-8') : ''; ?>" <?php echo !$showDetails ? 'readonly' : ''; ?>>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                        <div class="form-group">
                                                <label class="control-label">Password</label>
                                                <input type="password" name="Password" class="form-control" value="<?php echo $showDetails ? htmlspecialchars($res['Password'], ENT_QUOTES, 'UTF-8') : ''; ?>" <?php echo !$showDetails ? 'readonly' : ''; ?>>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row p-t-20">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Image</label>
                                                <input type="file" name="image" class="form-control" <?php echo !$showDetails ? 'disabled' : ''; ?>>
                                                <?php if ($showDetails && !empty($res['image'])): ?>
                                                    <img src="upload/<?php echo htmlspecialchars($res['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="Restaurant Image" width="100">
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                        <div class="form-group">
                                                <label class="control-label">Description</label>
                                                <input type="textfield" name="description" class="form-control" value="<?php echo $showDetails ? htmlspecialchars($res['description'], ENT_QUOTES, 'UTF-8') : ''; ?>" <?php echo !$showDetails ? 'readonly' : ''; ?>>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="form-actions">
                                    <?php if ($showDetails): ?>
    <button type="submit" name="submit" class="btn btn-success">Confirm</button>
    <button type="submit" name="delete" class="btn btn-danger" onclick="return confirmDelete();">Delete</button>
<?php endif; ?>

<script type="text/javascript">
function confirmDelete() {
    return confirm('Are you sure you want to delete this restaurant?');
}
</script>
                                    
                                        <div class="form-actions">
                                            <button type="submit" name="show" class="btn" value="Show">Show</button>
                                            <button type="submit" name="cancel" class="btn" value="Cancel">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
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
</body>
</html>
