<?php

$conn = new mysqli("localhost","root","","prosthesis_app");

$user_id = $_POST['user_id'];
$task_id = $_POST['task_id'];

$date = date("Y-m-d");

$sql="INSERT INTO task_completions(user_id,task_id,completed,date)
VALUES('$user_id','$task_id',1,'$date')";

$conn->query($sql);

echo json_encode(["status"=>"success"]);

?>