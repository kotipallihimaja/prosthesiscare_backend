<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include "db_connection.php";

$user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;
if($user_id <= 0){
    $data = json_decode(file_get_contents("php://input"));
    $user_id = isset($data->user_id) ? (int)$data->user_id : 0;
}

try {
    $upcoming = [];

    // 1. Fetch upcoming appointments
    $sql_app = "SELECT id, doctor_name, appointment_date, appointment_time, appointment_type 
                FROM appointments 
                WHERE user_id = ? AND status = 'scheduled' AND appointment_date >= CURDATE()
                ORDER BY appointment_date ASC, appointment_time ASC";
    $stmt_app = $connection->prepare($sql_app);
    $stmt_app->execute([$user_id]);
    
    while ($row = $stmt_app->fetch(PDO::FETCH_ASSOC)) {
        $upcoming[] = [
            "id" => "app_" . $row["id"],
            "title" => "Appointment with " . $row["doctor_name"],
            "date" => date("M d, Y", strtotime($row["appointment_date"])),
            "time" => date("g:i A", strtotime($row["appointment_time"])),
            "reminderType" => $row["appointment_type"],
            "is_appointment" => true
        ];
    }

    // 2. Fetch recurring reminders (e.g. Daily maintenance)
    $sql_rem = "SELECT id, reminder_title, reminder_time, reminder_type 
                FROM reminders 
                WHERE user_id = ?";
    $stmt_rem = $connection->prepare($sql_rem);
    $stmt_rem->execute([$user_id]);

    while ($row = $stmt_rem->fetch(PDO::FETCH_ASSOC)) {
        $upcoming[] = [
            "id" => "rem_" . $row["id"],
            "title" => $row["reminder_title"],
            "date" => "Everyday",
            "time" => date("g:i A", strtotime($row["reminder_time"])),
            "reminderType" => $row["reminder_type"],
            "is_appointment" => false
        ];
    }

    echo json_encode([
        "success" => true,
        "reminders" => $upcoming
    ]);

} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error fetching reminders",
        "error" => $e->getMessage()
    ]);
}
?>