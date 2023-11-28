<?php
session_start();
include 'config.php';

$branchID = $_GET['BranchID'];
$bcProgramID = $_GET['BCProgramID'];

$branchRecords = array(); // Initialize the array

// Updated SQL query with parameters for filtering
$query = "SELECT r.`CBRecordID`, r.`RegistrationNumber`, a.`Author`, a.`ThesisTitle`, a.`Adviser`, a.`DateOfSubmission`, b.`BC_Name` AS Branch, bc.`BCProgram_Name` AS Program
FROM `campusbranchesrecords` r
JOIN `campusbranchesapplication` a ON r.`CBFileID` = a.`CBAppID`
JOIN `branch` b ON a.`BranchID` = b.`BranchID`
JOIN `bcprogram` bc ON a.`BCProgramID` = bc.`BCProgramID`
WHERE a.`BranchID` = ? AND a.`BCProgramID` = ?";


$stmt = $conn->prepare($query);
$stmt->bind_param('ii', $branchID, $bcProgramID); // Bind parameters
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $branchRecords[] = $row;
}

echo json_encode($branchRecords);

$stmt->close();
$conn->close();
?>