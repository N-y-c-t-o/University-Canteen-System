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

$sql = "SELECT 
            o_id,
            o.orderNum,
            o.title,
            o.quantity,
            o.price,
            GROUP_CONCAT(o.title SEPARATOR ' , ') AS titles, 
            GROUP_CONCAT(o.quantity SEPARATOR ' , ') AS quantities, 
            GROUP_CONCAT(o.price SEPARATOR ' , ') AS prices, 
            SUM(o.price) AS total_price,  
            o.status As order_status,
            o.payment,
            o.deliOption,
            o.special,
            o.DeliTime,
            u.*
        FROM users_orders o
        LEFT JOIN users u ON o.u_id = u.u_id
        WHERE o.orderNum = '$id' and o.rs_id=$rs_id
        ORDER BY  o.o_id DESC";

$result = $conn->query($sql); // Execute query
?>
<head>
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="css/helper.css" rel="stylesheet">
    
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
        select {
            background-color: whitesmoke;
            color:#6c757d;
            width: 200px;
            height:50px; 
            border-radius: 5px;
        }
        select option {
            background-color: whitesmoke; 
            color: #6c757d;
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
                        <th colspan="2" class="text-center">Order Details</th>
                    </tr>
                </thead>
                <tbody>
                    
                        <tr>
                            <th class="text-center" scope="row">Order Number</th>
                            <td class="text-center"><?php echo htmlspecialchars($row['orderNum']) ?></td>
                        </tr>
                        <tr>
                            <th class="text-center" scope="row">Titles</th>
                            <td class="text-center"><?php echo htmlspecialchars($row['titles']) ?></td>
                        </tr>
                        <tr>
                            <th class="text-center" scope="row">Quantities</th>
                            <td class="text-center"><?php echo htmlspecialchars($row['quantities']) ?></td>
                        </tr>
                        <tr>
                            <th class="text-center" scope="row">Prices</th>
                            <td class="text-center"><?php echo htmlspecialchars($row['prices'])." ks" ?></td>
                        </tr>
                        <tr>
                            <th class="text-center" scope="row">Total_price</th>
                            <td class="text-center"><?php echo htmlspecialchars($row['total_price']) ." ks"?></td>
                        </tr>
                        <tr>
                            <th class="text-center" scope="row">Status</th>
                            <td class="text-center">
                                <?php 
                                    //status
                                    switch(htmlspecialchars($row['order_status'])){
                                        case "Pending":
                                            echo '<i class="fas fa-hourglass-half"></i><a href="#" class="text-warning">Pending</a>';
                                            break;
                                        case "Way":
                                            echo '<i class="bi bi-gear-fill"></i><a href="#" class="text-info">On the Way</a>';
                                            break;
                                        case "Cancelled":
                                            echo '<i class="fas fa-times-circle"></i><a href="#" class="text-danger">Cancelled</a>';
                                            $orderItemsSql = "SELECT title, quantity, price FROM users_orders WHERE orderNum = '{$row['orderNum']}' and rs_id = '{$rs_id}'";
                                            $orderItemsResult = $conn->query($orderItemsSql);

                                            while ($orderItem = $orderItemsResult->fetch_assoc()) {
                                                $insertSql = "INSERT INTO cancelled_orders (rs_id, u_id, username, orderNum, title, quantity, price,total_price) 
                                                            VALUES ('$rs_id', '{$row['u_id']}', '{$row['name']}', '{$row['orderNum']}', '{$orderItem['title']}', '{$orderItem['quantity']}', '{$orderItem['price']}', '{$row['total_price']}')";
                                                $conn->query($insertSql);
                                            }
                                            break;
                                        case "Delivered";
                                            echo '<i class="fas fa-check-circle"></i><a href="#" class="text-success">Delivered</a>';
                                            $orderItemsSql = "SELECT title, quantity, price FROM users_orders WHERE orderNum = '{$row['orderNum']}'and rs_id = '{$rs_id}'";
                                            $orderItemsResult = $conn->query($orderItemsSql);

                                            while ($orderItem = $orderItemsResult->fetch_assoc()) {
                                                $insertSql = "INSERT INTO success_orders (rs_id, u_id, username, orderNum, title, quantity, price,total_price) 
                                                            VALUES ('$rs_id', '{$row['u_id']}', '{$row['name']}', '{$row['orderNum']}', '{$orderItem['title']}', '{$orderItem['quantity']}', '{$orderItem['price']}', '{$row['total_price']}')";
                                                $conn->query($insertSql);
                                            } 
                                            break;
                                    }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-center" scope="row">Payment</th>
                            <td class="text-center"><?php echo htmlspecialchars($row['payment']) ?></td>
                        </tr>
                        <tr>
                            <th class="text-center" scope="row">Delivery Option</th>
                            <td class="text-center"><?php echo htmlspecialchars($row['deliOption']) ?></td>
                        </tr>
                        <tr>
                            <th class="text-center" scope="row">Special Instruction</th>
                            <td class="text-center"><?php echo htmlspecialchars($row['special']) ?></td>
                        </tr>
                        <tr>
                            <th class="text-center" scope="row">Delivery Time</th>
                            <td class="text-center"><?php echo htmlspecialchars($row['DeliTime']) ?></td>
                        </tr>
                        
                    
                </tbody>      
                </table>
                
            <?php } ?>

            
            </div>
            <div class="d-flex justify-content-center mt-4"> <!-- Centered the buttons -->
                <form action=""method="POST">
                        <select name="selectBox" idd="exampleSelect">
                                <option value="1">Pending</option>
                                <option value="2">On the Way</option>
                                <option value="3">Cancelled</option>
                                <option value="4">Delivered</option>
                        </select>
                        <button type= "submit" class="btn btn-info mr-2 ml-2">
                        <i class="bi bi-hand-thumbs-up-fill"></i>
                            Submit
                        </button>
                </form>
                        <button class="btn btn-secondary mr-2 ml-2" onclick="window.close();">
                            Back
                            <i class="bi bi-arrow-right"></i>
                        </button>
                        
                    </div>
        </div>
    </div>
</body>

<?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $selectedOption = $_POST['selectBox'];
        
            if ($selectedOption == "1") {
                $sql = "UPDATE users_orders SET status='Pending' WHERE orderNum='$id' and rs_id=$rs_id";
            } elseif ($selectedOption == "2") {
                $sql = "UPDATE users_orders SET status='Way' WHERE orderNum='$id' and rs_id=$rs_id";
            } elseif ($selectedOption == "3") {
                $sql = "UPDATE users_orders SET status='Cancelled' WHERE orderNum='$id' and rs_id=$rs_id";
            } elseif ($selectedOption == "4") {
                $sql = "UPDATE users_orders SET status='Delivered' WHERE orderNum='$id' and rs_id=$rs_id";
            }
        
            if ($conn->query($sql) === TRUE) {
                // Use header() to redirect to avoid resubmission on refresh
                echo "<script>alert('Order Status is successfully Updated');</script>";
                header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $id); // Redirect to the same page with the order ID in the query string
                exit();
            } else {
                echo "<script>alert('Error updating record: " . $conn->error . "');</script>";
            }
        
            $conn->close();
        }
ob_end_flush();      
?>
