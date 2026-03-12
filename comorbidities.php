<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"));

if(
    !empty($data->user_id) &&
    !empty($data->diabetes) &&
    !empty($data->hypertension) &&
    !empty($data->hyperthyroidism) &&
    !empty($data->hypothyroidism) &&
    !empty($data->heart_disease) &&
    !empty($data->blood_disorder) &&
    !empty($data->immune_disorder) &&
    !empty($data->osteoporosis)
){

    $user_id = $data->user_id;
    $diabetes = $data->diabetes;
    $hypertension = $data->hypertension;
    $hyperthyroidism = $data->hyperthyroidism;
    $hypothyroidism = $data->hypothyroidism;
    $heart_disease = $data->heart_disease;
    $blood_disorder = $data->blood_disorder;
    $immune_disorder = $data->immune_disorder;
    $osteoporosis = $data->osteoporosis;
    $other = !empty($data->other) ? $data->other : '';

    try {
        // Check if user already has comorbidities saved
        $check = $connection->prepare("SELECT id FROM comorbidities WHERE user_id = ?");
        $check->execute([$user_id]);

        if($check->rowCount() > 0) {
            // Update existing record
            $query = "UPDATE comorbidities SET 
                      diabetes = ?, hypertension = ?, hyperthyroidism = ?,
                      hypothyroidism = ?, heart_disease = ?, blood_disorder = ?,
                      immune_disorder = ?, osteoporosis = ?, other = ?
                      WHERE user_id = ?";
            $stmt = $connection->prepare($query);
            $stmt->execute([$diabetes, $hypertension, $hyperthyroidism,
                          $hypothyroidism, $heart_disease, $blood_disorder,
                          $immune_disorder, $osteoporosis, $other, $user_id]);
        } else {
            // Insert new record
            $query = "INSERT INTO comorbidities 
                      (user_id, diabetes, hypertension, hyperthyroidism, 
                       hypothyroidism, heart_disease, blood_disorder, 
                       immune_disorder, osteoporosis, other) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $connection->prepare($query);
            $stmt->execute([$user_id, $diabetes, $hypertension, $hyperthyroidism,
                          $hypothyroidism, $heart_disease, $blood_disorder,
                          $immune_disorder, $osteoporosis, $other]);
        }

        echo json_encode(array(
            "success" => true,
            "message" => "Comorbidities saved successfully!"
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
        "message" => "Please fill in all required fields!"
    ));
}
?>