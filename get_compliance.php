<?php
header("Content-Type: application/json");
include "db_connection.php";

$totalQuery = "SELECT COUNT(*) as total FROM reminder_history";
$completedQuery = "SELECT COUNT(*) as completed FROM reminder_history WHERE is_completed = 1";

$totalResult = $conn->query($totalQuery)->fetch_assoc();
$completedResult = $conn->query($completedQuery)->fetch_assoc();

$total = $totalResult['total'];
$completed = $completedResult['completed'];
$missed = $total - $completed;

$percentage = $total > 0 ? round(($completed / $total) * 100) : 0;

echo json_encode([
    "status" => true,
    "compliance_percentage" => $percentage,
    "completed" => $completed,
    "missed" => $missed
]);

$conn->close();
?>