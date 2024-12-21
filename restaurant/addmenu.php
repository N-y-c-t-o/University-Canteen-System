<?php

session_start();
require '../config.php'; // Include database connection file

if (!isset($_SESSION['rs_id']) ) {
    header("Location: restLogin.php");
    exit();
}

$restaurantId = $_SESSION['rs_id'];




$menuTable = "menu_rest" . $restaurantId;

// Initialize error and success messages
$error = '';
$success = '';

// Check if the table exists, create it if not
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
                        Table created successfully.
                      </div>';
    } else {
        $error = "Error creating table: " . $conn->error;
    }
}

// Handle form submission
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit'])) {
        // Validate required fields
        if (empty($_POST['title']) || empty($_POST['slogan']) || empty($_POST['price']) || empty($_POST['stock']) || empty($_POST['time_category'])) {
            $error = '<div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>All fields must be filled!</strong>
                      </div>';
        } else {
            $title = $_POST['title'];
            $slogan = $_POST['slogan'];
            $price = $_POST['price'];
            $stock = $_POST['stock'];
            $time_category = $_POST['time_category']; // Make sure this is correctly assigned
            if ($price <= 0 || $stock <= 0) {
                $error = '<div class="alert alert-danger alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>Price and stock must be greater than 0!</strong>
                          </div>';
            } else {

            // Step 1: Get a list of all 'menu_rest' tables dynamically
            $tablesQuery = "SELECT TABLE_NAME 
                            FROM INFORMATION_SCHEMA.TABLES 
                            WHERE TABLE_NAME LIKE 'menu_rest%' 
                            AND TABLE_SCHEMA = DATABASE()";
            $result = $conn->query($tablesQuery);

            $tables = [];
            while ($row = $result->fetch_assoc()) {
                $tables[] = $row['TABLE_NAME'];
            }

            // Step 2: Construct the query to check for duplicate titles across all 'menu_rest' tables
            $unionQueries = [];
            foreach ($tables as $table) {
                $unionQueries[] = "SELECT title FROM `$table`";
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


     

if ($error == '') {
    $insertQuery = "INSERT INTO $menuTable (title, slogan, price, stock, time_category, rs_id, img) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("ssdssis", $title, $slogan, $price, $stock, $time_category, $restaurantId, $imgUpload);
   
    if ($stmt->execute()) {
        $success = '<div class="alert alert-success alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        Menu item added successfully.
                      </div>';
    } else {
        $error = "Error executing statement: " . $stmt->error;
    }
    $stmt->close();
} elseif ($error == '') {
    // If no image was uploaded, show an error message
    $error = '<div class="alert alert-danger alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Image upload required!</strong> Please upload an image to add the menu item.
              </div>';
}
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
    <title>Add Menu Item</title>
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
                        <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-shopping-cart f-s-20 color-warning"></i><span class="hide-menu">Orders</span></a>
                     <ul aria-expanded="false" class="collapse">
                     
                        <li><a href="view_pending_order.php"><i class="fa fa-truck"></i><span>View Pending Orders</span></a></li>
                        <li> <a href="Delivered_orders.php"><i class="bi bi-check-square-fill"></i><span>Delivered Orders</span></a></li>
                        <li> <a href="Cancelled_orders.php"><i class="bi bi-x-octagon-fill"></i><span>Cancelled orders</span></a></li>
</ul>
</li>
                        
                        <li><a href="profile.php"><i class="fa fa-user"></i><span>View Profile</span></a></li>
                    
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
                    <div class="add">
                            <h2 class="m-b-0 text-white">Add Menu Items</h2>
                            <!-- <img src="/UIT/photos/banner4.jpg" alt="homepage" class="board" /> -->
                        </div>
                        <div class="card-body">
                            <form action='' method='post' enctype="multipart/form-data">
                                <div class="form-body">
                                    <hr>
                                    <div class="row p-t-20">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Dish Name</label>
                                                <input type="text" name="title" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Description</label>
                                                <input type="text" name="slogan" class="form-control" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row p-t-20">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Price</label>
                                                <input type="number" name="price" class="form-control" placeholder="MMK" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Available Stock</label>
                                                <input type="number" name="stock" class="form-control" placeholder="Qty" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row p-t-20">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Upload Image</label>
                                                <input type="file" name="img" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Time Category</label>
                                                <select name="time_category"  class="form-control" required>
    
    <option value="breakfast">Breakfast</option>
    <option value="lunch">Lunch</option>
    <option value="postNoon">Post Noon</option>
</select>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions">
                                <button type="submit" name="submit" class="btn" value="Save">Save</button>
                                    <a href="viewmenu.php" class="btn btn-inverse">Cancel</a>
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
