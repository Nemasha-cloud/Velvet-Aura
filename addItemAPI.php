<?php
require_once 'config.php'; // Database connection

header("Content-Type: application/json"); // Set response type
header("Access-Control-Allow-Origin: *"); // Allow external requests
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["error" => "Only POST requests are allowed"]);
    exit;
}

// Read JSON input
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["productName"], $data["category"], $data["type"], $data["description"], $data["imageAddress"], $data["Size"], $data["price"], $data["qty"])) {
    echo json_encode(["error" => "Missing required fields"]);
    exit;
}

// Sanitize input
$name = $conn->real_escape_string($data["productName"]);
$category = $conn->real_escape_string($data["category"]);
$type = $conn->real_escape_string($data["type"]);
$description = $conn->real_escape_string($data["description"]);
$image = $conn->real_escape_string($data["imageAddress"]);
$size = $conn->real_escape_string($data["Size"]);
$price = floatval($data["price"]);
$qty = intval($data["qty"]);
$admin_id = 1; // Temporary admin_id, modify as needed

$sql = "INSERT INTO item (name, unit_price, type, category, item_desc, stock, image, admin_id, size) 
        VALUES ('$name', '$price', '$type', '$category', '$description', '$qty', '$image', '$admin_id', '$size')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["success" => "Item added successfully"]);
} else {
    echo json_encode(["error" => "Failed to add item: " . $conn->error]);
}

$conn->close();
?>
