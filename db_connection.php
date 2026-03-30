<?php
// Global CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Handle preflight OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Database parameters
$host = "localhost";
$user = "root";
$password = "";
$db = "prosthesis_app";

// 1. MySQLi connection (Legacy for some scripts)
$conn = new mysqli($host, $user, $password, $db);
if ($conn->connect_error) {
    die("MySQLi Connection failed: " . $conn->connect_error);
}

// 2. PDO connection (For modern scripts)
try {
    $connection = new PDO("mysql:host=$host;dbname=$db", $user, $password);
    // Set PDO error mode to Exception
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Ensure UTF8
    $connection->exec("set names utf8mb4");
} catch(PDOException $e) {
    die("PDO Connection failed: " . $e->getMessage());
}
?>