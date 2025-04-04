<?php 
require 'config.php';
include 'header.php';
?>

<link rel="stylesheet" href="src/css/cart.css" type="text/css">
</head>
<body>
<center><h2><?php echo $_SESSION['log_name'] ?>'s Cart</h2></center>

<?php 
if (isset($_SESSION['log_user'])) {
    $cus_id = $_SESSION['log_user'];

    // Query cart id from cart table using cus_id
    $sqli = "SELECT cart_id FROM cart WHERE cus_id='" . $cus_id . "'";
    $result = $conn->query($sqli);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $cart_id = $row['cart_id'];
        }
    }
} else {
    header("Location: login.php");
    exit; // Ensure script stops after redirect
}

// Query cart details from cart table
$sql = "SELECT i.name, i.image, i.unit_price, c.quantity, i.item_code 
        FROM cart_details c, item i 
        WHERE c.item_code = i.item_code AND c.cart_id = $cart_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<form id='Updateform' method='post' action='updateCart.php?cart_id=" . $cart_id . "'>";
    echo "<div class='cart-table'>";
    echo "<center>";
    echo "<table>";
    echo "<th>Description</th>";
    echo "<th>Item</th>";
    echo "<th>Price</th>";
    echo "<th>Quantity</th>";
    echo "<th>Total</th>";
    echo "<th>Delete</th>";

    while ($row = $result->fetch_assoc()) {
        $itemCode = $row['item_code'];
        
        // Convert prices to user's currency
        $unit_price = convertCurrency($row['unit_price'], $_SESSION['currency'], $exchange_rates);
        $total_price = convertCurrency($row['unit_price'] * $row['quantity'], $_SESSION['currency'], $exchange_rates);

        echo "<tr>";
        echo "<td><p>" . htmlspecialchars($row['name']) . "</p>";
        echo "<input type='hidden' name='it_code[]' id='it_code[]' value='" . $row['item_code'] . "'>";
        echo "</td>";
        echo "<td><center><img src='" . htmlspecialchars($row['image']) . "'></center></td>";
        echo "<td><center><p>" . $unit_price . " " . $_SESSION['currency'] . "</p></center></td>";
        echo "<td><center><input type='number' name='quantity[]' min='1' value='" . $row['quantity'] . "'></center></td>";
        echo "<td><center><p>" . $total_price . " " . $_SESSION['currency'] . "</p></center></td>";
        echo "<td><center><input type='submit' formaction='deleteCart.php?it_code=" . $row['item_code'] . "&cart_id=" . $cart_id . "' name='delete' value='Remove Item'></center></td>";
        echo "</tr>";
    }
    echo "<tr><td colspan='6'><input type='submit' name='update' value='Update'></td></tr>";
    echo "</table>";
    echo "</center>";
    echo "</div>";
    echo "</form>";
} else {
    echo "<div class='cart-table'>";
    echo "<center>";
    echo "<table>";
    echo "<th>Description</th>";
    echo "<th>Item</th>";
    echo "<th>Price</th>";
    echo "<th>Quantity</th>";
    echo "<th>Total</th>";
    echo "</table>";
    echo "</center>";
    echo "</div>";
    echo "<h3><center>No items in cart :(</center></h3>";
}
?>

<form method="get" action="Checkout.php">
    <center><input type="submit" name="Prcd_chkOut" value="Proceed to Checkout" style="width: 20%;" ></center>
    <input type="hidden" name="cart_id" value="<?php echo $cart_id; ?>">
</form>

<?php include 'footer.php'; ?>
</body>
</html>