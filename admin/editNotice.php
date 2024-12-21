<?php
session_start();
require 'C:/xampp/htdocs/UIT/config.php';
$msg = null;
if (empty($_SESSION['ADMIN_SESSION_EMAIL'])) {
    header('location: adminLogin.php');
    exit();
}

// Fetch noticeboard items from the databas


// Handle save action
if (isset($_POST['save'])) {
    $noticeid = intval($_POST['noticeid']);
    $title = $_POST['title'];
    $content = $_POST['content'];
    $startID = $_POST['startID'];
    $endID = $_POST['endID'];

    $stmt = $conn->prepare("UPDATE noticeboard SET title = ?, content = ?, startID = ?, endID = ? WHERE noticeid = ?");
    $stmt->bind_param( 'ssssi',$title, $content, $startID, $endID, $noticeid);

    if ($stmt->execute()) {
        $msg = "update successful";
        $_SESSION['message']=$msg;
        header('Location: editNotice.php');
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating notice.']);
    }
    $stmt->close();
    exit();
}

// Handle delete action
if (isset($_POST['delete'])) {
    $noticeid = intval($_POST['noticeid']);
    $stmt = $conn->prepare("DELETE FROM noticeboard WHERE noticeid = ?");
    $stmt->bind_param("i", $noticeid);

    if ($stmt->execute()) {
        $msg = "delete successful";
        $_SESSION['message']=$msg;
        header('Location: editNotice.php');
    } else {
        echo "<script>alert('Error deleting notice.);</script>";
    }
    $stmt->close();
    exit();
}

// Handle cancel action
if (isset($_POST['cancel'])) {
    echo json_encode(['success' => true, 'message' => 'Cancelled.']);
    exit();
}

