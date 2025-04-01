<?php
require_once 'config.php';

// Check if $conn is valid
if (!isset($conn) || !($conn instanceof mysqli) || $conn->connect_error) {
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

// Check if the item name is provided
if (!isset($_POST['item_name']) || empty(trim($_POST['item_name']))) {
    echo json_encode(['error' => 'Please provide an item name']);
    exit;
}

$item_name = trim($_POST['item_name']);

// Sanitize the item name to prevent SQL injection
$item_name = mysqli_real_escape_string($conn, $item_name);

// Query to fetch the item price
$sql = "SELECT name, unit_price FROM item WHERE name LIKE '%$item_name%' LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $price = $row['unit_price'];
    $item_name = $row['name'];

    // Convert price to the user's currency (if convertCurrency is available)
    if (function_exists('convertCurrency') && isset($_SESSION['currency'], $exchange_rates)) {
        $price = convertCurrency($price, $_SESSION['currency'], $exchange_rates);
        $currency = $_SESSION['currency'];
    } else {
        $currency = 'LKR'; // Default currency
    }

    echo json_encode([
        'success' => true,
        'item_name' => $item_name,
        'price' => $price,
        'currency' => $currency
    ]);
} else {
    echo json_encode(['error' => 'Item not found']);
}

$conn->close();
?>