<?php
session_start();
require_once 'config.php';

$query = "SELECT * FROM Branch";
$result = $conn->query($query);

$branches = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $branches[] = array(
            'BranchID' => $row['BranchID'],
            'BC_Name' => $row['BC_Name']
        );
    }
}

header('Content-Type: application/json');  // Set the response content type to JSON
echo json_encode($branches);  // Output the JSON-encoded array
?>
