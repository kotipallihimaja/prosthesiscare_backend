<?php
// This file connects our app to the database

// Database settings
$host = "localhost";      // Where the database is (your computer)
$db_name = "prosthesis_app"; // The database name we created
$username = "root";       // Default XAMPP username
$password = "";           // Default XAMPP password (empty)

// Try to connect to database
try {
    // Create connection using PDO (PHP Data Objects)
    $connection = new PDO(
        "mysql:host=$host;dbname=$db_name;charset=utf8", 
        $username, 
        $password
    );
    
    // Set error mode to show errors (helpful for debugging)
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Success message (we'll remove this later)
    // echo "Connected successfully!";
    
} catch(PDOException $e) {
    // If connection fails, show error message
    die("Connection failed: " . $e->getMessage());
}
?>