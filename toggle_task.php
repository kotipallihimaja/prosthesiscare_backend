<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->user_id) && !empty($data->task_id) && isset($data->is_completed)){

    $user_id = $data->user_id;
    $task_id = $data->task_id;
    $is_completed = $data->is_completed;
    $today = date('Y-m-d');

    try {
        // Check if record exists
        $check = $connection->prepare("SELECT * FROM task_completions 
                                       WHERE user_id = ? AND task_id = ? AND completion_date = ?");
        $check->execute([$user_id, $task_id, $today]);

        if($check->rowCount() > 0){
            $query = "UPDATE task_completions SET is_completed = ?, completed_at = NOW() 
                      WHERE user_id = ? AND task_id = ? AND completion_date = ?";
            $stmt = $connection->prepare($query);
            $stmt->execute([$is_completed, $user_id, $task_id, $today]);
        } else {
            $query = "INSERT INTO task_completions (user_id, task_id, completion_date, is_completed, completed_at) 
                      VALUES (?, ?, ?, ?, NOW())";
            $stmt = $connection->prepare($query);
            $stmt->execute([$user_id, $task_id, $today, $is_completed]);
        }

        echo json_encode(array(
            "success" => true, 
            "message" => $is_completed ? "Task completed!" : "Task unchecked"
        ));

    } catch(PDOException $e){
        echo json_encode(array("success" => false, "message" => "Error: " . $e->getMessage()));
    }

} else {
    echo json_encode(array("success" => false, "message" => "Missing required fields!"));
}
?>