<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->user_id)){

    $user_id = $data->user_id;

    try {
        // Check if reminders already exist
        $check = $connection->prepare("SELECT id FROM reminders WHERE user_id = ?");
        $check->execute([$user_id]);
        
        if($check->rowCount() > 0){
            echo json_encode(array(
                "success" => true,
                "message" => "Reminders already exist for this user"
            ));
            exit();
        }

        // Create Daily Cleaning Reminder (9:00 AM)
        $query1 = "INSERT INTO reminders (user_id, reminder_title, reminder_time, reminder_type, frequency) 
                   VALUES (?, 'Morning Routine - Daily Cleaning', '09:00:00', 'daily', 'daily')";
        $stmt1 = $connection->prepare($query1);
        $stmt1->execute([$user_id]);
        $reminder1_id = $connection->lastInsertId();

        // Create tasks for Daily Cleaning
        $tasks1 = [
            "Remove and rinse prosthesis",
            "Brush prosthesis gently",
            "Clean mouth and gums",
            "Soak in cleaning solution"
        ];
        
        foreach($tasks1 as $index => $task){
            $task_query = "INSERT INTO daily_tasks (user_id, reminder_id, task_name, task_order) 
                           VALUES (?, ?, ?, ?)";
            $task_stmt = $connection->prepare($task_query);
            $task_stmt->execute([$user_id, $reminder1_id, $task, $index + 1]);
        }

        // Create Weekly Deep Cleaning Reminder (10:00 AM)
        $query2 = "INSERT INTO reminders (user_id, reminder_title, reminder_time, reminder_type, frequency) 
                   VALUES (?, 'Deep Cleaning', '10:00:00', 'weekly', 'weekly')";
        $stmt2 = $connection->prepare($query2);
        $stmt2->execute([$user_id]);
        $reminder2_id = $connection->lastInsertId();

        // Create tasks for Deep Cleaning
        $tasks2 = [
            "Deep brush prosthesis",
            "Check for damage",
            "Soak overnight",
            "Floss around prosthesis"
        ];
        
        foreach($tasks2 as $index => $task){
            $task_query = "INSERT INTO daily_tasks (user_id, reminder_id, task_name, task_order) 
                           VALUES (?, ?, ?, ?)";
            $task_stmt = $connection->prepare($task_query);
            $task_stmt->execute([$user_id, $reminder2_id, $task, $index + 1]);
        }

        echo json_encode(array(
            "success" => true,
            "message" => "Reminders and tasks created successfully!",
            "reminders_created" => 2
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