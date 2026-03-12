<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include_once 'db_connection.php';

// This would be called by a cron job or scheduled task

try {
    // Get appointments for tomorrow
    $tomorrow = date('Y-m-d', strtotime('+1 day'));
    $query = "SELECT a.*, u.name as user_name, u.phone 
              FROM appointments a
              INNER JOIN users u ON u.id = a.user_id
              WHERE a.appointment_date = ? 
              AND a.status = 'scheduled'
              AND a.reminder_sent = 0";
    $stmt = $connection->prepare($query);
    $stmt->execute([$tomorrow]);
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $reminders_sent = 0;
    foreach($appointments as $appointment){
        // Create notification
        $notif_message = "Reminder: You have an appointment with " . 
                        $appointment['doctor_name'] . " tomorrow (" . 
                        date('F j, Y', strtotime($appointment['appointment_date'])) . 
                        ") at " . date('g:i A', strtotime($appointment['appointment_time']));
        
        $notif_query = "INSERT INTO notification_history 
                        (user_id, notification_type, title, message) 
                        VALUES (?, 'reminder', 'Appointment Reminder', ?)";
        $notif_stmt = $connection->prepare($notif_query);
        $notif_stmt->execute([$appointment['user_id'], $notif_message]);

        // Mark reminder sent
        $update = "UPDATE appointments SET reminder_sent = 1 WHERE id = ?";
        $up_stmt = $connection->prepare($update);
        $up_stmt->execute([$appointment['id']]);

        $reminders_sent++;
    }

    echo json_encode(array(
        "success" => true,
        "message" => "Reminders sent: " . $reminders_sent,
        "appointments_count" => count($appointments)
    ));

} catch(PDOException $e){
    echo json_encode(array(
        "success" => false, 
        "message" => "Error: " . $e->getMessage()
    ));
}
?>