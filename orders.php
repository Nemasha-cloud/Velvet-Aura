<?php 
require 'config.php';

// Check if admin is logged in (redundant since adminHeader.php already checks, but kept for clarity)
if (!isset($_SESSION['log_user'])) {
    header('Location: adminLog.php');
    exit();
} else {
    $adminName = $_SESSION['log_name'];
}

include 'adminHeader.php'; // Include the refactored header
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin Orders - Online Fashion</title>
    <link rel="stylesheet" href="src/css/adminHeader.css" type="text/css">
    <script src="https://kit.fontawesome.com/8291a47a9c.js" crossorigin="anonymous"></script>
    <link rel="shortcut icon" type="image/jpg" href="src/img/favi.jpg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f5f5f5; /* Light background */
        }
        /* Header styles (from adminHeader.css, included here for clarity) */
        header {
            width: 100%;
            height: 100px;
            background-color: black;
        }
        header .container {
            height: 100px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header .side-head {
            margin-left: 20px;
        }
        header .side-head span {
            color: white;
            font-size: 25px;
            font-weight: 700;
        }
        header .nav ul {
            display: flex;
        }
        header .nav li {
            list-style: none;
            margin-right: 40px;
        }
        header .nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 20px;
            display: flex;
            align-items: center;
        }
        header .nav ul li a i {
            margin-right: 5px;
        }
        header .nav ul li a:hover {
            color: #ddd;
        }
        /* Table container */
        .table-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .table-wrapper {
            overflow-x: auto;
            overflow-y: auto;
            max-height: 500px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1000px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f2f2f2;
            position: sticky;
            top: 0;
            z-index: 1;
            font-weight: bold;
        }
        td {
            font-size: 0.9em;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .item-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }
        .item-table th, .item-table td {
            border: 1px solid #eee;
            padding: 8px;
        }
        .item-table th {
            background-color: #fafafa;
            position: static;
        }
        /* Style for the status button */
        .status-btn {
            padding: 5px 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 0.9em;
        }
        .status-btn:hover {
            background-color: #0056b3;
        }
        .status-btn:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }
        @media (max-width: 768px) {
            .table-wrapper {
                max-height: 400px;
            }
            th, td {
                padding: 6px;
            }
            header .container {
                flex-direction: column;
                height: auto;
                padding: 10px;
            }
            header .side-head {
                margin-left: 0;
                text-align: center;
            }
            header .nav ul {
                flex-wrap: wrap;
                justify-content: center;
            }
            header .nav li {
                margin: 5px 10px;
            }
        }
    </style>
</head>
<body>
    <div class="table-container">
        <h2>Order Details</h2>
        <div class="table-wrapper">
            <?php
            // Query to fetch all orders with related customer, courier, and payment info
            $sql = "SELECT o.order_id, o.address, o.order_status, 
                           c.name AS customer_name, 
                           cs.courier_name, 
                           pm.payment_method
                    FROM orders o
                    JOIN customer c ON o.cus_id = c.cus_id
                    JOIN courier_service cs ON o.courier_id = cs.courier_id
                    JOIN payment_method pm ON o.payment_id = pm.payment_id
                    ORDER BY o.order_id DESC";
            
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                echo "<table>";
                echo "<tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Address</th>
                        <th>Status</th>
                        <th>Courier</th>
                        <th>Payment Method</th>
                        <th>Items</th>
                      </tr>";

                while ($row = $result->fetch_assoc()) {
                    $order_id = $row['order_id'];

                    // Fetch items for this order
                    $item_sql = "SELECT od.item_code, od.price, od.size, od.quantity, i.name
                                 FROM order_details od
                                 JOIN item i ON od.item_code = i.item_code
                                 WHERE od.order_id = '$order_id'";
                    $item_result = $conn->query($item_sql);

                    echo "<tr>";
                    echo "<td>" . $order_id . "</td>";
                    echo "<td>" . htmlspecialchars($row['customer_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['address']) . "</td>";
                    // Status column with button for "pending" orders
                    echo "<td class='status-cell' data-order-id='$order_id'>";
                    if ($row['order_status'] === 'pending') {
                        echo "<button class='status-btn' onclick='updateOrderStatus($order_id)'>Mark as Shipping Ready</button>";
                    } else {
                        echo $row['order_status'];
                    }
                    echo "</td>";
                    echo "<td>" . $row['courier_name'] . "</td>";
                    echo "<td>" . $row['payment_method'] . "</td>";
                    echo "<td>";

                    // Nested table for items
                    if ($item_result && $item_result->num_rows > 0) {
                        echo "<table class='item-table'>";
                        echo "<tr><th>Item Name</th><th>Price</th><th>Size</th><th>Quantity</th><th>Total</th></tr>";
                        while ($item_row = $item_result->fetch_assoc()) {
                            $total_item_price = $item_row['price'] * $item_row['quantity'];
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($item_row['name']) . "</td>";
                            echo "<td>" . convertCurrency($item_row['price'], $_SESSION['currency'], $exchange_rates) . " " . $_SESSION['currency'] . "</td>";
                            echo "<td>" . $item_row['size'] . "</td>";
                            echo "<td>" . $item_row['quantity'] . "</td>";
                            echo "<td>" . convertCurrency($total_item_price, $_SESSION['currency'], $exchange_rates) . " " . $_SESSION['currency'] . "</td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "No items found";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No orders found.</p>";
            }

            $conn->close();
            ?>
        </div>
    </div>

    <script>
        function updateOrderStatus(orderId) {
            // Find the button and disable it to prevent multiple clicks
            const button = document.querySelector(`.status-cell[data-order-id="${orderId}"] .status-btn`);
            button.disabled = true;
            button.textContent = "Updating...";

            // Send AJAX request to update the status
            fetch('updateOrderStatus.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'order_id=' + encodeURIComponent(orderId)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the status cell with the new status
                    const statusCell = document.querySelector(`.status-cell[data-order-id="${orderId}"]`);
                    statusCell.textContent = "Shipping is ready";
                } else {
                    alert('Error updating status: ' + (data.error || 'Unknown error'));
                    // Re-enable the button if the update fails
                    button.disabled = false;
                    button.textContent = "Mark as Shipping Ready";
                }
            })
            .catch(error => {
                alert('Error updating status: ' + error.message);
                // Re-enable the button if the request fails
                button.disabled = false;
                button.textContent = "Mark as Shipping Ready";
            });
        }
    </script>
</body>
</html>