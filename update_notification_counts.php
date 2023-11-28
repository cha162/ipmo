<?php
session_start();
require 'config.php'; // Include your database connection configuration

// Assuming you have a notifications table with a column named status
$updateQuery = "UPDATE notification SET status = 'read' WHERE status = 'unread'";
$updateResult = $conn->query($updateQuery);

if ($updateResult) {
    

    
    // Fetch the count of unread notifications after updating
    $countQuery = "SELECT COUNT(*) AS unread_count FROM notification WHERE status = 'unread'";
    $countResult = $conn->query($countQuery);

    if ($countResult && $row = $countResult->fetch_assoc()) {
        $unread_count = $row['unread_count'];
        echo json_encode(['unread_count' => $unread_count, 'message' => $message]);
    } else {
        echo json_encode(['error' => 'Error fetching notification count.']);
    }
} else {
    echo json_encode(['error' => 'Error updating notifications.']);
}

$conn->close();
?>
