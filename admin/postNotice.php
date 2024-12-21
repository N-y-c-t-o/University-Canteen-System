<?php
session_start();

require 'C:/xampp/htdocs/UIT/config.php';

// Convert Myanmar Time (MMT) to UTC
function convertToUTC($datetime) {
    $datetimeUTC = new DateTime($datetime, new DateTimeZone('Asia/Yangon'));
    $datetimeUTC->setTimezone(new DateTimeZone('UTC'));
    return $datetimeUTC->format('Y-m-d H:i:s');
}

// Set the time zone to Myanmar Time
date_default_timezone_set('Asia/Yangon');

if (empty($_SESSION['ADMIN_SESSION_EMAIL'])) {
    header('location: adminLogin.php');
    exit();
}

// Fetch restaurants from the database
$query = "SELECT * FROM noticeboard"; // Assuming the table name is 'noticeboard'
$result = $conn->query($query);

if (!$result) {
    die("Error executing query: " . $conn->error);
}


// $adminId = $_SESSION['user_id'];
// $role = $_SESSION['role'];

// if ($role !== 'admin') { 
//     echo "Access denied.";
//     exit();
// }

// Initialize error and success messages
$error = '';
$success = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit'])) {
        if (empty($_POST['title']) || empty($_POST['content']) || empty($_POST['startID']) || empty($_POST['endID'])) {
            $error = '<div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>All fields must be filled!</strong>
                    </div>';
        } else {
            $title = $_POST['title'];
            $content = $_POST['content'];
            $startID = $_POST['startID'];
            $endID = $_POST['endID'];

            // Convert input times from Myanmar Time (MMT) to UTC
            $startID = convertToUTC($startID);
            $endID = convertToUTC($endID);

            // Insert values into the database
            $insertQuery = "INSERT INTO noticeboard (title, content, startID, endID) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("ssss", $title, $content, $startID, $endID);
            
            if ($stmt->execute()) {
                $success = '<div class="alert alert-success alert-dismissible fade show">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                Notice added successfully.
                            </div>';
            } else {
                $error = '<div class="alert alert-danger alert-dismissible fade show">
                             <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                             <strong>Error adding notice:</strong> ' . $stmt->error . '
                         </div>';
            }            
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Notice</title>
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/yay.css" rel="stylesheet">
    <link href="css/general.css" rel="stylesheet">
    <style>
        .card-header {
            background: linear-gradient(45deg, #ff6257, #febf76, #ff6257);
            width: 900px;
            height: 60px;
            top: 50px;
            text-align: center;
            border-radius: 50px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            /* background-color: #ff6f61; */
        }

        .card-header h2{
            color: #333;
            font-weight: 700;
        }

        .oo {
            background-color: #fff;
        }

        /* .card {
            align-items: center;
        } */

        .add {
            /* background-image: url('/UIT/photos/banner3.jpg');
            background-size: cover;   
            background-repeat: no-repeat; */
            /* background: linear-gradient(90deg, #fe5d63, #ff9da1, #fe5d63); */
            /* background: linear-gradient(15deg, #fca3a6, #fe676c); */
            background: linear-gradient(45deg, #ff6257, #febf76, #ff6257);
            width: 900px;
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

        .form-actions{
            display: inline-flex;
        }

        .form-actions a{
            /* text-align:; */
        }

        .btn {
            width: 100px;
            background-color: #ff6257;
            color: #ffffff;
            border-radius: 10px;
            border: 2.5px solid #ff4e42;
            margin: 20px 10px 0px 10px;
            display: flex;
            flex-direction: row;
            transition: background-color 0.3s, transform 0.2s;
            text-align: center;
        }

        .btn:hover {
            background-color: #ff4e42;
            color: #333;
            transform: scale(1.05);
        }
        footer{
            height: 45px;
        }
        footer p{
            margin-top: 125px;
            color: #fd9a93;
        }
        </style>
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
                        <li> <a href="cancelled_Orders.php"><i class="fa fa-shopping-cart" aria-hidden="true"></i><span>Cancelled Orders</span></a></li>
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
                            <h2 class="m-b-0 text-white">Post Noticeboard</h2>
                        </div>
                        <div class="card-body">
                            <form action='' method='POST' enctype="multipart/form-data">
                                <div class="form-body">
                                    <hr>
                                    <div class="row p-t-20">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Title</label>
                                                <input type="text" name="title" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Content</label>
                                                <input type="text" name="content" class="form-control" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row p-t-20">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Start Time (MMT)</label>
                                                <input type="datetime-local" name="startID" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">End Time (MMT)</label>
                                                <input type="datetime-local" name="endID" class="form-control" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" name="submit" class="btn" value="Save">Save</button>
                                    <a href="viewNotice.php" class="btn">Cancel</a>
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
</body>
</html>