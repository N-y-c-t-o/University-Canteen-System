<?php
session_start();

require 'C:/xampp/htdocs/UIT/config.php';

if (empty($_SESSION['rs_id'])) {
     header('location: restLogin.php');
     }

    //  echo "Restaurant ID: " . $_SESSION['rs_id'];
    $rs_id = $_SESSION['rs_id'];

$sql = "SELECT * FROM restaurant WHERE rs_id = $rs_id";
$result = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $restname = $_POST['restname'];
    $password = $_POST['password'];
    $description = $_POST['description'];
    $imagePath = ''; 

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "upload/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            if ($_FILES["image"]["size"] < 500000) {
                if (in_array($imageFileType, ['jpg', 'jpeg', 'png'])) {
                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                        echo "<script>alert('Profile updated successfully!')</script>";
                        $imagePath = $target_file;
                    } else {
                        echo "<script>alert('Error uploading the file.')</script>";
                    }
                } else {
                    echo "<script>alert('Only JPG, JPEG, and PNG files are allowed.')</script>";
                }
            } else {
                echo "<script>alert('Sorry, your file is too large.')</script>";
            }
        } else {
            echo "<script>alert('File is not an image.')</script>";
        }
    }

    $updateSql = "UPDATE restaurant SET title='$title', phone='$phone', email='$email', restname='$restname', Password='$password', description='$description'";
    if ($imagePath !== '') {
        $updateSql .= ", image='$imagePath'";
    }
    $updateSql .= " WHERE rs_id = $rs_id";

    // Execute the update query
    if ($conn->query($updateSql) === TRUE) {
        echo "<script>window.location.href = window.location.href;</script>";
    } else {
        echo "Error updating profile: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <title>Profile</title>
<head>
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Restaurant Order Table</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <link href="css/helper.css" rel="stylesheet">
    <link href="css/yay.css" rel="stylesheet">
    <link href="css/addmenu.css" rel="stylesheet">
    <link href="css/ok.css" rel="stylesheet">
  

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
                           
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="restaurant">
                    <div class="col-md-3 col-md-3">
                        <div class="card pp-30">

                            <div class="add">
                                <h2 class="m-b-0 text-white">Profile</h2>
                                <!-- <img src="/UIT/photos/banner4.jpg" alt="homepage" class="board" /> -->
                            </div>
                            
                            <!-- Profile View/Edit Section -->
                            <div class="card-body">
                                <?php while ($restaurant = $result->fetch_assoc()) { ?>
                                    <div class="container mt-5">
                                        <h2 class="mb-4">Restaurant Profile</h2>
                                        <form method="POST" action="">
                                            
                                            <div class="form-group">
                                                <label for="rs_id">Restaurant ID</label>
                                                <input type="text" class="form-control" id="rs_id" value="<?php echo htmlspecialchars($restaurant['rs_id']); ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="name">Name</label>
                                                <input type="text" class="form-control" name="title" id="title" value="<?php echo htmlspecialchars($restaurant['title']); ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="phone">Phone Number</label>
                                                <input type="text" class="form-control" name="phone" id="phone" value="<?php echo htmlspecialchars($restaurant['phone']); ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="email" class="form-control" name="email" id="email" value="<?php echo htmlspecialchars($restaurant['email']); ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="restname">restname</label>
                                                <input type="text" class="form-control" name="restname" id="restname" value="<?php echo htmlspecialchars($restaurant['restname']); ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="password">Password</label>
                                                <input type="password" class="form-control" name="password" id="myPassword" value="<?php echo htmlspecialchars($restaurant['Password']); ?>" readonly>
                                                <br>
                                                Show Password: <input type="checkbox" onclick="myFunction()">
                                            </div>
                                            <div class="form-group">
                                                <label for="description">Description</label>
                                                <textarea class="form-control" name="description" id="description" rows="3" readonly><?php echo htmlspecialchars($restaurant['description']); ?></textarea>
                                            </div>

                                            <!-- Edit Button -->
                                            <button type="button" class="btn btn-primary" id="editButton" onclick="enableEdit()">Edit</button>

                                            <!-- Save Button -->
                                            <button type="submit" class="btn btn-primary" id="saveButton" onclick="enableSave()" style="display: none;">Save</button>
                                        </form>
                                    </div>
                                    <script>
                                        function enableEdit() {
                                            document.querySelectorAll('input, textarea').forEach(function(element) {
                                                element.removeAttribute('readonly');
                                            });
                                            document.getElementById('editButton').style.display = 'none';
                                            document.getElementById('saveButton').style.display = 'inline-block';
                                            document.getElementById('image').style.display = 'inline-block';
                                        }

                                        function enableSave() {
                                            document.getElementById('updateForm').addEventListener('submit', function(e) {
                                                e.preventDefault(); // Prevent the default form submission
                                                console.log('Form submitted!'); // Check if this message appears in the console

                                                // Simulate an AJAX request (replace with actual AJAX code)
                                                setTimeout(function() {
                                                    console.log('Updating profile...'); // Check if this message appears
                                                    window.location.reload(); // Reload the page
                                                }, 1000);
                                            });
                                        }

                                        function myFunction() {
                                            var x = document.getElementById("myPassword");
                                            if (x.type === "password") {
                                                x.type = "text";
                                            } else {
                                                x.type = "password";
                                            }
                                        }
                                    </script>
                                    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
                                    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
                                <?php } ?>
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

<html>