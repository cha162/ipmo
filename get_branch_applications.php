<?php
session_start();
include 'config.php';

$BranchID = $_GET['BranchID'];
$BCProgramID = $_GET['BCProgramID'];

$CBApplications = array(); // Initialize the array

$query = "SELECT fa.*, faf.CBFile_01, faf.CBFile_02, faf.CBFile_03, faf.CBFile_04, faf.CBFile_05, faf.CBFile_06
          FROM campusbranchesapplication AS fa
          LEFT JOIN campusbranchesapplicationfile AS faf ON fa.CBAppID = faf.CBAppID
          LEFT JOIN users AS u ON fa.CBUserID = u.MUserID
          WHERE fa.BranchID = ? AND fa.BCProgramID = ?
          ORDER BY fa.DateofSubmission DESC";  // Order by DateofSubmission in descending order

$stmt = $conn->prepare($query);
$stmt->bind_param('ii', $BranchID, $BCProgramID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $CBApplications[] = $row;
    }
}

echo json_encode($CBApplications);

$stmt->close();
$conn->close();
?>