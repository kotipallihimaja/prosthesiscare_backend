<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

include_once 'db_connection.php';

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->user_id)){

    $user_id = $data->user_id;

    try {
        // Get comorbidities
        $query = "SELECT * FROM comorbidities WHERE user_id = ?";
        $stmt = $connection->prepare($query);
        $stmt->execute([$user_id]);
        
        if($stmt->rowCount() > 0){
            $comorbidities = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Check if any comorbidity is "Yes"
            $risk_conditions = [];
            $has_risk = false;
            
            $conditions = [
                'diabetes' => 'Diabetes',
                'hypertension' => 'Hypertension',
                'hyperthyroidism' => 'Hyperthyroidism',
                'hypothyroidism' => 'Hypothyroidism',
                'heart_disease' => 'Heart Disease',
                'blood_disorder' => 'Blood Disorder',
                'immune_disorder' => 'Immune Disorder',
                'osteoporosis' => 'Osteoporosis'
            ];
            
            foreach($conditions as $db_field => $display_name){
                if(isset($comorbidities[$db_field]) && $comorbidities[$db_field] == 'Yes'){
                    $risk_conditions[] = $display_name;
                    $has_risk = true;
                }
            }
            
            echo json_encode(array(
                "success" => true,
                "has_risk" => $has_risk,
                "risk_level" => $has_risk ? "High Risk" : "Normal",
                "risk_color" => $has_risk ? "red" : "green",
                "risk_conditions" => $risk_conditions,
                "message" => $has_risk ? 
                    "Patient has " . count($risk_conditions) . " condition(s) that may affect dental treatment" : 
                    "No comorbidities reported"
            ));
            
        } else {
            echo json_encode(array(
                "success" => true,
                "has_risk" => false,
                "risk_level" => "Unknown",
                "risk_color" => "gray",
                "risk_conditions" => [],
                "message" => "No comorbidities data found"
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
        "message" => "User ID required!"
    ));
}
?>