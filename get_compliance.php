<?php
header("Content-Type: application/json");


date_default_timezone_set('Asia/Kolkata');

include "db_connection.php";

$user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;


$today = date('Y-m-d');


$totalQuery = "SELECT COUNT(*) as total FROM reminder_history WHERE user_id = $user_id";
$completedQuery = "SELECT COUNT(*) as completed FROM reminder_history WHERE user_id = $user_id AND is_completed = 1";


$dailyCheckQuery = "SELECT COUNT(*) as daily_done FROM reminder_history 
                    WHERE user_id = $user_id AND title = 'Daily Cleaning' AND date = '$today' AND is_completed = 1";

// Deep Cleaning Status (Last 7 days)
$deepCheckQuery = "SELECT COUNT(*) as deep_done FROM reminder_history 
                   WHERE user_id = $user_id AND title = 'Deep Cleaning' 
                   AND date >= DATE_SUB('$today', INTERVAL 7 DAY) AND is_completed = 1";

$totalResult = $conn->query($totalQuery)->fetch_assoc();
$completedResult = $conn->query($completedQuery)->fetch_assoc();
$dailyResult = $conn->query($dailyCheckQuery)->fetch_assoc();
$deepResult = $conn->query($deepCheckQuery)->fetch_assoc();

$total = $totalResult['total'];
$completed = $completedResult['completed'];
$missed = max(0, $total - $completed);
$is_daily_done = $dailyResult['daily_done'] > 0;
$is_deep_done = $deepResult['deep_done'] > 0;

$percentage = $total > 0 ? round(($completed / $total) * 100) : 0;

echo json_encode([
    "status" => true,
    "compliance_percentage" => (int)$percentage,
    "completed" => (int)$completed,
    "missed" => (int)$missed,
    "is_daily_completed" => $is_daily_done,
    "is_deep_completed" => $is_deep_done
]);

$conn->close();
?>