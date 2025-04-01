<?php 
require_once 'config.php';

// Start session if not already started (redundant since config.php handles this, but kept for clarity)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'header.php';

$cartId = isset($_GET['cart_id']) ? $_GET['cart_id'] : null;
$total = 0;

if (!$cartId) {
    echo "<script>alert('Invalid cart ID'); window.location.href='cart.php';</script>";
    exit();
}

// Delivery cost in LKR (base currency)
$delivery_cost_lkr = 450.00;
?>

<link rel="stylesheet" href="src/css/Checkout.css" type="text/css">
</head>
<body>

<?php echo "<form method='POST' name='billing_details' action='btnCheckout.php?cartId=$cartId' id='checkoutForm'>"; ?>
    
    <div class="container">
        <div class="wrapper">
            <div class="left-side">
                <h2>Order Summary</h2><hr>
                <div class="left-side-top">
<?php 
if (isset($_SESSION['log_user'])) {
    $sql = "SELECT * FROM cart_details WHERE cart_id='$cartId'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $item_code = $row['item_code'];
            $sqli = "SELECT name, image FROM item WHERE item_code='$item_code'";
            $cart_details_result = $conn->query($sqli);
            
            if ($cart_details_result && $cart_details_result->num_rows > 0) {
                while ($cart_details_rows = $cart_details_result->fetch_assoc()) {
                    $item_img = $cart_details_rows['image'];                                           
                    $item_size = $row['size'];
                    $item_qty = $row['quantity'];
                    $item_price_lkr = $row['price'] * $item_qty; // Price in LKR
                    $total += $item_price_lkr; // Total in LKR
                    $item_name = $cart_details_rows['name']; 

                    // Convert item price to user's currency
                    $item_price_converted = convertCurrency($item_price_lkr, $_SESSION['currency'], $exchange_rates);

                    echo "<table class='item-table'>";
                    echo "<tr><td class='img-col'><img src='" . htmlspecialchars($item_img) . "' class='img-box'></td>";
                    echo "<td class='desc-col'><span class='item-name'>" . htmlspecialchars($item_name) . "</span><br><span class='item-details'>Size $item_size Quantity $item_qty</span></td></tr>";
                    echo "<tr><td><div class='price'>" . $item_price_converted . " " . $_SESSION['currency'] . "</div></td></tr></table>";
                }
            }
        }
    } else {
        echo "<p>No items in cart</p>";
    }
} else {
    echo "<script>alert('Please login to view cart'); window.location.href='login.php';</script>";
    exit();
}

// Convert delivery cost and total to user's currency
$delivery_cost_converted = convertCurrency($delivery_cost_lkr, $_SESSION['currency'], $exchange_rates);
$total_converted = convertCurrency($total + $delivery_cost_lkr, $_SESSION['currency'], $exchange_rates);
?>
                </div>
                <div class="left-side-bottom">
                    <div class="tot-desc">
                        <span style="float: left;">Delivery</span><br>
                        <span style="float: left;">TOTAL</span>
                    </div>
                    <div class="tot-value">
                        <span style="float: right;"><?php echo $delivery_cost_converted . " " . $_SESSION['currency']; ?></span><br>
                        <span style="float: right;"><?php echo $total_converted . " " . $_SESSION['currency']; ?></span>
                    </div>
                </div>
            </div>
            <div class="right-side">
                <div class="billing-details">
                    <h3>Address</h3><br>
                    <div class="address-top">
                        <input type="text" name="address" placeholder="Street Name" required>
                        <input type="text" name="address1" placeholder="District" required>
                    </div>
                    <input type="text" class="address3" name="address2" placeholder="Province" required>
                </div>
                <div class="payment-details">
                    <h3>Payment Method</h3><br>
                    <img src="src/img/pay.png" align="right" width="300" height="120">
                    <input type="radio" id="COD" name="delivery" value="1" required>
                    <label for="COD">Cash on Delivery</label><br>
                    <input type="radio" id="card" name="delivery" value="3">
                    <label for="card">Credit Card</label><br>
                    <input type="radio" id="payhere" name="delivery" value="4">
                    <label for="payhere">PayHere</label><br><br>
                    <center>
                        <input type="submit" name="btnCheckout" class="btnCheckout" value="Checkout">
                    </center>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Success Modal -->
<div id="successModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div style="background-color: #fff; margin: 15% auto; padding: 20px; border-radius: 10px; width: 400px; text-align: center; box-shadow: 0 0 20px rgba(0,0,0,0.2);">
        <span onclick="closeModal()" style="color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer;">×</span>
        <div id="spinner" class="spinner"></div>
        <h2 id="successTitle" style="color: #fe4253; display: none;">Payment Successful!</h2>
        <p id="successMessage" style="display: none;">Your order has been placed successfully.</p>
        <p id="addressDisplay" style="display: none;"></p>
        <button id="closeButton" onclick="closeModal()" style="display: none; background-color: #fe4253; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; margin-top: 20px;">Close</button>
    </div>
</div>

<style type="text/css">
    .spinner {
        width: 50px;
        height: 50px;
        border: 5px solid #f3f3f3;
        border-top: 5px solid #fe4253;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 20px auto;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<script type="text/javascript">
document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    const paymentMethod = document.querySelector('input[name="delivery"]:checked');
    
    if (paymentMethod && paymentMethod.value === '4') { // PayHere selected
        e.preventDefault();
        
        const street = document.querySelector('input[name="address"]').value;
        const district = document.querySelector('input[name="address1"]').value;
        const province = document.querySelector('input[name="address2"]').value;
        const fullAddress = `${street}, ${district}, ${province}`;

        document.getElementById('successModal').style.display = 'block';
        document.getElementById('spinner').style.display = 'block';
        document.getElementById('successTitle').style.display = 'none';
        document.getElementById('successMessage').style.display = 'none';
        document.getElementById('addressDisplay').style.display = 'none';
        document.getElementById('closeButton').style.display = 'none';

        setTimeout(function() {
            document.getElementById('spinner').style.display = 'none';
            document.getElementById('successTitle').style.display = 'block';
            document.getElementById('successMessage').style.display = 'block';
            document.getElementById('addressDisplay').style.display = 'block';
            document.getElementById('addressDisplay').textContent = "Delivery Address: " + fullAddress;
            document.getElementById('closeButton').style.display = 'block';
            
            // Submit form after PayHere simulation
            setTimeout(() => {
                document.getElementById('checkoutForm').submit();
            }, 1000);
        }, 3000);
    }
    // For COD and Credit Card, let normal form submission proceed
});

function closeModal() {
    document.getElementById('successModal').style.display = 'none';
}
</script>

</body>
</html>