<?php

$conn = new mysqli("localhost","root","","prosthesis_care");

$user_id = $_POST['user_id'];

$check_query = "
SELECT * FROM task_history
WHERE user_id='$user_id'
AND task_type='monthly'
AND MONTH(completed_date)=MONTH(CURDATE())
AND YEAR(completed_date)=YEAR(CURDATE())
";

$result = $conn->query($check_query);

if($result->num_rows > 0){

echo json_encode([
"status"=>"error",
"message"=>"Monthly care already completed"
]);

exit();

}

$tasks = [
"Professional cleaning solution soak",
"Check fit and comfort",
"Inspect adhesive and supplies"
];

foreach($tasks as $task){

$sql = "INSERT INTO task_history(user_id,task_type,task_name)
VALUES('$user_id','monthly','$task')";

$conn->query($sql);

}

echo json_encode([
"status"=>"success",
"message"=>"Monthly care completed"
]);

$conn->close();

?>