<?php
header("Content-Type: application/json");
include "db_connection.php";

$data = json_decode(file_get_contents("php://input"), true);

$title = $data['title'];
$date = $data['date'];
$time = $data['time'];
$isCompleted = $data['isCompleted'];

$stmt = $conn->prepare("INSERT INTO reminder_history (title, reminder_date, reminder_time, is_completed) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sssi", $title, $date, $time, $isCompleted);

if ($stmt->execute()) {
    echo json_encode(["status" => true, "message" => "Reminder added successfully"]);
} else {
    echo json_encode(["status" => false, "message" => "Failed to add reminder"]);
}

$stmt->close();
$conn->close();

?>