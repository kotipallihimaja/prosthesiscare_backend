<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"));

if(
    !empty($data->user_id) &&
    !empty($data->reminder_id) &&
    !empty($data->snooze_duration)
){

    $user_id = $data->user_id;
    $reminder_id = $data->reminder_id;
    $snooze_duration = $data->snooze_duration; // 15, 30, 60, 120, or "later"
    
    // Calculate snooze time
    $snooze_minutes = 0;
    switch($snooze_duration) {
        case '15':
            $snooze_minutes = 15;
            $message = "Snoozed for 15 minutes";
            break;
        case '30':
            $snooze_minutes = 30;
            $message = "Snoozed for 30 minutes";
            break;
        case '60':
            $snooze_minutes = 60;
            $message = "Snoozed for 1 hour";
            break;
        case '120':
            $snooze_minutes = 120;
            $message = "Snoozed for 2 hours";
            break;
        case 'later':
            $snooze_minutes = 0;
            $message = "Snoozed until later today";
            break;
        default:
            $snooze_minutes = 30;
            $message = "Snoozed for 30 minutes";
    }

    // Calculate new snooze time
    $snooze_time = date('H:i:s', strtotime("+{$snooze_minutes} minutes"));

    try {
        // Update the reminder with snooze time
        $query = "UPDATE reminders SET reminder_time = ? WHERE id = ? AND user_id = ?";
        $stmt = $connection->prepare($query);
        $stmt->execute([$snooze_time, $reminder_id, $user_id]);

        echo json_encode(array(
            "success" => true,
            "message" => $message,
            "snoozed_until" => $snooze_time,
            "snooze_duration" => $snooze_duration
        ));

    } catch(PDOException $e){
        echo json_encode(array("success" => false, "message" => "Error: " . $e->getMessage()));
    }

} else {
    echo json_encode(array("success" => false, "message" => "Missing required fields!"));
}
?>