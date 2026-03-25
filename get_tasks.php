<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
include 'db_connection.php';

$user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;

$query = "
SELECT 
    t.id,
    t.task_name,
    IFNULL(ut.is_completed, 0) as is_completed
FROM daily_tasks t
LEFT JOIN user_tasks ut 
    ON t.id = ut.task_id 
    AND ut.user_id = $user_id
    AND ut.date = CURDATE()
ORDER BY t.id ASC
";

$result = $conn->query($query);
$tasks = [];

if($result) {
    while ($row = $result->fetch_assoc()) {
        $row['id'] = (int)$row['id'];
        $row['is_completed'] = (int)$row['is_completed'];
        $tasks[] = $row;
    }
    echo json_encode(["success" => true, "tasks" => $tasks]);
} else {
    echo json_encode(["success" => false, "message" => "Query failed"]);
}
?>