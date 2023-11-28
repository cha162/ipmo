<?php
session_start();
require "config.php";

$startDate = $_GET['startDate'] ?? '';
$endDate = $_GET['endDate'] ?? '';

// Your SQL query
$query = "SELECT r.`MRecordID`, r.`RegistrationNumber`, a.`Author`, a.`ThesisTitle`, a.`Adviser`, a.`DateOfSubmission`, c.`College_Name` AS College, p.`Program_Name` AS Program
          FROM `mainapplicationrecords` r
          JOIN `mainapplication` a ON r.`MFileID` = a.`MAppID`
          JOIN `college` c ON a.`CollegeID` = c.`CollegeID`
          JOIN `program` p ON a.`ProgramID` = p.`ProgramID`
          WHERE a.`DateOfSubmission` BETWEEN ? AND ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $startDate, $endDate);
$stmt->execute();
$result = $stmt->get_result();

// Construct table rows with fetched data
$rows = '';
while ($row = $result->fetch_assoc()) {
    $rows .= '<tr>';
    $rows .= '<td id="registration_' . $row['MRecordID'] . '">' . $row['RegistrationNumber'] . '</td>';
    $rows .= '<td>' . $row['Author'] . '</td>';
    $rows .= '<td>' . $row['ThesisTitle'] . '</td>';
    $rows .= '<td>' . $row['College'] . '</td>';
    $rows .= '<td>' . $row['Program'] . '</td>';
    $rows .= '<td>' . $row['Adviser'] . '</td>';
    $rows .= '<td>' . $row['DateOfSubmission'] . '</td>';
    $rows .= '<td><a href="#" class="edit-link" data-id="' . $row['MRecordID'] . '">Edit</a></td>';
    $rows .= '</tr>';
}

echo $rows; // Send constructed table rows back as response
?>
