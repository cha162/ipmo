<?php
session_start();
include 'config.php';

$collegeID = $_GET['CollegeID'];
$programID = $_GET['ProgramID'];

$mainApplications = array(); // Initialize the array

$query = "SELECT ma.*, maf.MFile_01, maf.MFile_02, maf.MFile_03, maf.MFile_04, maf.MFile_05, maf.MFile_06
          FROM mainapplication AS ma
          LEFT JOIN mainapplicationfile AS maf ON ma.MAppID = maf.MAppID
          LEFT JOIN users AS u ON ma.MUserID = u.MUserID
          WHERE ma.CollegeID = ? AND ma.ProgramID = ?
          ORDER BY ma.DateofSubmission DESC";  // Order by DateofSubmission in descending order

$stmt = $conn->prepare($query);
$stmt->bind_param('ii', $collegeID, $programID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $mainApplications[] = $row;
    }
}

echo json_encode($mainApplications);

$stmt->close();
$conn->close();
?>