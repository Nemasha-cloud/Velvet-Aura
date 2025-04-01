<?php
// btnCheckout.php
require_once 'config.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['log_user'])) {
    echo "<script>alert('Please login to continue'); window.location.href='login.php';</script>";
    exit();
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnCheckout'])) {
    try {
        // Get form data
        $address = trim($_POST['address'] . ', ' . $_POST['address1'] . ', ' . $_POST['address2']);
        $cus_id = $_SESSION['log_user'];
        $payment_id = $_POST['delivery'];
        $cart_id = isset($_GET['cartId']) ? $_GET['cartId'] : null;

        if (!$cart_id) {
            throw new Exception("Cart ID is missing");
        }

        // Start transaction
        $conn->begin_transaction();

        // Insert into orders table
        $sqlOrder = "INSERT INTO orders (address, order_status, cus_id, courier_id, payment_id) 
                    VALUES (?, 'pending', ?, 1, ?)";
        $stmt = $conn->prepare($sqlOrder);
        $stmt->bind_param("sis", $address, $cus_id, $payment_id);
        
        if (!$stmt->execute()) {
            throw new Exception("Failed to create order: " . $conn->error);
        }

        $order_id = $conn->insert_id;

        // Get cart items
        $sqlCart = "SELECT cd.item_code, cd.price, cd.size, cd.quantity, i.stock 
                   FROM cart_details cd 
                   JOIN item i ON cd.item_code = i.item_code 
                   WHERE cd.cart_id = ?";
        $stmt = $conn->prepare($sqlCart);
        $stmt->bind_param("i", $cart_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $item_code = $row['item_code'];
                $quantity = $row['quantity'];
                $price = $row['price'] * $quantity;
                $size = $row['size'];
                $current_stock = $row['stock'];

                // Check stock availability
                if ($current_stock < $quantity) {
                    throw new Exception("Insufficient stock for item code: $item_code");
                }

                // Insert into order_details
                $sqlOrderDetails = "INSERT INTO order_details (order_id, item_code, price, size, quantity) 
                                  VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sqlOrderDetails);
                $stmt->bind_param("iiisi", $order_id, $item_code, $price, $size, $quantity);
                
                if (!$stmt->execute()) {
                    throw new Exception("Failed to add order details: " . $conn->error);
                }

                // Update stock
                $sqlStock = "UPDATE item SET stock = stock - ? WHERE item_code = ?";
                $stmt = $conn->prepare($sqlStock);
                $stmt->bind_param("ii", $quantity, $item_code);
                
                if (!$stmt->execute()) {
                    throw new Exception("Failed to update stock: " . $conn->error);
                }
            }

            // Clear cart
            $sqlDelete = "DELETE FROM cart_details WHERE cart_id = ?";
            $stmt = $conn->prepare($sqlDelete);
            $stmt->bind_param("i", $cart_id);
            
            if (!$stmt->execute()) {
                throw new Exception("Failed to clear cart: " . $conn->error);
            }

            // Commit transaction
            $conn->commit();

            // Success message and redirect
            echo "<script>alert('Order placed successfully!'); window.location.href='cart.php';</script>";
            exit();
        } else {
            throw new Exception("No items found in cart");
        }
    } catch (Exception $e) {
        // Rollback on error
        $conn->rollback();
        echo "<script>alert('Error: " . $conn->real_escape_string($e->getMessage()) . "'); window.location.href='checkout.php?cart_id=$cart_id';</script>";
        exit();
    }
} else {
    // If accessed directly without POST
    echo "<script>alert('Invalid access method'); window.location.href='cart.php';</script>";
    exit();
}

// $conn->close();

?>