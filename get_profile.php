<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->user_id)){
    $user_id = $data->user_id;

    try {
        $user_query = "SELECT id, name, email, phone, age, date_of_birth, address, created_at FROM users WHERE id = ?";
        $user_stmt = $connection->prepare($user_query);
        $user_stmt->execute([$user_id]);
        $user = $user_stmt->fetch(PDO::FETCH_ASSOC);

        if(!$user){
            echo json_encode(array("success" => false, "message" => "User not found!"));
            exit();
        }

        $member_since = date('F Y', strtotime($user['created_at']));

        // Get compliance score
        $compliance_query = "SELECT AVG(score) as avg_score FROM compliance_scores WHERE user_id = ?";
        $compliance_stmt = $connection->prepare($compliance_query);
        $compliance_stmt->execute([$user_id]);
        $compliance_data = $compliance_stmt->fetch(PDO::FETCH_ASSOC);
        $compliance_score = round($compliance_data['avg_score'] ?? 0);

        echo json_encode(array(
            "success" => true,
            "profile" => [
                "patient_id" => $user['id'],
                "name" => $user['name'],
                "email" => $user['email'],
                "phone" => $user['phone'],
                "age" => $user['age'],
                "date_of_birth" => $user['date_of_birth'],
                "address" => $user['address'] ?? '',
                "member_since" => $member_since,
                "compliance_score" => $compliance_score
            ]
        ));

    } catch(PDOException $e){
        echo json_encode(array("success" => false, "message" => "Error: " . $e->getMessage()));
    }
} else {
    echo json_encode(array("success" => false, "message" => "User ID required!"));
}
?>