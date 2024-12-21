<?php
ob_start();
session_start();
if (!isset($_SESSION['rs_id'])) {
    header("Location: restLogin.php");
    exit();
}
if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_SANITIZE_STRING);
}

include 'C:/xampp/htdocs/UIT/config.php';
$rs_id = $_SESSION['rs_id'];


$sql = "SELECT * FROM users WHERE u_id=$id";

$cancelledOrders="SELECT COUNT(*) as cancelled_times FROM cancelled_orders WHERE u_id=$id";

$result = $conn->query($sql); // Execute query
$cancelledOrdersResult= $conn->query($cancelledOrders);
?>
<head>
<link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<link href="css/helper.css" rel="stylesheet">
<link href="css/yay.css" rel="stylesheet">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Restaurant Order Update</title>

    <style>
        button{
            width:120px;
            height:50px;
            border-radius:5px;
        }
    </style>

    <link href="css/helper.css" rel="stylesheet">
    <link href="css/ok.css" rel="stylesheet">
</head>
<body class="fix-header">
    <div id="main-wrapper" class="d-flex justify-content-center align-items-center vh-100">
        <div class="table-container w-100">
            <div class="d-flex justify-content-center align-items-center" style="height: 80vh;">
            <?php while($row = $result->fetch_assoc()) { ?>
                <!-- Order Details Table-->
                <table class="table table-bordered table-hover mb-4"style="width:70vw;margin: 30px;">
                <thead class="thead-dark">
                    <tr>
                        <th colspan="2" class="text-center">User Details</th>
                    </tr>
                </thead>
                <tbody>
                    
                        <tr>
                            <th class="text-center" scope="row">User_Id</th>
                            <td class="text-center"><?php echo htmlspecialchars($row['u_id']) ?></td>
                        </tr>
                        <tr>
                            <th class="text-center" scope="row">Username</th>
                            <td class="text-center"><?php echo htmlspecialchars($row['name']) ?></td>
                        </tr>
                        <tr>
                            <th class="text-center" scope="row">Email</th>
                            <td class="text-center"><?php echo htmlspecialchars($row['email']) ?></td>
                        </tr>
                        <tr>
                            <th class="text-center" scope="row">Phone</th>
                            <td class="text-center"><?php echo htmlspecialchars($row['phone']) ?></td>
                        </tr>
                        <tr>
                            <th class="text-center" scope="row">Cancelled_Orders</th>
                            <td class="text-center">
                                <?php 
                                    while($row = $cancelledOrdersResult->fetch_assoc()) {
                                    echo htmlspecialchars($row['cancelled_times']);
                                    }
                                ?>
                            </td>
                        </tr> 
                                       
                </tbody>      
                </table>
                
            <?php } ?>

            
            </div>
            <div class="d-flex justify-content-end mt-4"> <!-- Centered the buttons -->
                        <button class="btn btn-secondary mr-5" onclick="window.close();">
                            Back
                            <i class="bi bi-arrow-right"></i>
                        </button>       
                    </div>
        </div>
    </div>
</body>