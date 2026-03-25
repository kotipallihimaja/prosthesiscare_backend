<?php
// 1. Prevent any PHP warnings/errors from breaking the JSON output
error_reporting(0);
ini_set('display_errors', 0);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

// 2. Include your database connection (This defines $conn)
include_once 'db_connection.php';

// 3. Read the JSON data sent from Android
$data = json_decode(file_get_contents("php://input"));

if(!empty($data->email_or_name) && !empty($data->password)){

    $email_or_name = $data->email_or_name;
    $password = $data->password;

    try {
        // 4. Use MySQLi prepared statements (matches your $conn variable)
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? OR name = ?");
        $stmt->bind_param("ss", $email_or_name, $email_or_name);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0){
            $user = $result->fetch_assoc();

            // 5. Verify the password
            if(password_verify($password, $user['password'])){
                echo json_encode(array(
                    "success" => true,
                    "message" => "Login successful!",
                    "user" => array(
                        "id" => (int)$user['id'],
                        "name" => $user['name'],
                        "email" => $user['email'],
                        "phone" => $user['phone'],
                        "age" => $user['age'],
                        "date_of_birth" => $user['date_of_birth']
                    )
                ));
            } else {
                echo json_encode(array("success" => false, "message" => "Incorrect password!"));
            }
        } else {
            echo json_encode(array("success" => false, "message" => "User not found!"));
        }

    } catch(Exception $e){
        echo json_encode(array("success" => false, "message" => "Server Error: " . $e->getMessage()));
    }

} else {
    echo json_encode(array("success" => false, "message" => "Please enter email/username and password!"));
}
?>