<?php
require 'config.php'; // Include your database configuration file

session_start();

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    $query = "SELECT DISTINCT Adviser, Adviser_02, Adviser_03, Adviser_04, Adviser_05 FROM campusbranchesapplication WHERE CBUserID = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result) {
            $adviserDataArray = [];
            while ($row = $result->fetch_assoc()) {
                // Loop through each adviser column and add to the array
                foreach ($row as $adviser) {
                    if ($adviser !== null) {
                        $adviserDataArray[] = $adviser;
                    }
                }
            }

            echo json_encode($adviserDataArray);
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
