<?php
error_reporting(E_ALL);
ini_set('display_errors', 0); // Hide errors from output, but they go to logs

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->user_id) && !empty($data->task_id)){
    $user_id = (int)$data->user_id;
    $task_id = (int)$data->task_id;
    $date = date('Y-m-d');

    try {
        // First check if it exists
        $check = $conn->prepare("SELECT is_completed FROM user_tasks WHERE user_id = ? AND task_id = ? AND date = ?");
        $check->bind_param("iis", $user_id, $task_id, $date);
        $check->execute();
        $res = $check->get_result();

        if($res->num_rows > 0) {
            // Update existing
            $row = $res->fetch_assoc();
            $new_status = $row['is_completed'] == 1 ? 0 : 1;
            $stmt = $conn->prepare("UPDATE user_tasks SET is_completed = ? WHERE user_id = ? AND task_id = ? AND date = ?");
            $stmt->bind_param("iiis", $new_status, $user_id, $task_id, $date);
        } else {
            // Insert new
            $stmt = $conn->prepare("INSERT INTO user_tasks (user_id, task_id, is_completed, date) VALUES (?, ?, 1, ?)");
            $stmt->bind_param("iis", $user_id, $task_id, $date);
        }

        if($stmt->execute()){
            echo json_encode(["success" => true, "message" => "Updated"]);
        } else {
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "DB Error: " . $conn->error]);
        }
    } catch(Exception $e){
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Server Error: " . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Incomplete data"]);
}
?>
