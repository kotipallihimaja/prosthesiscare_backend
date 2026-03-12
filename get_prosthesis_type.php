<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Prosthesis types as per user's requirement
$prosthesis_types = array(
    "Fixed" => array(
        "Crown",
        "Bridge",
        "Implant"
    ),
    "Removable" => array(
        "Full Denture",
        "Partial Denture"
    )
);

echo json_encode(array(
    "success" => true,
    "prosthesis_types" => $prosthesis_types
));
?>