// Fetch noticeboard items from the database
$query = "SELECT * FROM noticeboard";
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

    <title>Edit Notice</title>

    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/yay.css" rel="stylesheet">
    <link href="../admin/css/all_users.css" rel="stylesheet">
    <link href="../admin/css/helper.css" rel="stylesheet">
    <link href="../admin/css/yay.css" rel="stylesheet">
    <link href="../admin/css/general.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        tr.clickable-row {
            cursor: pointer;
        }

        .card-header {
            width: 900px;
            height: 60px;
            top: 50px;
            text-align: center;
            border-radius: 50px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            /* background-color: #ff6f61; */
        }

        .card-header h2 {
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

        .add h2 {
            color: #333;
            font-weight: 700;
            margin-top: 10px;
        }

        .form-actions {
            display: inline-flex;
            align-items: center;
            margin-left: 25px;
        }


        .btn {
            width: 120px;
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

        footer {
            height: 0px;
        }

        footer p {
            margin-top: 130px;
            color: #fd9a93;
        }
        #message{
            position: absolute;
            top:50vh;
            left: 50%;
            z-index: 100;
            background: black;
            width: fit-content;
            height: fit-content;
            border-radius: 10px;
        }
    </style>
</head>

<body class="fix-header">
    <div id="message"><?php echo isset($_SESSION['message'])?$_SESSION['message']:null;$_SESSION['message']=null;?></div>
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
                        <li> <a href="all_users.php"> <span><i class="fa fa-user f-s-20 "></i></span><span>Users</span></a></li>
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
                                <li><a href="editNotice.php">Edit Noticeboard</a></li>
                                <li><a href="pastNotice.php">Past Noticeboard</a></li>
                            </ul>
                        </li>
                        <li> <a href="Cancelled_Orders.php"><i class="fa fa-shopping-cart" aria-hidden="true"></i><span>Cancelled Orders</span></a></li>
                    </ul>
                </nav>
            </div>
        </div>

        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="col-lg-12">
                    <div class="card card-outline-primary">
                        <div class="card-header">
                            <h2 class="m-b-0 text-white">Edit Notice</h2>
                        </div>
                        <div class="table-responsive m-t-40">
                            <!-- Modal for editing notice -->
                            <div class="modal fade" id="editNoticeModal" tabindex="-1" role="dialog" aria-labelledby="editNoticeModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <div class="card-header">
                                                <h2 class="modal-title" id="editNoticeModalLabel">Edit Notice</h2>
                                            </div>
                                            <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button> -->
                                        </div>
                                        <div class="modal-body">
                                            <!-- Form to edit the notice -->
                                            <form id="editNoticeForm" method="POST" action="editNotice.php">
                                                <div class="form-group">
                                                    <label for="noticeIdDisplay">ID</label>
                                                    <input type="text" class="form-control" id="noticeIdDisplay" disabled>
                                                    <input type="hidden" id="noticeId" name="noticeid">
                                                </div>
                                                <div class="form-group">
                                                    <label for="noticeTitle">Title</label>
                                                    <input type="text" class="form-control" id="noticeTitle" name="title">
                                                </div>
                                                <div class="form-group">
                                                    <label for="noticeContent">Content</label>
                                                    <textarea class="form-control" id="noticeContent" name="content"></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="startID">Start Date</label>
                                                    <input type="datetime-local" class="form-control" id="startID" name="startID">
                                                </div>
                                                <div class="form-group">
                                                    <label for="endID">End Date</label>
                                                    <input type="datetime-local" class="form-control" id="endID" name="endID">
                                                </div>
                                                <div class="form-actions">
                                                    <button type="submit" class="btn" name="save">Save changes</button>
                                                    <button type="submit" class="btn" name="delete">Delete</button>
                                                    <a href="editNotice.php" class="btn">Cancel</a>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Table for displaying notices -->
                            <table id="noticeTable" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Content</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT * FROM noticeboard WHERE NOW() BETWEEN startID AND endID";
                                    $result = $conn->query($query);
                                    if (!$result) {
                                        die("Error executing query: " . $conn->error);
                                    }
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr class='clickable-row' data-id='" . htmlspecialchars($row['noticeid'], ENT_QUOTES, 'UTF-8') . "'>";
                                            echo "<td>" . htmlspecialchars($row['noticeid'], ENT_QUOTES, 'UTF-8') . "</td>";
                                            echo "<td>" . htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') . "</td>";
                                            echo "<td>" . htmlspecialchars($row['content'], ENT_QUOTES, 'UTF-8') . "</td>";
                                            echo "<td>" . htmlspecialchars($row['startID'], ENT_QUOTES, 'UTF-8') . "</td>";
                                            echo "<td>" . htmlspecialchars($row['endID'], ENT_QUOTES, 'UTF-8') . "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='5'><center>No notices available</center></td></tr>";
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

    <script>
        $(document).ready(function() {
            // Handle row click
            $('.clickable-row').on('click', function() {
                var id = $(this).data('id');
                $('#editNoticeModal').modal('show');
                $.ajax({
                    url: 'fetch_notice.php',
                    method: 'GET',
                    data: {
                        noticeid: id
                    },
                    success: function(response) {
                        console.log('Raw Response:', response);
                        const notice = JSON.parse(response);
                        console.log('Parsed Notice:', notice);
                        $('#noticeId').val(notice.noticeid);
                        $('#noticeIdDisplay').val(notice.noticeid); // Display ID
                        $('#noticeTitle').val(notice.title);
                        $('#noticeContent').val(notice.content);
                        $('#startID').val(notice.startID);
                        $('#endID').val(notice.endID);
                    }
                });
            });

            // Handle cancel button click
            $('#cancelButton').on('click', function() {
                $('#editNoticeModal').modal('hide');
            });

            // Handle form submission for save and delete
            $('#editNoticeForm').on('submit', function(e) {
                var action = $(e.originalEvent.submitter).attr('name');

                if (action === 'delete') {
                    if (!confirm('Are you sure you want to delete this notice?')) {
                        e.preventDefault();
                    }
                }
            });

            // Handle cancel button click
            $('#cancelButton').on('click', function() {
                $('#editNoticeModal').modal('hide');
            });

            // Handle modal close
            $('#editNoticeModal').on('hidden.bs.modal', function() {
                // Do nothing
            });
        });
        setTimeout(() => {
            document.getElementById('message').style.display = 'none';
        }, 5000);
    </script>
</body>

</html>