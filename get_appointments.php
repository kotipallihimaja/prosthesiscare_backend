<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->user_id)){

    $user_id = $data->user_id;
    
    // Get month filter (default current month)
    $month = !empty($data->month) ? $data->month : date('m');
    $year = !empty($data->year) ? $data->year : date('Y');

    try {
        // Get upcoming appointments (filtered by month)
        $query = "SELECT * FROM appointments 
                  WHERE user_id = ? 
                  AND MONTH(appointment_date) = ? 
                  AND YEAR(appointment_date) = ?
                  AND appointment_date >= CURDATE()
                  AND status != 'cancelled'
                  ORDER BY appointment_date ASC, appointment_time ASC";
        $stmt = $connection->prepare($query);
        $stmt->execute([$user_id, $month, $year]);
        $upcoming_appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get past appointments
        $past_query = "SELECT * FROM appointments 
                       WHERE user_id = ? 
                       AND (appointment_date < CURDATE() OR status = 'completed')
                       ORDER BY appointment_date DESC, appointment_time DESC
                       LIMIT 10";
        $past_stmt = $connection->prepare($past_query);
        $past_stmt->execute([$user_id]);
        $past_appointments = $past_stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get all months with appointments
        $months_query = "SELECT DISTINCT MONTH(appointment_date) as month, YEAR(appointment_date) as year
                        FROM appointments 
                        WHERE user_id = ? AND appointment_date >= CURDATE()
                        ORDER BY year, month";
        $months_stmt = $connection->prepare($months_query);
        $months_stmt->execute([$user_id]);
        $available_months = $months_stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get notification for upcoming appointment (within 24 hours)
        $soon_query = "SELECT * FROM appointments 
                       WHERE user_id = ? 
                       AND appointment_date = CURDATE() 
                       AND status != 'cancelled'
                       ORDER BY appointment_time ASC";
        $soon_stmt = $connection->prepare($soon_query);
        $soon_stmt->execute([$user_id]);
        $today_appointments = $soon_stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(array(
            "success" => true,
            "upcoming_appointments" => $upcoming_appointments,
            "past_appointments" => $past_appointments,
            "available_months" => $available_months,
            "today_appointments" => $today_appointments,
            "current_month" => $month,
            "current_year" => $year
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