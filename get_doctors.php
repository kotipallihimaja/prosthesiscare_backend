<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include_once 'db_connection.php';

try {
    $query = "SELECT * FROM doctors WHERE is_available = 1 ORDER BY doctor_name";
    $stmt = $connection->prepare($query);
    $stmt->execute();
    $doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(array(
        "success" => true,
        "doctors" => $doctors
    ));

} catch(PDOException $e){
    echo json_encode(array(
        "success" => false, 
        "message" => "Error: " . $e->getMessage()
    ));
}
?>