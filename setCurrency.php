<?php
session_start();
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['currency'])) {
    $_SESSION['currency'] = $data['currency'];
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["error" => "Invalid currency"]);
}
?>
