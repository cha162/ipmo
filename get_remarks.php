<?php
session_start();
require 'config.php';

$selectedStatus = $_GET['status'];  // Get the selected status from the query string

$whereClause = "";
if ($selectedStatus && $selectedStatus !== 'all') {
    $whereClause = "WHERE Remarks = '$selectedStatus'";
}

$query = "SELECT * FROM mainapplication $whereClause";
$result = $conn->query($query);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

$conn->close();

echo json_encode($data);
?>