<?php
session_start();
require_once 'config.php';

// Check if 'BranchID' key exists in the $_GET array
if (isset($_GET['BranchID'])) {
    $branchID = $_GET['BranchID'];

    $query = "SELECT * FROM BCProgram WHERE BranchID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $branchID);
    $stmt->execute();
    $result = $stmt->get_result();

    $bcPrograms = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $bcPrograms[] = array(
                'BCProgramID' => $row['BCProgramID'],
                'BCProgram_Name' => $row['BCProgram_Name']
            );
        }
    }

    echo json_encode($bcPrograms);
} else {
    // Handle the case where 'BranchID' is not provided in the request
    echo json_encode(array('error' => 'BranchID not provided'));
}
?>