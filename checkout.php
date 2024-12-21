<!DOCTYPE html>
<html lang="en">

    <?php
        
        include 'config.php'; // Make sure to include your database connection setup
        include_once 'product-action.php';
error_reporting(0);
session_start();
        $name = "Guest"; // Default value
        $phone = "Not Available"; // Default value
        $email = "Not Available"; // Default value
        $profile = "profile.jpg"; // Default profile picture

        if (isset($_SESSION['SESSION_EMAIL'])) {
            // Fetch the username and phone number from the database
            $email = $_SESSION['SESSION_EMAIL'];
            $query = mysqli_query($conn, "SELECT name, phone, email, profile FROM users WHERE email='$email'");
            $row = mysqli_fetch_assoc($query);

            if ($row) {
                $name = $row['name'];
                $phone = $row['phone'];
                $email = $row['email'];
                $profile = $row['profile'];
            }
        }
        function function_alert() { 
            echo "<script>alert('Thank you. Your Order has been placed!');</script>"; 
            echo "<script>window.location.replace('your_orders.php');</script>"; 
        }
        
        if(empty($_SESSION["user_id"])) {
            header('location:login.php');
        } else {
            $item_total = 0; // Initialize item_total
            foreach ($_SESSION["cart_item"] as $item) {
                // Calculate the item total
                $item_total += ($item["price"] * $item["quantity"]);
            }
        
            // Check if form is submitted
            if (isset($_POST['submit'])) {
                $deliOption = "";
                $selectedTime = $_POST['DeliTime']; // Get selected time from hidden input
                $specialInstructions = $_POST['specialInstructions']; // Get special instructions
            
                if ($_POST['mod'] == "PickUp") {
                    $deliOption = "Pick Up";
                } elseif ($_POST['mod'] == "delivery") {
                    $building = $_POST['building'];
                    $floor = $_POST['floor'];
                    $room = $_POST['room'];
                    $deliOption = "Building $building, Floor $floor, Room $room";
                }
            
                // Get the selected payment method
                $paymentMethod = isset($_POST['payment']) ? $_POST['payment'] : '';
            
                if ($paymentMethod === "Digital") {
                    $digitalPaymentMethod = isset($_POST['digitalPayment']) ? $_POST['digitalPayment'] : '';
                    if (!empty($digitalPaymentMethod)) {
                        $paymentMethod = $digitalPaymentMethod;
                    }
                }
            
                // Generate the order number based on the current timestamp
                $orderNum = "ORDER_" . time();
            
                foreach ($_SESSION["cart_item"] as $item) {
                    // Calculate the total price for the current item
                    $totalPrice = $item["quantity"] * $item["price"];
                
                    // Prepare the SQL statement
                    $SQL = "INSERT INTO users_orders (u_id, rs_id, d_id, title, quantity, price, deliOption, orderNum, DeliTime, payment, special) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                
                    $stmt = $conn->prepare($SQL);
                    if (!$stmt) {
                        error_log("Error preparing statement: " . $conn->error);
                        exit;
                    }
                
                    // Bind parameters with the calculated total price
                    $stmt->bind_param('iiissssssss', $_SESSION["user_id"], $item["rs_id"], $item["d_id"], $item["title"], $item["quantity"], $totalPrice, $deliOption, $orderNum, $selectedTime, $paymentMethod, $specialInstructions);
                    $stmt->execute();
                }
                
                foreach ($_SESSION["cart_item"] as $item) {
                    $productId = $item['d_id'];
                    $quantityOrdered = $item['quantity'];
                    $rs_id=$item['rs_id'];
            
                    // Dynamically set the table name
                    $menu_table = "menu_rest" . $rs_id;
            
                    // Prepare the SQL to update the stock
                    $stmt = $conn->prepare("UPDATE $menu_table SET stock = stock - ? WHERE d_id = ?");
                    if (!$stmt) {
                        error_log("Error preparing stock update statement: " . $conn->error);
                        exit;
                    }
            
                    $stmt->bind_param('ii', $quantityOrdered, $productId);
                    if (!$stmt->execute()) {
                        error_log("Error updating stock for dish ID $productId: " . $stmt->error);
                    }
                }
                // Clear the cart after processing all items
                unset($_SESSION["cart_item"]);
                unset($_SESSION["cart_categories"]);
                unset($_SESSION["rest_id"]);
                $success = "Thank you. Your order has been placed!";
                function_alert();
            }
                
            
    ?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width-device-width, initial-scale=1.0">
    <link rel="icon" href="#">
    <title>UIT Cafeteria</title>
    <!-- <link href="css/animate.css" rel="stylesheet"> -->
    <!-- <link href="css/index.css" rel="stylesheet"> -->
    
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/homePage.css" rel="stylesheet">
    <!-- Box Icons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
   .elegant-card {
    border-radius: 20px;
    padding: 30px;
    background-color: #fff5f8;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.success-message {
    color: #28a745;
    font-weight: bold;
    font-size: 1.2em;
    text-align: center;
}

.coquette-widget {
    background-color: #fdf1f4;
    border-radius: 15px;
    padding: 20px;
}

.cart-title {
    font-family: 'Poppins', sans-serif;
    font-size: 1.5em;
    font-weight: bold;
    color: #e67e99;
}

.time-selection {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 15px;
}

.time-btn {
    background-color: #ffc4d3;
    border: none;
    padding: 5px 10px;
    border-radius: 8px;
    cursor: pointer;
}

.payment-options .custom-radio .custom-control-description {
    font-family: 'Poppins', sans-serif;
    font-size: 1em;
    color: #9b6d7d;
}

.select-field, .textarea-field {
    border: 1px solid #f7c8d7;
    border-radius: 10px;
    padding: 8px;
    font-family: 'Poppins', sans-serif;
}

.order-btn {
    background-color: #ff6390;
    color: white;
    border: none;
    padding: 10px 20px;
    font-size: 1.2em;
    border-radius: 15px;
    cursor: pointer;
}

.order-btn:hover {
    background-color: #e65580;
}
.container{
    padding-top:0px;
}
#timeDisplay {
    font-size: 18px;
    color: #ff7b6e; /* Cute pink */
    font-weight: bold;
}

