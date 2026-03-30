<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");


date_default_timezone_set('Asia/Kolkata');

include_once 'db_connection.php';

// Android nunchi user_id ni GET parameter ga tesukuntundhi
$user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;

if($user_id > 0) {
    try {
        // Combining reminder_history and user_tasks for a unified history
        $query = "
            (SELECT id, title, date, time, is_completed FROM reminder_history WHERE user_id = ?)
            UNION ALL
            (SELECT ut.id, dt.task_name as title, ut.date, 'Daytime' as time, ut.is_completed 
             FROM user_tasks ut 
             JOIN daily_tasks dt ON ut.task_id = dt.id 
             WHERE ut.user_id = ?)
            ORDER BY date DESC, id DESC
        ";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $user_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $history = [];
        while ($row = $result->fetch_assoc()) {
            $history[] = [
                "id" => (int)$row['id'],
                "title" => $row['title'],
                "due_date" => $row['date'],
                "due_time" => $row['time'],
                "completion_date" => $row['date'],
                "completion_time" => $row['time'],
                "is_completed" => (int)$row['is_completed'],
                "status" => $row['is_completed'] == 1 ? "Completed" : "Missed"
            ];
        }

        echo json_encode([
            "success" => true,
            "history" => $history
        ]);

    } catch (Exception $e) {
        echo json_encode([
            "success" => false,
            "message" => "Error: " . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Invalid User ID"
    ]);
}

$conn->close();
?>