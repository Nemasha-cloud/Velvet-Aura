<?php
require_once 'config.php';

// Check if the request is valid
if (!isset($_POST['order_id']) || empty($_POST['order_id'])) {
    echo json_encode(['success' => false, 'error' => 'Order ID not provided']);
    exit;
}

$order_id = intval($_POST['order_id']);

// Update the order status to "Shipping is ready"
$sql = "UPDATE orders SET order_status = 'Shipping is ready' WHERE order_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $conn->error]);
}

$stmt->close();
$conn->close();
?>