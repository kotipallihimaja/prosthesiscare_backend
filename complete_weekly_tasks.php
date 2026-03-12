<?php

$conn = new mysqli("localhost","root","","prosthesis_care");

$user_id = $_POST['user_id'];

$check_query = "
SELECT * FROM task_history
WHERE user_id='$user_id'
AND task_type='weekly'
AND YEARWEEK(completed_date,1)=YEARWEEK(CURDATE(),1)
";

$result = $conn->query($check_query);

if($result->num_rows > 0){

echo json_encode([
"status"=>"error",
"message"=>"Weekly care already completed for this week"
]);

exit();

}

$tasks = [
"Deep clean with denture brush",
"Soak overnight in cleaning tablet",
"Inspect for cracks"
];

foreach($tasks as $task){

$sql = "INSERT INTO task_history(user_id,task_type,task_name)
VALUES('$user_id','weekly','$task')";

$conn->query($sql);

}

echo json_encode([
"status"=>"success",
"message"=>"Weekly care completed successfully"
]);

$conn->close();

?>