#decrementButton, #incrementButton {
    background-color: #fff; /* Soft pink background */
    color: #333;
    border: none;
    border-radius: 50px;
    font-size: 16px;
    padding: 8px 15px;
    cursor: pointer;
}

#decrementButton:hover, #incrementButton:hover {
    color: #ff7b6e; /* Darker pink on hover */
}

p.text-xs-center span {
    font-size: 18px;
    color: #fbb65e; /* Pinkish text */
    font-weight: 500;
}

.home {
    text-align: center;
    padding: 0px 100px;
    background-color: transparent;
    animation: fadeIn 2s ease-in-out;
    color: var(--hover-color);
}
.header {
    position: relative;
    left: 250px;
    background-color: var(--panel-color);
    height: 200vh;
    width: calc(100% - 200px);
    padding: 0px 14px;
    font-size: 20px;
    transition: var(--tran-05);
}

.bb:hover{
    background: #fbb65e;
}
</style>
</head>

<body> 
    <nav class="close">
        <div class="logo-name">
            <div class="logo-image">
            <img src="photos/<?php echo $profile; ?>" alt="Profile Picture">
            </div>

            <span class="logo_name">Account</span>
        </div>

        <div class="menu-items">
            <ul class="nav-links">
                <li><a href="#" id="nameLink">
                <i class='bx bx-user'></i>
                    <span class="link-name"><?php echo $name; ?></span>
                </a> 
                </li>
                <li><a href="#"  id="emailLink">
                    <i class='bx bx-envelope'></i>
                    <span class="link-name"><?php echo $email; ?></span>
                </a>
                </li>
                <li><a href="#"  id="phoneLink">
                    <i class='bx bx-phone'></i>
                    <span class="link-name"><?php echo $phone; ?></span>
                </a>
                </li>
                <li><a href="editProfile.php">
                    <i class='bx bx-edit'></i>
                    <span class="link-name">Edit Profile</span>
                </a></li>
                
                <?php if (isset($_SESSION['SESSION_EMAIL'])): ?>
                    <li><a href="reset-password.php">
                        <i class='bx bx-lock-open'></i>
                        <span class="link-name">Reset Password</span>
                    </a></li>
                <?php else: ?>
                    <li><a href="forgot-password.php">
                        <i class='bx bx-lock'></i>
                        <span class="link-name">Forgot Password</span>
                    </a></li>
                <?php endif; ?>

                <li><a href="your_orders.php">
                    <i class='bx bx-history'></i>
                    <span class="link-name">History</span>
                </a></li>
            </ul>

            <ul class="logout-mode">
                <?php if (isset($_SESSION['SESSION_EMAIL'])): ?>
                    <li><a href="logout.php">
                        <i class='bx bx-log-out'></i>
                        <span class="link-name">Logout</span>
                    </a></li>
                <?php else: ?>
                    <li><a href="login.php">
                        <i class='bx bx-log-in'></i>
                        <span class="link-name">Log in</span>
                    </a></li>
                <?php endif; ?>

                <li class="mode">
                    <a href="#">
                    <i class='bx bx-moon'></i>
                    <span class="link-name">Dark Mode</span>
                </a>
            
                <div class="mode-toggle">
                    <span class="switch"></span>
                </div>
            </li>
            </ul>
        </div>
    </nav>

    <section class="header">
        <div class="top"> 
            <i class='bx bx-menu sidebar-toggle'></i>

            <div class="navbar">
                <a href="homePage.php">Home</a>
                <a href="restaurant.php">Restaurant</a>
                <a href="contact.php">Contact</a>
                <a href="about.php">About</a>
                
            <a href="index.php"><img src="photos/logo1.png" alt="" ></a>
            
            </div>
                    
        </div>

        <div style="border-radius: 20px; margin-top: 50px; padding:40px;">
    <section class="checkout">
        <div class="page-wrapper">
            <div class="container">
                <span style="color: #28a745; font-weight: bold; font-size: 1.2em; text-align: center;">
                    <?php echo $success; ?>
                </span>
            </div>

            <div class="container" style="margin-top: 30px;">
                <form action="" method="post">
                    <div style="background-color: #fff; border-radius: 15px; padding: 20px; width:600px; margin-left:220px;">
                        <div style="padding: 20px;">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div style="margin-bottom: 20px;">
                                        <h4 style="font-family: 'Poppins', sans-serif; font-size: 1.2em; text-align: center; font-weight: bold; color: #ff7b6e;">Cart Summary</h4>
                                    </div>
                                    <div>
                                        <table style="width: 100%; margin-bottom: 20px;">
                                            <tbody style="font-size:16px">
                                                <tr>
                                                    <td>Cart Subtotal</td>
                                                    <td style="text-align:right;"><?php echo $item_total." ks"; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Total</strong></td>
                                                    <td style="text-align:right;"><strong id="totalAmount"><?php echo isset($item_total) ? $item_total . " ks" : "0 ks"; ?></strong></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <div style="display: flex; align-items: center; justify-content: center; margin-top: 15px;">
                                        <table>
                            <tr>
        <input type="hidden" id="deliTime" name="DeliTime" value="">
        <p class="text-xs-center" style="display: flex; margin-left: -50px; align-items: center; justify-content: center;"> 
            <span>Choose the time for food delivery/pick up:</span>
            <button type="button" id="decrementButton" class="btn btn-secondary mx-2">[-]</button>
            <span id="timeDisplay"></span>
            <button type="button" id="incrementButton" class="btn btn-secondary mx-2">[+]</button>
        </p>
                                    </tr>

                                </tbody>
                            </table>
                                        </div>
                                    </div>
                                    <h4 style="font-family: 'Poppins', sans-serif; font-size: 1em; color: #fbb65e; margin-top: 20px;">Choose Payment Method</h4>
                                    <ul style="list-style: none; padding: 0; margin-top: 10px;">
                                        <li>
                                            <label style="display: block; font-size: 16px; margin-bottom: 10px;">
                                                <input name="payment" id="radioCash" value="Cash" type="radio" onchange="togglePaymentDropdown()"> Cash
                                            </label>
                                        </li>
                                        <li>
                                            <label style="display: block; font-size: 16px; margin-bottom: 10px;">
                                                <input name="payment" id="radioDigital" value="Digital" type="radio" onchange="togglePaymentDropdown()"> Digital
                                            </label>
                                        </li>
                                    </ul>

                                    <div id="digitalOptions" style="display: none; margin-top: 10px;">
    <label style="display: inline-flex; align-items: center; font-size: 16px; margin-right: 20px; cursor: pointer;">
        <input name="digitalPayment" id="radioKBZ" value="KBZ" type="radio" style="margin-right: 5px;"> KBZ
    </label>
    <label style="display: inline-flex; align-items: center; font-size: 16px; margin-right: 20px; cursor: pointer;">
        <input name="digitalPayment" id="radioAYA" value="AYA" type="radio" style="margin-right: 5px;"> AYA
    </label>
    <label style="display: inline-flex; align-items: center; font-size: 16px; cursor: pointer;">
        <input name="digitalPayment" id="radioWAVE" value="WAVE" type="radio" style="margin-right: 5px;"> WAVE
    </label>
