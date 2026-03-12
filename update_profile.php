<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"));

if(
    !empty($data->user_id) &&
    !empty($data->name) &&
    !empty($data->email) &&
    !empty($data->phone)
){

    $user_id = $data->user_id;
    $name = $data->name;
    $email = $data->email;
    $phone = $data->phone;
    $age = !empty($data->age) ? $data->age : null;
    $date_of_birth = !empty($data->date_of_birth) ? $data->date_of_birth : null;
    $address = !empty($data->address) ? $data->address : null;

    try {
        // Check if email is used by another user
        $check_email = $connection->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $check_email->execute([$email, $user_id]);
        
        if($check_email->rowCount() > 0){
            echo json_encode(array("success" => false, "message" => "Email already in use by another user!"));
            exit();
        }

        $query = "UPDATE users SET name = ?, email = ?, phone = ?, age = ?, date_of_birth = ?, address = ? WHERE id = ?";
        $stmt = $connection->prepare($query);
        $stmt->execute([$name, $email, $phone, $age, $date_of_birth, $address, $user_id]);

        echo json_encode(array(
            "success" => true, 
            "message" => "Profile updated successfully!"
        ));

    } catch(PDOException $e){
        echo json_encode(array("success" => false, "message" => "Error: " . $e->getMessage()));
    }

} else {
    echo json_encode(array("success" => false, "message" => "Please fill in all required fields (Name, Email, Phone)!"));
}
?>