<?php
session_start();
require_once 'config.php';

if (isset($_GET['CollegeID'])) {
    $collegeID = $_GET['CollegeID'];

    $query = "SELECT * FROM program WHERE CollegeID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $collegeID);
    $stmt->execute();
    $result = $stmt->get_result();

    $programs = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $programs[] = array(
                'ProgramID' => $row['ProgramID'],
                'Program_Name' => $row['Program_Name']
            );
        }
    }

    echo json_encode($programs);
} else {
    echo json_encode(array());
}
?>