</div>

                                    <h4 style="font-family: 'Poppins', sans-serif; font-size: 1em; color: #fbb65e; margin-top: 20px;">Choose Pick Up/Delivery</h4>
                                    <ul style="list-style: none; padding: 0;">
                                        <li>
                                            <label style="display: block; font-size: 16px; margin-bottom: 10px;">
                                                <input name="mod" id="radioPickup" value="PickUp" type="radio" onchange="toggleDropdowns()"> Pick Up
                                            </label>
                                        </li>
                                        <li>
                                            <label style="display: block; font-size: 16px; margin-bottom: 10px;">
                                                <input name="mod" id="radioDelivery" value="delivery" type="radio" onchange="toggleDropdowns()"> Delivery
                                            </label>
                                        </li>
                                    </ul>

                                    <div id="deliveryOptions" style="display: none;">
                                        <table style="width: 100%; font-size: 16px; margin-bottom: 20px;">
                                            <tr>
                                                <td>Delivery Charges</td>
                                                <td>500 ks</td>
                                            </tr>
                                        </table>
                                        <div style="display: flex; gap: 10px; font-size: 16px; margin-bottom: 20px;">
                                            <div style="flex: 1;">
                                                <label for="building">Building:</label><br><br>
                                                <select id="building" name="building" style="width: 100%; padding: 8px; font-size: 16px; border-radius: 10px; border: 1px solid #f7c8d7;">
                                                    <option>1</option>
                                                    <option>2</option>
                                                    <option>3</option>
                                                </select>
                                            </div>
                                            <div style="flex: 1;">
                                                <label for="floor">Floor:</label><br><br>
                                                <select id="floor" name="floor" style="width: 100%; padding: 8px; font-size: 16px; border-radius: 10px; border: 1px solid #f7c8d7;">
                                                    <option>1</option>
                                                    <option>2</option>
                                                    <option>3</option>
                                                    <option>4</option>
                                                    <option>5</option>
                                                    <option>6</option>
                                                </select>
                                            </div>
                                            <div style="flex: 1;">
                                                <label for="room">Room:</label><br><br>
                                                <select id="room" name="room" style="width: 100%; padding: 8px; border-radius: 10px; border: 1px solid #f7c8d7;">
                                                    <option>1</option>
                                                    <option>2</option>
                                                    <option>3</option>
                                                    <option>4</option>
                                                    <option>5</option>
                                                    <option>6</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div>
                                            <label for="specialInstructions" style="font-size: 16px;">Special Instructions:</label><br><br>
                                            <textarea id="specialInstructions" name="specialInstructions" style="width: 100%; padding: 8px; font-size: 16px; border-radius: 10px; border: 1px solid #f7c8d7;" rows="3"></textarea>
                                        </div>
                                    </div>
                                    
                                    <p style="text-align: center; font-size: 16px; margin-top: 20px;">
                                        <input type="submit" id="orderButton" onclick="return validateAndConfirmOrder();" name="submit" style="background-color: #ff7b6e; color: white; border: none; padding: 10px 10px; font-size: 1.2em; border-radius: 10px; cursor: pointer;" value="Order Now">
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>


