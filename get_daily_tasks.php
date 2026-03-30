<?php
include_once 'db_connection.php';
header("Content-Type: application/json");

$user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;
$today = date('Y-m-d');

if ($user_id > 0) {
    // Join with user_tasks to see if user completed these today
    $sql = "SELECT dt.id, dt.task_name, dt.task_order, COALESCE(ut.is_completed, 0) as is_completed 
            FROM daily_tasks dt 
            LEFT JOIN user_tasks ut ON dt.id = ut.task_id AND ut.user_id = $user_id AND ut.date = '$today'
            ORDER BY dt.task_order";
} else {
    $sql = "SELECT id, task_name, task_order, 0 as is_completed FROM daily_tasks ORDER BY task_order";
}

$result = $conn->query($sql);
$tasks = array();

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()){
        // Cast types
        $row['id'] = (int)$row['id'];
        $row['task_order'] = (int)$row['task_order'];
        $row['is_completed'] = (int)$row['is_completed'];
        $tasks[] = $row;
    }
}

echo json_encode($tasks);
$conn->close();
?>