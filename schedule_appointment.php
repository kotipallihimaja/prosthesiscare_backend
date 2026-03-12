<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"));

if(
    !empty($data->user_id) &&
    !empty($data->doctor_name) &&
    !empty($data->appointment_date) &&
    !empty($data->appointment_time) &&
    !empty($data->appointment_type)
){

    $user_id = $data->user_id;
    $doctor_name = $data->doctor_name;
    $appointment_date = $data->appointment_date;
    $appointment_time = $data->appointment_time;
    $appointment_type = $data->appointment_type;
    $notes = !empty($data->notes) ? $data->notes : '';

    try {
        // Check if slot is available
        $check = $connection->prepare("SELECT id FROM appointments 
                                       WHERE appointment_date = ? AND appointment_time = ? 
                                       AND doctor_name = ? AND status != 'cancelled'");
        $check->execute([$appointment_date, $appointment_time, $doctor_name]);
        
        if($check->rowCount() > 0){
            echo json_encode(array(
                "success" => false, 
                "message" => "This time slot is already booked with " . $doctor_name
            ));
            exit();
        }

        // Insert appointment
        $query = "INSERT INTO appointments 
                  (user_id, doctor_name, appointment_date, appointment_time, appointment_type, notes) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $connection->prepare($query);
        $stmt->execute([$user_id, $doctor_name, $appointment_date, $appointment_time, $appointment_type, $notes]);

        $appointment_id = $connection->lastInsertId();

        // Create notification
        $notif_title = "Appointment Scheduled";
        $notif_message = "Your appointment with " . $doctor_name . " is scheduled for " . 
                         date('F j, Y', strtotime($appointment_date)) . " at " . 
                         date('g:i A', strtotime($appointment_time));
        
        $notif_query = "INSERT INTO notification_history 
                        (user_id, notification_type, title, message) 
                        VALUES (?, 'appointment', ?, ?)";
        $notif_stmt = $connection->prepare($notif_query);
        $notif_stmt->execute([$user_id, $notif_title, $notif_message]);

        echo json_encode(array(
            "success" => true, 
            "message" => "Appointment scheduled successfully!",
            "appointment_id" => $appointment_id
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
        "message" => "Please fill in all required fields!"
    ));
}
?>