<?php
session_start();
require "config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle the AJAX request to update the registration number in the database
    $recordID = $_POST['recordID'];
    $newRegistrationNumber = $_POST['registrationNumber'];

    // Perform the database update
    $query = "UPDATE `mainapplicationrecords` SET `RegistrationNumber` = '$newRegistrationNumber' WHERE `MRecordID` = $recordID";
    $result = $conn->query($query);

    if ($result) {
        // Return a success message if the update was successful
        echo json_encode(['status' => 'success', 'message' => 'Registration number updated successfully']);
    } else {
        // Return an error message if the update failed
        echo json_encode(['status' => 'error', 'message' => 'Failed to update registration number']);
    }

    // Close the database connection
    $conn->close();
}
