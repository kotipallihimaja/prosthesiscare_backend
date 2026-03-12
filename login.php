<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"));

if(
    !empty($data->email_or_name) &&
    !empty($data->password)
){

    $email_or_name = $data->email_or_name;
    $password = $data->password;

    try {

        $query = "SELECT * FROM users WHERE email = :input OR name = :input";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':input', $email_or_name);
        $stmt->execute();

        if($stmt->rowCount() > 0){

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if(password_verify($password, $user['password'])){

                echo json_encode(array(
                    "success" => true,
                    "message" => "Login successful! Welcome back!",
                    "user" => array(
                        "id" => $user['id'],
                        "name" => $user['name'],
                        "email" => $user['email'],
                        "phone" => $user['phone'],
                        "age" => $user['age'],
                        "date_of_birth" => $user['date_of_birth']
                    )
                ));

            } else {

                echo json_encode(array( 
                    "success" => false,
                    "message" => "Incorrect password!"
                ));
            }

        } else {

            echo json_encode(array(
                "success" => false,
                "message" => "User not found!"
            ));
        }

    } catch(PDOException $e){

        echo json_encode(array(
            "success" => false,
            "message" => "Error: " . $e->getMessage()
        ));
    }

} else {

    echo json_encode(array(
        "success" => false,
        "message" => "Please enter email/username and password!"
    ));
}
?>