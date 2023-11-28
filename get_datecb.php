<?php
session_start();
require "config.php";

$startDate = $_GET['startDate'] ?? '';
$endDate = $_GET['endDate'] ?? '';

// Your SQL query
$query = "SELECT r.`CBRecordID`, r.`RegistrationNumber`, a.`Author`, a.`ThesisTitle`, a.`Adviser`, a.`DateOfSubmission`, a.`BC_Name` AS Branch, a.`BCProgram_Name` AS BCProgram
          FROM `campusbranchesrecords` r
          JOIN `campusbranchesapplication` a ON r.`CBFileID` = a.`CBAppID`
          JOIN `branch` b ON a.`BranchID` = b.`BranchID`
          JOIN `bcprogram` cb ON a.`BCProgramID` = cb.`BCProgramID`
          WHERE a.`DateOfSubmission` BETWEEN ? AND ?";


$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $startDate, $endDate);
$stmt->execute();
$result = $stmt->get_result();

// Construct table rows with fetched data
$rows = '';
while ($row = $result->fetch_assoc()) {
    $rows .= '<tr>';
    $rows .= '<td id="registration_' . $row['CBRecordID'] . '">' . $row['RegistrationNumber'] . '</td>';
    $rows .= '<td>' . $row['Author'] . '</td>';
    $rows .= '<td>' . $row['ThesisTitle'] . '</td>';
    $rows .= '<td>' . $row['Branch'] . '</td>';
    $rows .= '<td>' . $row['BCProgram'] . '</td>';
    $rows .= '<td>' . $row['Adviser'] . '</td>';
    $rows .= '<td>' . $row['DateOfSubmission'] . '</td>';
    $rows .= '<td><a href="#" class="edit-link" data-id="' . $row['CBRecordID'] . '">Edit</a></td>';
    $rows .= '</tr>';
}

echo $rows; // Send constructed table rows back as response
