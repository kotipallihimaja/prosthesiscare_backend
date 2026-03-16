<?php

$conn = new mysqli("localhost","root","","prosthesis_app");

$user_id = $_GET['user_id'];

$sql = "SELECT doctor_name, appointment_date, appointment_time
FROM appointments
WHERE user_id='$user_id'
AND appointment_date >= CURDATE()
ORDER BY appointment_date ASC
LIMIT 1";

$result = $conn->query($sql);

if($result->num_rows > 0){

    $row = $result->fetch_assoc();
    echo json_encode($row);

}else{

    echo json_encode(["doctor_name"=>"None"]);
}

?>