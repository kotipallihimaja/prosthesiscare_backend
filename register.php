<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"));

if(
    !empty($data->name) &&
    !empty($data->email) &&
    !empty($data->phone) &&
    !empty($data->age) &&
    !empty($data->date_of_birth) &&
    !empty($data->password)
){
    $name = $data->name;
    $email = $data->email;
    $phone = $data->phone;
    $age = $data->age;
    $dob = $data->date_of_birth;
    $password = password_hash($data->password, PASSWORD_DEFAULT);

    // 1. Email ఉందో లేదో చెక్ చేయడం
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();
    if($check->num_rows > 0){
        echo json_encode(["success" => false, "message" => "Email already registered!"]);
        exit;
    }
    $check->close();

    
    $stmt = $conn->prepare("INSERT INTO users (name, email, phone, age, date_of_birth, password) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $email, $phone, $age, $dob, $password);

    if($stmt->execute()){
        $user_id = $conn->insert_id;

        
        $conn->query("INSERT IGNORE INTO comorbidities (user_id) VALUES ($user_id)");

        // 4. Prosthesis
        $conn->query("INSERT IGNORE INTO prosthesis (user_id, prosthesis_type, prosthesis_name) VALUES ($user_id, 'Not Selected', 'Not Selected')");

        
        echo json_encode([
            "success" => true,
            "message" => "Registration successful!",
            "user_id" => $user_id
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Registration failed!"]);
    }
    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Please fill all fields!"]);
}
?>