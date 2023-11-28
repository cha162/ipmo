<?php
require 'config.php';
session_start();

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // Assuming you receive the updated author data from the AJAX request
    $updatedAuthorData = $_POST['authorData'];

    // Update the author data in the database
    $query = "UPDATE mainapplication SET Author_02 = ? WHERE MUserID = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("si", $updatedAuthorData, $userId);
        $stmt->execute();

        // Check if the update was successful
        if ($stmt->affected_rows > 0) {
            echo "Author data updated successfully.";
        } else {
            echo "Error: Unable to update author data.";
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
