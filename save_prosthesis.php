<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

// Database connection include
include_once 'db_connection.php';

// Get JSON input
$json = file_get_contents("php://input");
$data = json_decode($json);

if (!$data) {
    echo json_encode(array("success" => false, "message" => "No data received!"));
    exit();
}

// Required fields check
$user_id = $data->user_id ?? null;
$type = $data->prosthesis_type ?? null;
$name = $data->prosthesis_name ?? null;
$install_date = $data->installation_date ?? null;
$next_date = $data->next_maintenance_date ?? null;
$notes = $data->notes ?? "";

if (!$user_id || !$type || !$name) {
    echo json_encode(array("success" => false, "message" => "Required fields missing!"));
    exit();
}

try {
    // 1. Check if record exists using mysqli ($conn)
    $check = $conn->prepare("SELECT id FROM prosthesis WHERE user_id = ?");
    $check->bind_param("i", $user_id);
    $check->execute();
    $check->store_result();
    $exists = $check->num_rows > 0;
    $check->close();

    if ($exists) {
        // UPDATE existing record
        $stmt = $conn->prepare("UPDATE prosthesis SET prosthesis_type = ?, prosthesis_name = ?, installation_date = ?, next_maintenance_date = ?, notes = ? WHERE user_id = ?");
        $stmt->bind_param("sssssi", $type, $name, $install_date, $next_date, $notes, $user_id);
    } else {
        // INSERT new record
        $stmt = $conn->prepare("INSERT INTO prosthesis (user_id, prosthesis_type, prosthesis_name, installation_date, next_maintenance_date, notes) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", $user_id, $type, $name, $install_date, $next_date, $notes);
    }

    if ($stmt->execute()) {
        echo json_encode(array("success" => true, "message" => "Prosthesis saved successfully!"));
    } else {
        echo json_encode(array("success" => false, "message" => "Database error: " . $conn->error));
    }
    $stmt->close();

} catch (Exception $e) {
    echo json_encode(array("success" => false, "message" => "Server Error: " . $e->getMessage()));
}
 ?> 