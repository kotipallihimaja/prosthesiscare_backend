<?php
header("Content-Type: application/json");
include "db_connection.php"; // this gives us $connection (PDO)

$filter = isset($_GET['filter']) ? $_GET['filter'] : "All";

try {

    // Base query
    $sql = "SELECT * FROM reminder_history";

    // Apply filter
    if ($filter == "Completed") {
        $sql .= " WHERE is_completed = 1";
    } elseif ($filter == "Missed") {
        $sql .= " WHERE is_completed = 0";
    }

    $sql .= " ORDER BY reminder_date DESC";

    // Prepare and execute query
    $stmt = $connection->prepare($sql);
    $stmt->execute();

    $reminders = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $reminders[] = [
            "id" => $row["id"],
            "title" => $row["title"],
            "date" => date("M d, Y", strtotime($row["reminder_date"])),
            "time" => $row["reminder_time"] 
                        ? date("g:i A", strtotime($row["reminder_time"])) 
                        : null,
            "isCompleted" => (bool)$row["is_completed"]
        ];
    }

    echo json_encode([
        "status" => true,
        "data" => $reminders
    ]);

} catch (PDOException $e) {

    echo json_encode([
        "status" => false,
        "message" => "Error fetching reminders",
        "error" => $e->getMessage()
    ]);
}

?>