<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->user_id)){

    $user_id = $data->user_id;
    $today = date('Y-m-d');

    try {
        // Get user info
        $user_query = "SELECT id, name, email, total_points FROM users WHERE id = ?";
        $user_stmt = $connection->prepare($user_query);
        $user_stmt->execute([$user_id]);
        $user = $user_stmt->fetch(PDO::FETCH_ASSOC);

        // Get today's compliance score
        $score_query = "SELECT * FROM compliance_scores WHERE user_id = ? AND date = ?";
        $score_stmt = $connection->prepare($score_query);
        $score_stmt->execute([$user_id, $today]);
        $today_score = $score_stmt->fetch(PDO::FETCH_ASSOC);

        // Get overall compliance (last 7 days average)
        $overall_query = "SELECT AVG(score) as avg_score, SUM(points_earned) as total_points_earned 
                         FROM compliance_scores WHERE user_id = ? AND date >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
        $overall_stmt = $connection->prepare($overall_query);
        $overall_stmt->execute([$user_id]);
        $overall = $overall_stmt->fetch(PDO::FETCH_ASSOC);

        // Calculate compliance percentage
        $compliance_score = $overall['avg_score'] ?? 0;
        $total_points = $user['total_points'] ?? 0;

        // Get reminders with tasks
        $reminder_query = "SELECT r.*, 
                          (SELECT COUNT(*) FROM daily_tasks WHERE reminder_id = r.id) as total_tasks
                          FROM reminders r 
                          WHERE r.user_id = ? AND r.is_active = 1
                          ORDER BY r.reminder_time";
        $reminder_stmt = $connection->prepare($reminder_query);
        $reminder_stmt->execute([$user_id]);
        $reminders = $reminder_stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get tasks for each reminder with completion status
        $dashboard_reminders = [];
        foreach($reminders as $reminder){
            $task_query = "SELECT dt.*, 
                          (SELECT is_completed FROM task_completions 
                           WHERE task_id = dt.id AND user_id = ? AND completion_date = ?) as is_completed
                          FROM daily_tasks dt
                          WHERE dt.reminder_id = ?";
            $task_stmt = $connection->prepare($task_query);
            $task_stmt->execute([$user_id, $today, $reminder['id']]);
            $tasks = $task_stmt->fetchAll(PDO::FETCH_ASSOC);

            $completed_count = 0;
            foreach($tasks as $task){
                if($task['is_completed'] == 1){
                    $completed_count++;
                }
            }

            $dashboard_reminders[] = [
                "reminder" => $reminder,
                "tasks" => $tasks,
                "completed_count" => $completed_count,
                "total_count" => count($tasks),
                "is_completed" => (count($tasks) > 0 && $completed_count == count($tasks))
            ];
        }

        echo json_encode(array(
            "success" => true,
            "user" => [
                "name" => $user['name'],
                "total_points" => $total_points
            ],
            "compliance" => [
                "score" => round($compliance_score),
                "today_completed" => $today_score ? $today_score['completed_tasks'] : 0,
                "today_total" => $today_score ? $today_score['total_tasks'] : 0,
                "points_earned_today" => $today_score ? $today_score['points_earned'] : 0
            ],
            "reminders" => $dashboard_reminders
        ));

    } catch(PDOException $e){
        echo json_encode(array("success" => false, "message" => "Error: " . $e->getMessage()));
    }

} else {
    echo json_encode(array("success" => false, "message" => "User ID required!"));
}
?>