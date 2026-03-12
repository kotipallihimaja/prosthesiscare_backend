<?php

include 'db_connection.php';

$user_id = $_POST['user_id'];
$title = $_POST['title'];
$date = $_POST['date'];
$time = $_POST['time'];
$status = $_POST['status'];

$query = "INSERT INTO reminder_history 
(user_id,title,date,time,status)
VALUES ('$user_id','$title','$date','$time','$status')";

mysqli_query($conn,$query);

echo json_encode(["success"=>true]);

?>