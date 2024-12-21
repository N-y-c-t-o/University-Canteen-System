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

// Initialize error and success messages
$error = '';
$success = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit'])) {
        if (empty($_POST['title']) || empty($_POST['restname']) || empty($_POST['phone']) || empty($_POST['password']) || empty($_POST['description']) || empty($_POST['email']) || empty($_FILES['img']['name'])) {
            $error = '<div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>All fields must be filled, including the image!</strong>
                      </div>';
        } else {
            $title = $_POST['title'];
            $restname = $_POST['restname'];
            $phone = $_POST['phone'];
            $password = md5($_POST['password']);  // MD5 hash
            $description = $_POST['description'];
            $email = $_POST['email'];

            // Check if the restname or title already exists
            $checkQuery = "SELECT * FROM restaurant WHERE restname = ? OR title = ?";
            $stmt = $conn->prepare($checkQuery);
            $stmt->bind_param("ss", $restname, $title);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $error = '<div class="alert alert-danger alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>Restaurant name or title already exists!</strong>
                          </div>';
            } else {
                // Get image name and store it
                $imgUpload = '';
                if (isset($_FILES['img']) && $_FILES['img']['error'] == UPLOAD_ERR_OK) {
                    $fname = $_FILES['img']['name'];
                    $extension = strtolower(pathinfo($fname, PATHINFO_EXTENSION));
                    $baseName = pathinfo($fname, PATHINFO_FILENAME);

                    // Check valid extensions
                    if (in_array($extension, ['jpg', 'png', 'gif'])) {
                        // Save the image name (without uploading)
                        $imgUpload = $baseName . '.' . $extension;
                    } else {
                        $error = '<div class="alert alert-danger alert-dismissible fade show">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <strong>Invalid extension!</strong> Only png, jpg, and gif are accepted.
                                  </div>';
                    }
                } else {
                    $error = '<div class="alert alert-danger alert-dismissible fade show">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <strong>An image is required!</strong>
                              </div>';
                }

                // Insert new restaurant into the database if no errors
                if (empty($error)) {
                    $insertQuery = "INSERT INTO restaurant (title, restname, phone, password, description, email, image, date) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
                    $stmt = $conn->prepare($insertQuery);
                    $stmt->bind_param("sssssss", $title, $restname, $phone, $password, $description, $email, $imgUpload);
                    
                    if ($stmt->execute()) {
                        // Get the last inserted restaurant ID
                        $restaurantId = $stmt->insert_id;

                        // Create a new menu_rest table for the restaurant
                        $menuTable = "menu_rest" . $restaurantId;
                        $tableCheckQuery = "SHOW TABLES LIKE '$menuTable'";
                        $result = $conn->query($tableCheckQuery);

                        if ($result && $result->num_rows == 0) {
                            // Table does not exist, create it
                            $createTableQuery = "CREATE TABLE $menuTable (
                                d_id INT AUTO_INCREMENT PRIMARY KEY,
                                title VARCHAR(255) NOT NULL,
                                slogan TEXT NOT NULL,
                                price DECIMAL(10, 0) NOT NULL,
                                stock INT NOT NULL,
                                time_category VARCHAR(50) NOT NULL,
                                rs_id INT NOT NULL,
                                img VARCHAR(255)
                            )";
                            if ($conn->query($createTableQuery) === TRUE) {
                                $success = '<div class="alert alert-success alert-dismissible fade show">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                Restaurant and menu table created successfully.
                                            </div>';
                            } else {
                                $error = "Error creating menu table: " . $conn->error;
                            }
                        }

                    } else {
                        $error = "Error executing statement: " . $stmt->error;
                    }
                    $stmt->close();
                }
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Restaurant</title>
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

        <div class="page-wrapper">
            <div class="container-fluid">
                <!-- Display error or success messages -->
                <?php echo $error; ?>
                <?php echo $success; ?>

                <!-- Start Page Content -->
                <div class="col-lg-12">
                    <div class="card card-outline-primary">
                        <div class="card-header">
                            <h2 class="m-b-0 text-white">Add Restaurant</h2>
                        </div>
                        <div class="card-body">
                            <form action='' method='post' enctype="multipart/form-data">
                                <div class="form-body">
                                    <hr>
                                    <div class="row p-t-20">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Restaurant Name</label>
                                                <input type="text" name="title" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Restname</label>
                                                <input type="text" name="restname" class="form-control" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row p-t-20">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Phone Number</label>
                                                <input type="text" name="phone" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Email Address</label>
                                                <input type="email" name="email" class="form-control" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row p-t-20">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Password</label>
                                                <input type="password" name="password" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Upload Image</label>
                                                <input type="file" name="img" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row p-t-20">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label">Description</label>
                                                <textarea name="description" class="form-control" rows="4" required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" name="submit" class="btn" value="Save">Save</button>
                                    <a href="addrestaurant.php" class="btn">Cancel</a>                                
                                </div>
                            </form>
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
