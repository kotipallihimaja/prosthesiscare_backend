<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->user_id) && !empty($data->appointment_id)){

    $user_id = $data->user_id;
    $appointment_id = $data->appointment_id;

    try {
        // Get appointment details before cancelling
        $get = $connection->prepare("SELECT * FROM appointments WHERE id = ? AND user_id = ?");
        $get->execute([$appointment_id, $user_id]);
        $appointment = $get->fetch(PDO::FETCH_ASSOC);

        if(!$appointment){
            echo json_encode(array("success" => false, "message" => "Appointment not found!"));
            exit();
        }

        // Update status
        $query = "UPDATE appointments SET status = 'cancelled' WHERE id = ? AND user_id = ?";
        $stmt = $connection->prepare($query);
        $stmt->execute([$appointment_id, $user_id]);

        // Create notification
        $notif_message = "Your appointment with " . $appointment['doctor_name'] . " on " . 
                        date('F j, Y', strtotime($appointment['appointment_date'])) . 
                        " at " . date('g:i A', strtotime($appointment['appointment_time'])) . 
                        " has been cancelled.";
        
        $notif_query = "INSERT INTO notification_history 
                        (user_id, notification_type, title, message) 
                        VALUES (?, 'appointment_cancelled', 'Appointment Cancelled', ?)";
        $notif_stmt = $connection->prepare($notif_query);
        $notif_stmt->execute([$user_id, $notif_message]);

        echo json_encode(array(
            "success" => true, 
            "message" => "Appointment cancelled successfully!"
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