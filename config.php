<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$server = "localhost";
$username = "root";
$password = "";
$database = "online_fashion";

$conn = new mysqli($server, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set default currency if not set
if (!isset($_SESSION['currency'])) {
    $_SESSION['currency'] = 'LKR'; // Default currency
}

// Exchange rates (modify as needed)
$exchange_rates = [
    'LKR' => 1,
    'USD' => 0.0033,  // 1 USD = ~300 LKR
    'EUR' => 0.0031   // 1 EUR = ~320 LKR
];

// Define currency conversion function only if it doesn't exist
if (!function_exists('convertCurrency')) {
    function convertCurrency($amount, $to_currency, $exchange_rates) {
        if (!is_numeric($amount)) {
            return $amount; // Return original if not numeric
        }
        
        // Ensure the currency exists in exchange rates
        if (isset($exchange_rates[$to_currency])) {
            $converted = $amount * $exchange_rates[$to_currency];
            return number_format($converted, 2, '.', ''); // Format to 2 decimal places
        }
        return number_format($amount, 2, '.', ''); // Return original formatted if currency not found
    }
}
?>