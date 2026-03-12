<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->user_id) && !empty($data->notification_id)){

    $user_id = $data->user_id;
    $notification_id = $data->notification_id;

    try {
        $query = "UPDATE notification_history SET is_read = 1 
                  WHERE id = ? AND user_id = ?";
        $stmt = $connection->prepare($query);
        $stmt->execute([$notification_id, $user_id]);

        echo json_encode(array(
            "success" => true, 
            "message" => "Notification marked as read!"
        ));

    } catch(PDOException $e){
        echo json_encode(array(
            "success" => false, 
            "message" => "Error: " . $e->getMessage()
        ));
    }

} else if(!empty($data->user_id) && empty($data->notification_id)){
    // Mark all as read
    $user_id = $data->user_id;
    
    try {
        $query = "UPDATE notification_history SET is_read = 1 WHERE user_id = ?";
        $stmt = $connection->prepare($query);
        $stmt->execute([$user_id]);

        echo json_encode(array(
            "success" => true, 
            "message" => "All notifications marked as read!"
        ));

    } catch(PDOException $e){
        echo json_encode(array(
            "success" => false, 
            "message" => "Error: " . $e->getMessage()
        ));
    }

} else {
    echo json_encode(array(
        "success" => false, 
        "message" => "Missing required fields!"
    ));
}
?>