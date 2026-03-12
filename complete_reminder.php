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
        // Get all tasks for this reminder
        $task_query = "SELECT id FROM daily_tasks WHERE reminder_id = ?";
        $task_stmt = $connection->prepare($task_query);
        $task_stmt->execute([$reminder_id]);
        $tasks = $task_stmt->fetchAll(PDO::FETCH_ASSOC);

        $completed = 0;
        foreach($tasks as $task){
            // Check if exists
            $check = $connection->prepare("SELECT id FROM task_completions 
                                           WHERE user_id = ? AND task_id = ? AND completion_date = ?");
            $check->execute([$user_id, $task['id'], $today]);

            if($check->rowCount() > 0){
                $update = "UPDATE task_completions SET is_completed = 1, completed_at = NOW() 
                           WHERE user_id = ? AND task_id = ? AND completion_date = ?";
                $up_stmt = $connection->prepare($update);
                $up_stmt->execute([$user_id, $task['id'], $today]);
            } else {
                $insert = "INSERT INTO task_completions (user_id, task_id, completion_date, is_completed, completed_at) 
                           VALUES (?, ?, ?, 1, NOW())";
                $in_stmt = $connection->prepare($insert);
                $in_stmt->execute([$user_id, $task['id'], $today]);
            }
            $completed++;
        }

        // Calculate points (10 points per completed reminder)
        $points_earned = $completed > 0 ? 10 : 0;

        // Update compliance score
        $total_tasks = count($tasks);
        $score = $total_tasks > 0 ? 100 : 0;

        // Check if compliance record exists for today
        $check_score = $connection->prepare("SELECT id FROM compliance_scores WHERE user_id = ? AND date = ?");
        $check_score->execute([$user_id, $today]);

        if($check_score->rowCount() > 0){
            $update_score = "UPDATE compliance_scores SET 
                            total_tasks = ?, completed_tasks = ?, points_earned = ?, score = ? 
                            WHERE user_id = ? AND date = ?";
            $up_score = $connection->prepare($update_score);
            $up_score->execute([$total_tasks, $completed, $points_earned, $score, $user_id, $today]);
        } else {
            $insert_score = "INSERT INTO compliance_scores 
                            (user_id, date, total_tasks, completed_tasks, points_earned, score) 
                            VALUES (?, ?, ?, ?, ?, ?)";
            $in_score = $connection->prepare($insert_score);
            $in_score->execute([$user_id, $today, $total_tasks, $completed, $points_earned, $score]);
        }

        // Update user's total points
        $update_user = "UPDATE users SET total_points = total_points + ? WHERE id = ?";
        $up_user = $connection->prepare($update_user);
        $up_user->execute([$points_earned, $user_id]);

        echo json_encode(array(
            "success" => true,
            "message" => "Great job! All tasks completed!",
            "points_earned" => $points_earned,
            "completed_count" => $completed
        ));

    } catch(PDOException $e){
        echo json_encode(array("success" => false, "message" => "Error: " . $e->getMessage()));
    }

} else {
    echo json_encode(array("success" => false, "message" => "Missing required fields!"));
}
?>