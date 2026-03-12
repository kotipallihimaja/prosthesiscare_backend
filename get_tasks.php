<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->user_id) && !empty($data->reminder_id)){

    $user_id = $data->user_id;
    $reminder_id = $data->reminder_id;
    $today = date('Y-m-d');

    try {
        // Get reminder info
        $reminder_query = "SELECT * FROM reminders WHERE id = ? AND user_id = ?";
        $reminder_stmt = $connection->prepare($reminder_query);
        $reminder_stmt->execute([$reminder_id, $user_id]);
        $reminder = $reminder_stmt->fetch(PDO::FETCH_ASSOC);

        // Get tasks
        $task_query = "SELECT dt.*, 
                      (SELECT is_completed FROM task_completions 
                       WHERE task_id = dt.id AND user_id = ? AND completion_date = ?) as is_completed
                      FROM daily_tasks dt
                      WHERE dt.reminder_id = ?";
        $task_stmt = $connection->prepare($task_query);
        $task_stmt->execute([$user_id, $today, $reminder_id]);
        $tasks = $task_stmt->fetchAll(PDO::FETCH_ASSOC);

        $completed_count = 0;
        foreach($tasks as $task){
            if($task['is_completed'] == 1){
                $completed_count++;
            }
        }

        echo json_encode(array(
            "success" => true,
            "reminder" => $reminder,
            "tasks" => $tasks,
            "completed_count" => $completed_count,
            "total_count" => count($tasks)
        ));

    } catch(PDOException $e){
        echo json_encode(array("success" => false, "message" => "Error: " . $e->getMessage()));
    }

} else {
    echo json_encode(array("success" => false, "message" => "Missing required fields!"));
}
?>