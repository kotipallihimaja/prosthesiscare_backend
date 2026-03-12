<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Accept");

// Include database connection
include_once 'db_connection.php';

// Get JSON input
$json = file_get_contents("php://input");
$data = json_decode($json);

// Check if data is received
if (!$data) {
    echo json_encode(array(
        "success" => false, 
        "message" => "No data received!",
        "debug" => "JSON decode failed"
    ));
    exit();
}

// Check required fields
if(
    empty($data->user_id) ||
    empty($data->prosthesis_type) ||
    empty($data->prosthesis_name) ||
    empty($data->installation_date) ||
    empty($data->next_maintenance_date)
) {
    echo json_encode(array(
        "success" => false, 
        "message" => "Please fill in all required fields!",
        "received_data" => $data
    ));
    exit();
}

// Store values
$user_id = $data->user_id;
$prosthesis_type = $data->prosthesis_type;
$prosthesis_name = $data->prosthesis_name;
$installation_date = $data->installation_date;
$next_maintenance_date = $data->next_maintenance_date;
$notes = isset($data->notes) ? $data->notes : '';

try {
    // Check if user already has prosthesis
    $check = $connection->prepare("SELECT id FROM prosthesis WHERE user_id = ?");
    $check->execute([$user_id]);

    if($check->rowCount() > 0) {
        // Update existing record
        $query = "UPDATE prosthesis SET 
                  prosthesis_type = ?, prosthesis_name = ?,
                  installation_date = ?, next_maintenance_date = ?, notes = ?
                  WHERE user_id = ?";
        $stmt = $connection->prepare($query);
        $stmt->execute([$prosthesis_type, $prosthesis_name,
                      $installation_date, $next_maintenance_date, $notes, $user_id]);
    } else {
        // Insert new record
        $query = "INSERT INTO prosthesis 
                  (user_id, prosthesis_type, prosthesis_name, 
                   installation_date, next_maintenance_date, notes) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $connection->prepare($query);
        $stmt->execute([$user_id, $prosthesis_type, $prosthesis_name,
                      $installation_date, $next_maintenance_date, $notes]);
    }

    echo json_encode(array(
        "success" => true,
        "message" => "Prosthesis saved successfully!"
    ));

} catch(PDOException $e) {
    echo json_encode(array(
        "success" => false,
        "message" => "Database Error: " . $e->getMessage()
    ));
}
?>