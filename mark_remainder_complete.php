<?php
// ఇండియా టైమ్ సెట్ చేయడం
date_default_timezone_set('Asia/Kolkata');

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include "db_connection.php";


$data = json_decode(file_get_contents("php://input"));

if(!empty($data->user_id)){
    $user_id = $data->user_id;
   
    $title = isset($data->title) ? $data->title : 'Daily Maintenance'; 
    $date = date('Y-m-d');
    $time = date('h:i A');

   
    $query = "INSERT INTO reminder_history (user_id, title, date, time, is_completed) VALUES (?, ?, ?, ?, 1)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isss", $user_id, $title, $date, $time);
    
    if($stmt->execute()){
        
        echo json_encode(array("success" => true, "message" => "History updated successfully"));
    } else {
        echo json_encode(array("success" => false, "message" => "Database Error: " . $conn->error));
    }
    $stmt->close();
} else {
    echo json_encode(array("success" => false, "message" => "Invalid User ID or Missing Data"));
}
 ?>