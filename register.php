<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"));

if(
    !empty($data->name) &&
    !empty($data->email) &&
    !empty($data->phone) &&
    !empty($data->age) &&
    !empty($data->date_of_birth) &&
    !empty($data->password) &&
    !empty($data->confirm_password)
){

    $name = $data->name;
    $email = $data->email;
    $phone = $data->phone;
    $age = $data->age;
    $date_of_birth = $data->date_of_birth;
    $password = $data->password;
    $confirm_password = $data->confirm_password;

    // Password validations
    if($password !== $confirm_password){
        echo json_encode(array("success" => false, "message" => "Passwords do not match!"));
        exit();
    }

    if(strlen($password) < 8){
        echo json_encode(array("success" => false, "message" => "Password must be at least 8 characters!"));
        exit();
    }

    if(!preg_match('/[A-Z]/', $password)){
        echo json_encode(array("success" => false, "message" => "Password must have at least one CAPITAL letter!"));
        exit();
    }

    if(!preg_match('/[0-9]/', $password)){
        echo json_encode(array("success" => false, "message" => "Password must have at least one NUMBER!"));
        exit();
    }

    if(!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)){
        echo json_encode(array("success" => false, "message" => "Password must have at least one SPECIAL character!"));
        exit();
    }

    // Check if email exists
    $check_email = $connection->prepare("SELECT id FROM users WHERE email = ?");
    $check_email->execute([$email]);
    if($check_email->rowCount() > 0){
        echo json_encode(array("success" => false, "message" => "Email already registered!"));
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Insert user
        $query = "INSERT INTO users (name, email, phone, age, date_of_birth, password) VALUES (:name, :email, :phone, :age, :dob, :password)";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':age', $age);
        $stmt->bindParam(':dob', $date_of_birth);
        $stmt->bindParam(':password', $hashed_password);

        if($stmt->execute()){
            $user_id = $connection->lastInsertId();
            
            // AUTO-CREATE REMINDERS FOR NEW USER
            createRemindersForUser($connection, $user_id);
            
            // AUTO-CREATE COMORBIDITIES RECORD
            $comorb = $connection->prepare("INSERT INTO comorbidities (user_id) VALUES (?)");
            $comorb->execute([$user_id]);
            
            // AUTO-CREATE PROSTHESIS RECORD
            $prost = $connection->prepare("INSERT INTO prosthesis (user_id, prosthesis_type, prosthesis_name, installation_date, next_maintenance_date) VALUES (?, 'Not Selected', 'Not Selected', CURDATE(), CURDATE())");
            $prost->execute([$user_id]);
            
            // AUTO-CREATE USER SETTINGS
            $settings = $connection->prepare("INSERT INTO user_settings (user_id) VALUES (?)");
            $settings->execute([$user_id]);
            
            // AUTO-CREATE PRIVACY CONSENT
            $privacy = $connection->prepare("INSERT INTO privacy_consent (user_id) VALUES (?)");
            $privacy->execute([$user_id]);

            echo json_encode(array(
                "success" => true,
                "message" => "Registration successful! Reminders created automatically!",
                "user_id" => $user_id
            ));
        } else {
            echo json_encode(array("success" => false, "message" => "Registration failed!"));
        }

    } catch(PDOException $e){
        echo json_encode(array("success" => false, "message" => "Error: " . $e->getMessage()));
    }

} else {
    echo json_encode(array("success" => false, "message" => "Please fill in all fields!"));
}

// Function to create reminders for any user
function createRemindersForUser($connection, $user_id){
    // Daily Cleaning Reminder
    $query1 = "INSERT INTO reminders (user_id, reminder_title, reminder_time, reminder_type, frequency) VALUES (?, 'Morning Routine - Daily Cleaning', '09:00:00', 'daily', 'daily')";
    $stmt1 = $connection->prepare($query1);
    $stmt1->execute([$user_id]);
    $reminder1_id = $connection->lastInsertId();

    $tasks1 = ["Remove and rinse prosthesis", "Brush prosthesis gently", "Clean mouth and gums", "Soak in cleaning solution"];
    foreach($tasks1 as $index => $task){
        $task_query = "INSERT INTO daily_tasks (user_id, reminder_id, task_name, task_order) VALUES (?, ?, ?, ?)";
        $task_stmt = $connection->prepare($task_query);
        $task_stmt->execute([$user_id, $reminder1_id, $task, $index + 1]);
    }

    // Weekly Deep Cleaning Reminder
    $query2 = "INSERT INTO reminders (user_id, reminder_title, reminder_time, reminder_type, frequency) VALUES (?, 'Deep Cleaning', '10:00:00', 'weekly', 'weekly')";
    $stmt2 = $connection->prepare($query2);
    $stmt2->execute([$user_id]);
    $reminder2_id = $connection->lastInsertId();

    $tasks2 = ["Deep brush prosthesis", "Check for damage", "Soak overnight", "Floss around prosthesis"];
    foreach($tasks2 as $index => $task){
        $task_query = "INSERT INTO daily_tasks (user_id, reminder_id, task_name, task_order) VALUES (?, ?, ?, ?)";
        $task_stmt = $connection->prepare($task_query);
        $task_stmt->execute([$user_id, $reminder2_id, $task, $index + 1]);
    }
}
?>