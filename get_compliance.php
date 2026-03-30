<?php
header("Content-Type: application/json");


date_default_timezone_set('Asia/Kolkata');

include "db_connection.php";

$user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;

$today = date('Y-m-d');

// 1. Reminders Compliance
$remindersTotalQuery = "SELECT COUNT(*) as total FROM reminder_history WHERE user_id = $user_id";
$remindersDoneQuery = "SELECT COUNT(*) as completed FROM reminder_history WHERE user_id = $user_id AND is_completed = 1";

// 2. Daily Tasks Compliance (for today)
$tasksTotalQuery = "SELECT COUNT(*) as total FROM daily_tasks";
$tasksDoneQuery = "SELECT COUNT(*) as completed FROM user_tasks WHERE user_id = $user_id AND date = '$today' AND is_completed = 1";

$remTotalRes = $conn->query($remindersTotalQuery)->fetch_assoc();
$remDoneRes = $conn->query($remindersDoneQuery)->fetch_assoc();
$taskTotalRes = $conn->query($tasksTotalQuery)->fetch_assoc();
$taskDoneRes = $conn->query($tasksDoneQuery)->fetch_assoc();

$remTotal = (int)($remTotalRes['total'] ?? 0);
$remDone = (int)($remDoneRes['completed'] ?? 0);
$taskTotal = (int)($taskTotalRes['total'] ?? 0);
$taskDone = (int)($taskDoneRes['completed'] ?? 0);

// Total counts for overall percentage
$total = $remTotal + $taskTotal;
$completed = $remDone + $taskDone;
$missed = max(0, $total - $completed);

$is_daily_done = ($taskTotal > 0 && $taskDone >= $taskTotal);

// Deep Cleaning status (any deep cleaning in last 7 days)
$deepCheckQuery = "SELECT COUNT(*) as deep_done FROM reminder_history 
                   WHERE user_id = $user_id AND title LIKE '%Deep%' 
                   AND date >= DATE_SUB('$today', INTERVAL 7 DAY) AND is_completed = 1";
$deepResult = $conn->query($deepCheckQuery)->fetch_assoc();
$is_deep_done = (int)($deepResult['deep_done'] ?? 0) > 0;

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