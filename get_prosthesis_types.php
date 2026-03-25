<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Prosthesis types
$prosthesis_types = array(
    "Fixed" => array("Crown", "Bridge", "Implant"),
    "Removable" => array("Full Denture", "Partial Denture")
);


echo json_encode(array(
    "success" => true,
    "prosthesis_types" => $prosthesis_types
));
?>