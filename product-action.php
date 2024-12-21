<?php
session_start(); // Ensure session is started

// Initialize or update the categories array
if (!isset($_SESSION["cart_categories"])) {
    $_SESSION["cart_categories"] = [];
}

if(!empty($_GET["action"])) {
    $productId = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : '';
    $quantity = isset($_POST['quantity']) ? htmlspecialchars($_POST['quantity']) : '';
    $res_id = isset($_GET['rs_id']) ? htmlspecialchars($_GET['rs_id']) : '';
    
    // Dynamically set the table name
    $menu_table = "menu_rest" . $res_id;
    switch($_GET["action"]) {
        case "add":
            if (!empty($quantity)) {
                $stmt = $conn->prepare("SELECT * FROM $menu_table WHERE d_id = ?");
                $stmt->bind_param('i', $productId);
                $stmt->execute();
                $productDetails = $stmt->get_result()->fetch_object();
        
                if ($productDetails) {
                    $stock = $productDetails->stock;
        
                    // Check if the quantity being added exceeds stock
                    if ($quantity > $stock) {
                        echo '<script>alert("Quantity exceeds available stock. Only ' . $stock . ' of the stock is available right now."); window.location.href="dishes.php?rs_id='.$res_id.'";</script>';
                        exit();
                    }
                    
                    if ($quantity <= 0) {
                        echo '<script>alert("Quantity must be greater than zero."); window.location.href="dishes.php?rs_id='.$res_id.'";</script>';
                        exit();
                    }
                    $itemArray = array($productDetails->d_id => array(
                        'title' => $productDetails->title,
                        'd_id' => $productDetails->d_id,
                        'rs_id'=> $productDetails->rs_id,
                        'quantity' => $quantity,
                        'price' => $productDetails->price,
                        'category' => $productDetails->time_category,
                        'stock' => $productDetails->stock
                    ));
        
                    if (!empty($_SESSION["cart_item"])) {
                        if (in_array($productDetails->d_id, array_keys($_SESSION["cart_item"]))) {
                            foreach ($_SESSION["cart_item"] as $k => $v) {
                                if ($productDetails->d_id == $k) {
                                    $currentQuantity = $_SESSION["cart_item"][$k]["quantity"];
                                    // Check if adding the current quantity exceeds the stock
                                    if (($currentQuantity + $quantity) > $stock) {
                                        echo '<script>alert("Adding this quantity exceeds available stock."); window.location.href="dishes.php?rs_id=' . $res_id . '";</script>';
                                        exit();
                                    }
                                    
                                    $_SESSION["cart_item"][$k]["quantity"] += $quantity;
                                }
                            }
                        } else {
                            $_SESSION["cart_item"] = $_SESSION["cart_item"] + $itemArray;
                        }
                    } else {
                        $_SESSION["cart_item"] = $itemArray;
                    }
                    if (!in_array($productDetails->time_category, $_SESSION["cart_categories"])) {  
                        $_SESSION["cart_categories"][] = $productDetails->time_category;  
                    }  //this
                } else {
                    echo '<script>alert("Product not found."); window.location.href="dishes.php?rs_id=' . $res_id . '";</script>';
                }
            }
            break;

            case "remove":
                if (!empty($_SESSION["cart_item"])) {
                    foreach ($_SESSION["cart_item"] as $k => $v) {
                        if ($productId == $v['d_id']) {
                            unset($_SESSION["cart_item"][$k]);
            
                            // Check if this was the last item of the removed item's category
                            $category = $v['category'];  //this
                            $categoryStillExists = false;  //this
            
                            foreach ($_SESSION["cart_item"] as $remainingItem) {  //this
                                if ($remainingItem['category'] == $category) {  //this
                                    $categoryStillExists = true;  //this
                                    break;  //this
                                }  //this
                            }  //this
            
                            // If no other items of the same category exist, remove the category from the session
                            if (!$categoryStillExists) {  //this
                                $_SESSION["cart_categories"] = array_diff($_SESSION["cart_categories"], array($category));  //this
                            }  //this
                        }
                    }
                }
                break;

        case "empty":
            unset($_SESSION["cart_item"]);
            unset($_SESSION["cart_categories"]);
            break;

				case "check":
					// Validate categories
					if (count($_SESSION["cart_categories"]) > 1) {
						 $_SESSION['error_message'] = "You can only order from one category at a time. Please adjust your cart.";
                header("Location: dishes.php?res_id=" . $_GET['rs_id']);
                exit;
					} else {
                header("Location: checkout.php");
                exit;
					}
					break;
    }
}
?>
