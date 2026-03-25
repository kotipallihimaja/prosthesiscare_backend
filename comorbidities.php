<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

// మీ db_connection.php ని ఇక్కడ ఇంక్లూడ్ చేస్తున్నాము
include_once 'db_connection.php';

// JSON డేటాని చదవడం
$data = json_decode(file_get_contents("php://input"));

if(!$data){
    $data = (object) $_POST;
}

// యూజర్ ఐడి చెక్
$user_id = $data->user_id ?? null;

if(!$user_id){
    echo json_encode(["success" => false, "message" => "User ID missing"]);
    exit();
}

// డేటాని వేరియబుల్స్ లోకి తీసుకోవడం
$diabetes = $data->diabetes ?? "No";
$hypertension = $data->hypertension ?? "No";
$hyperthyroidism = $data->hyperthyroidism ?? "No";
$hypothyroidism = $data->hypothyroidism ?? "No";
$heart_disease = $data->heart_disease ?? "No";
$blood_disorder = $data->blood_disorder ?? "No";
$immune_disorder = $data->immune_disorder ?? "No";
$osteoporosis = $data->osteoporosis ?? "No";
$other = $data->other ?? "";

try {
    
    $check = $conn->prepare("SELECT id FROM comorbidities WHERE user_id = ?");
    $check->bind_param("i", $user_id);
    $check->execute();
    $check->store_result();
    
    if($check->num_rows > 0) {
        
        $stmt = $conn->prepare("UPDATE comorbidities SET diabetes=?, hypertension=?, hyperthyroidism=?, hypothyroidism=?, heart_disease=?, blood_disorder=?, immune_disorder=?, osteoporosis=?, other=? WHERE user_id=?");
        $stmt->bind_param("sssssssssi", $diabetes, $hypertension, $hyperthyroidism, $hypothyroidism, $heart_disease, $blood_disorder, $immune_disorder, $osteoporosis, $other, $user_id);
    } else {
        
        $stmt = $conn->prepare("INSERT INTO comorbidities (user_id, diabetes, hypertension, hyperthyroidism, hypothyroidism, heart_disease, blood_disorder, immune_disorder, osteoporosis, other) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssssss", $user_id, $diabetes, $hypertension, $hyperthyroidism, $hypothyroidism, $heart_disease, $blood_disorder, $immune_disorder, $osteoporosis, $other);
    }

    if($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Comorbidities saved successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Database error: " . $conn->error]);
    }
    
    $stmt->close();
    $check->close();

} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Server Error: " . $e->getMessage()]);
}
?>