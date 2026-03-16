<?php

$conn = new mysqli("localhost","root","","prosthesis_app");

$result = $conn->query("SELECT * FROM daily_tasks ORDER BY task_order");

$tasks = array();

while($row = $result->fetch_assoc()){
    $tasks[]=$row;
}

echo json_encode($tasks);

?>