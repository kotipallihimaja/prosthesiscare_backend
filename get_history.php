<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");


date_default_timezone_set('Asia/Kolkata');

include_once 'db_connection.php';

// Android nunchi user_id ni GET parameter ga tesukuntundhi
$user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;

if($user_id > 0) {
    try {
        // User history ni latest date nunchi order chesthundhi
        $query = "SELECT * FROM reminder_history WHERE user_id = ? ORDER BY id DESC";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
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