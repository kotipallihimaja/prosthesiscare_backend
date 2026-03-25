<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

include_once 'db_connection.php';


$user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;

if($user_id <= 0){
   
    $data = json_decode(file_get_contents("php://input"));
    $user_id = isset($data->user_id) ? (int)$data->user_id : 0;
}

if($user_id > 0){
    try {
        
        
        $query = "SELECT * FROM appointments WHERE user_id = ? AND status != 'cancelled' ORDER BY appointment_date ASC";
        $stmt = $connection->prepare($query);
        $stmt->execute([$user_id]);
        $all_appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(array(
            "success" => true,
            "appointments" => $all_appointments, 
            "message" => "Appointments fetched successfully"
        ));

    } catch(PDOException $e){
        echo json_encode(array("success" => false, "message" => "Error: " . $e->getMessage()));
    }
} else {
    echo json_encode(array("success" => false, "message" => "User ID required!"));
}
?>