<script type="text/javascript">
        function togglePaymentDropdown() {
        var selectedPayment = document.querySelector('input[name="payment"]:checked');
        var digitalOptionsContainer = document.getElementById("digitalOptions");

        if (selectedPayment && selectedPayment.value === "Digital") {
            digitalOptionsContainer.style.display = "block";
        } else {
            digitalOptionsContainer.style.display = "none";
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        togglePaymentDropdown();
    });
function validateAndConfirmOrder() {
    var pickup = document.getElementById('radioPickup').checked;
    var delivery = document.getElementById('radioDelivery').checked;
    var cash = document.getElementById('radioCash').checked;
    var KBZ = document.getElementById('radioKBZ').checked;   
    var AYA = document.getElementById('radioAYA').checked; 
    var WAVE = document.getElementById('radioWAVE').checked; 

    if (!cash && !AYA && !KBZ && !WAVE) {
        alert("Please select either cash or Digital to proceed.");
        return false; // Prevent form submission
    }

    if (!pickup && !delivery) {
        alert("Please select either Pickup or Delivery to proceed.");
        return false; // Prevent form submission
    }

    return confirm('Do you want to confirm the order?');
}
let selectedTime = new Date();
const maxTime = new Date(selectedTime.getTime() + 3 * 60 * 60 * 1000); // Current time + 3 hours
const maxUTC = new Date(selectedTime);
maxUTC.setUTCHours(15, 50, 0, 0); // 15:50 UTC

