<?php
require 'config.php'; // Include your database configuration file

session_start();

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    $query = "SELECT DISTINCT Author, Author_02, Author_03, Author_04, Author_05, Author_06, Author_07, Author_08, Author_09, Author_10 FROM campusbranchesapplication WHERE CBUserID = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result) {
            $authorDataArray = [];
            while ($row = $result->fetch_assoc()) {
                // Loop through each author column and add to the array
                foreach ($row as $author) {
                    if ($author !== null) {
                        $authorDataArray[] = $author;
                    }
                }
            }

            echo json_encode($authorDataArray);
        } else {
            echo "Error: " . $stmt->error;
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
