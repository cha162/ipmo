<?php
session_start();
require_once 'config.php';

$query = "SELECT * FROM College";
$result = $conn->query($query);

$colleges = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $colleges[] = array(
            'CollegeID' => $row['CollegeID'],
            'College_Name' => $row['College_Name']
        );
    }
}

echo json_encode($colleges);
?>