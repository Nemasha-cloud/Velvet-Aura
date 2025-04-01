<?php
require 'config.php';

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

// Check the request method
$method = $_SERVER['REQUEST_METHOD'];

if ($method === "GET") {
    // Fetch all items
    $sql = "SELECT * FROM item";
    $result = $conn->query($sql);
    $items = [];

    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }

    echo json_encode($items);
} 

elseif ($method === "POST") {
    // Update item stock
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data["itms_code"], $data["stock"])) {
        echo json_encode(["error" => "Missing required fields"]);
        exit;
    }

    foreach ($data["itms_code"] as $index => $itemCode) {
        $stock = intval($data["stock"][$index]);
        $sql = "UPDATE item SET stock = '$stock' WHERE item_code = '$itemCode'";

        if (!$conn->query($sql)) {
            echo json_encode(["error" => "Failed to update item: " . $conn->error]);
            exit;
        }
    }

    echo json_encode(["success" => "Items updated successfully"]);
} 

elseif ($method === "DELETE") {
    // Delete an item
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data["item_code"])) {
        echo json_encode(["error" => "Missing item code"]);
        exit;
    }

    $itemCode = $conn->real_escape_string($data["item_code"]);
    $sql = "DELETE FROM item WHERE item_code = '$itemCode'";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["success" => "Item deleted successfully"]);
    } else {
        echo json_encode(["error" => "Failed to delete item: " . $conn->error]);
    }
} 

else {
    // Invalid method
    echo json_encode(["error" => "Invalid request method"]);
}

$conn->close();
?>
