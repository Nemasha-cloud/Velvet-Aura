<?php
require_once 'config.php';

// Check if the username is provided
if (!isset($_POST['username']) || empty(trim($_POST['username']))) {
    echo json_encode(['exists' => false, 'error' => 'Username not provided']);
    exit;
}

$username = trim($_POST['username']);

// Check if the username exists in the database
$sql = "SELECT * FROM customer WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['exists' => true]);
} else {
    echo json_encode(['exists' => false]);
}

$stmt->close();
$conn->close();
?>