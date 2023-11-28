<?php
session_start();
include 'config.php';

$collegeID = $_GET['CollegeID'];
$programID = $_GET['ProgramID'];

$mainRecords = array(); // Initialize the array

// Updated SQL query with parameters for filtering
$query = "SELECT r.`MRecordID`, r.`RegistrationNumber`, a.`Author`, a.`ThesisTitle`, a.`Adviser`, a.`DateOfSubmission`, c.`College_Name` AS College, p.`Program_Name` AS Program
          FROM `mainapplicationrecords` r
          JOIN `mainapplication` a ON r.`MFileID` = a.`MAppID`
          JOIN `users` u ON a.`MUserID` = u.`MUserID`
          JOIN `college` c ON u.`CollegeID` = c.`CollegeID`
          JOIN `program` p ON u.`ProgramID` = p.`ProgramID`
          WHERE a.`CollegeID` = ? AND a.`ProgramID` = ?"; // Filtering based on CollegeID and ProgramID

$stmt = $conn->prepare($query);
$stmt->bind_param('ii', $collegeID, $programID); // Bind parameters
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $mainRecords[] = $row;
}

echo json_encode($mainRecords);

$stmt->close();
$conn->close();

?>