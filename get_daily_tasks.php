<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

$conn = new mysqli("localhost","root","","prosthesis_app");

if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed"]));
}

$sql = "SELECT * FROM daily_tasks ORDER BY task_order";

$result = $conn->query($sql);

$tasks = array();

if ($result->num_rows > 0) {

    while($row = $result->fetch_assoc()){
        $tasks[] = $row;
    }

    echo json_encode($tasks);

} else {

    echo json_encode([]);

}

$conn->close();

?>