function padZero(num) {
    return num < 10 ? '0' + num : num;
}

function updateDisplay() {
    // Calculate the new hours and minutes after adding 6 hours and 30 minutes
    let hours = selectedTime.getUTCHours() + 6; // Add 6 hours
    let minutes = selectedTime.getUTCMinutes() + 30; // Add 30 minutes

    // Handle the overflow of minutes into hours
    if (minutes >= 60) {
        hours += Math.floor(minutes / 60); // Increment hours by the number of full hours in minutes
        minutes = minutes % 60; // Get the remaining minutes
    }

    // Ensure hours wrap around correctly (e.g., 24:xx -> 00:xx)
    if (hours >= 24) {
        hours = hours % 24;
    }

    // Format and display the time
    document.getElementById('timeDisplay').textContent = padZero(hours) + ':' + padZero(minutes) ;
}

function updateDeliTimeField() {
    // Add 6 hours and 30 minutes to the selectedTime
    const newTime = new Date(selectedTime.getTime() + (6 * 60 + 30) * 60 * 1000);
    const utcTime = newTime.toISOString().slice(0, 19).replace('T', ' '); // Format the new time
    document.getElementById('deliTime').value = utcTime;
}

function modifyTime(increment) {
    selectedTime = new Date(selectedTime.getTime() + increment * 60 * 1000);

    if (selectedTime >= new Date() && selectedTime <= maxTime && selectedTime <= maxUTC) {
        // Do nothing, selectedTime is already updated
    } else if (selectedTime > maxTime || selectedTime > maxUTC) {
        selectedTime = new Date(Math.min(maxTime.getTime(), maxUTC.getTime()));
    } else {
        selectedTime = new Date(); // Reset to current time if the decrement goes below current time
    }

    updateDisplay();
    updateDeliTimeField();
}

// Initial time setup
updateDisplay();
updateDeliTimeField();

// Button event listeners
document.getElementById('incrementButton').addEventListener('click', function() {
    modifyTime(30); // Increment time by 30 minutes
});

document.getElementById('decrementButton').addEventListener('click', function() {
    modifyTime(-30); // Decrement time by 30 minutes
});

