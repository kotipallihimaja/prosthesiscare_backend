<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Dental Prosthesis categories and sub-types
$prosthesis_types = array(
    "Fixed" => array("Crown", "Bridge", "Dental Implant", "Inlay / Onlay"),
    "Removable" => array("Full Denture", "Partial Denture", "Overdenture", "Flexible Denture")
);


echo json_encode(array(
    "success" => true,
    "prosthesis_types" => $prosthesis_types
));
?>