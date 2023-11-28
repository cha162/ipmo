<?php
require 'config.php';
session_start();

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // Assuming you receive the updated adviser data from the AJAX request
    $updatedAdviserData = $_POST['adviserData'];

    // Update the adviser data in the database
    $query = "UPDATE mainapplication SET Adviser_02 = ? WHERE MUserID = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("si", $updatedAdviserData, $userId);
        $stmt->execute();

        // Check if the update was successful
        if ($stmt->affected_rows > 0) {
            echo "Adviser data updated successfully.";
        } else {
            echo "Error: Unable to update adviser data.";
        }

        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "User not logged in.";
}

// Close the database connection
$conn->close();
?>