function checkTimeAndToggleButton() {
    const currentTime = new Date();
    const currentUTCHours = currentTime.getUTCHours();
    const currentUTCMinutes = currentTime.getUTCMinutes();

    const disableStartHour = 10;
    const disableStartMinute = 30;
    const disableEndHour = 11; // Change "Order Now" time here
    const disableEndMinute = 0;

    const disableStartTime = disableStartHour * 60 + disableStartMinute;
    const disableEndTime = disableEndHour * 60 + disableEndMinute;
    const currentTimeInMinutes = currentUTCHours * 60 + currentUTCMinutes;

    const orderButton = document.getElementById('orderButton');

    if (currentTimeInMinutes >= disableStartTime && currentTimeInMinutes <= disableEndTime) {
        orderButton.disabled = true;
    } else {
        orderButton.disabled = false;
    }
}
checkTimeAndToggleButton();
setInterval(checkTimeAndToggleButton, 60000); // Check every minute

    //DON'T TOUCH THIS

    function toggleDropdowns() {
    var selectedOption = document.querySelector('input[name="mod"]:checked').value;
    var deliveryOptionsContainer = document.getElementById("deliveryOptions");
    var itemTotal = <?php echo $item_total; ?>;
    var totalElement = document.getElementById("totalAmount");

    if (selectedOption === "delivery") {
        deliveryOptionsContainer.style.display = "block";
        totalElement.textContent = (itemTotal + 500) + " ks"; // Add 500 for delivery
    } else {
        deliveryOptionsContainer.style.display = "none";
        totalElement.textContent = itemTotal + " ks"; // Just the item total
    }
}

// Call the function on page load to set the initial state
toggleDropdowns();

</script>
        </div>
           
       </div>

        <footer>
            <div class="foot">
                <div class="sec">
                    <h2>About Us</h2><br>
                    <p>Our mission is to enhance the dining experience for our customers by providing 
                        a user-friendly platform that simplifies food ordering. We aim to connect food lovers 
                        with their favorite restaurants while ensuring a seamless and efficient ordering process.</p>
                    
                        <ul  class="sci">
                            <li calss="facebook"><a href="#"><i class='bx bxl-facebook'></i></a></li>
                            <li class="instagram"><a href="#"><i class='bx bxl-instagram'></i></a></li>
                            <li class="tiktok"><a href="#"><i class='bx bxl-tiktok'></i></a></li>
                            <li class="viber"><a href="#"><i class="fa-brands fa-viber"></i></a></li>
                        </ul>
                </div>

                <div class="quicklink">
                    <h2>Members</h2><br>
                    <ul>
                        <li>Kyi Sin Shun Lett</li>
                        <li>Myat Shwe Yi Moe</li>
                        <li>Hnin Shwe Yi Wint</li>
                        <li>Kyaw Min Wai</li>
                        <li>Aung Khant Kyaw</li>
                    </ul>
                </div>      

                <div class="aboutus">
                    <h2>Admin</h2><br>            
                        <ul class="admin">
                            <li>
                                <span><i class='bx bxs-phone-call'></i></span>
                                <p><a href="tel:+1234567890">+1 987 654 3210</a></p>
                            </li>
                            <li>
                                <span><i class='bx bxs-envelope' ></i></span>
                                <p><a href="mailto:admin@uit.edu.mm">admin@xyz.edu.mm</a></p>
                            </li>
                            <li>
                                <span><i class='bx bxs-log-in'></i></span>
                                <p><a href="#" class="line">Admin & Owner Login</a></p>
                            </li>
                        </ul>
                </div>

                <div class="contact">
                    <h2>Contact Us</h2><br>
                    <ul class="info">
                        <li>
                            <span><i class='bx bxs-phone-call'></i></span>
                            <p><a href="tel:+1234567890">+1 123 456 7890</a></p>
                        </li>
                        <li>
                            <span><i class='bx bxs-envelope' ></i></span>
                            <p><a href="mailto:knowmore@uit.edu.mm">university@xyz.edu.mm</a></p>
                        </li>
                    <ul>
                </div>
            </div>
         </footer>
        <div class = "copyrightText">
            <p>
                © 2024 University Canteen. All Rights Reserved.
            </p>
        </div> 

        
    </section>

    
    <script src="js/sideBar2.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/tether.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/animsition.min.js"></script>
    <script src="js/bootstrap-slider.min.js"></script>
    <script src="js/jquery.isotope.min.js"></script>
    <script src="js/headroom.js"></script>
    <script src="js/foodpicky.min.js"></script>
</body>
</html>
<?php
}
?>