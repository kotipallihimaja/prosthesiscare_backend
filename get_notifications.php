<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->user_id)){

    $user_id = $data->user_id;
    $limit = !empty($data->limit) ? $data->limit : 20;

    try {
        // Get unread notifications count
        $count_query = "SELECT COUNT(*) as unread_count FROM notification_history 
                       WHERE user_id = ? AND is_read = 0";
        $count_stmt = $connection->prepare($count_query);
        $count_stmt->execute([$user_id]);
        $unread_count = $count_stmt->fetch(PDO::FETCH_ASSOC);

        // Get all notifications
        $query = "SELECT * FROM notification_history 
                  WHERE user_id = ? 
                  ORDER BY created_at DESC 
                  LIMIT ?";
        $stmt = $connection->prepare($query);
        $stmt->execute([$user_id, $limit]);
        $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get upcoming appointment reminders (next 24 hours)
        $reminder_query = "SELECT * FROM appointments 
                           WHERE user_id = ? 
                           AND appointment_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)
                           AND status = 'scheduled'
                           ORDER BY appointment_date, appointment_time";
        $reminder_stmt = $connection->prepare($reminder_query);
        $reminder_stmt->execute([$user_id]);
        $upcoming_reminders = $reminder_stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(array(
            "success" => true,
            "unread_count" => $unread_count['unread_count'],
            "notifications" => $notifications,
            "upcoming_reminders" => $upcoming_reminders
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
        "message" => "User ID required!"
    ));
}
?>