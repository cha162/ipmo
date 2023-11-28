<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <style>
        body{
            text-decoration: none;
            list-style: none;
            box-sizing: border-box;
            font-family: 'Montserrat';
        }
        .popup {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
        }
        .inner_popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #fff;
            padding: 20px;
            max-width: 400px;
            width: 100%;
            text-align: center;
            border-radius: 8px;
            max-height: 400px; /* Set a fixed maximum height */
            overflow-y: scroll; /* Use 'auto' or 'scroll' based on your preference */
        }

        .inner_popup::-webkit-scrollbar {
            width: 12px;
        }

        .inner_popup::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .inner_popup::-webkit-scrollbar-thumb {
            background-color: #888;
        }

        .notification_ul {
            list-style: none;
            padding: 0;
        }

        .notification_ul h6 {
            margin: 0;
            padding: 10px;
            border-bottom: 1px solid #ccc;
            text-align: left;
        }

        .notification_ul .time-submitted {
            margin-top: 5px;
            font-size: 12px;
            color: #888;
        }

        .show_all {
            text-align: center;
            margin-top: 10px;
            cursor: pointer;
            color: blue;
        }

        .show_all:hover {
            text-decoration: underline;
        }

        .close {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 20px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<?php
require 'config.php';

$query = "SELECT * FROM notification WHERE status = 'unread' ORDER BY time_submitted DESC LIMIT 10";
$result = $conn->query($query);

if ($result) {
    echo '<ul class="notification_ul">';
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<li >';
            echo '<h6><i class="fa fa-file-text" style="font-size:15px;"></i> ' . $row['message'] . '</h6>';
            echo '<p class="text-muted time-submitted" style="text-align: left;">' . $row['time_submitted'] . '</p>';
            echo '</li>';
        }
        echo '</ul>';
        echo '<div class="show_all" onclick="showPopup()">Show All Notification</div>';
    } else {
        echo '<i class="fa fa-file-text" style="font-size: 15px;"></i> No new notifications';
        echo '<hr>';
        echo '<div class="show_all" onclick="showPopup()">Show Previous Notification</div>';
    }
} else {
    echo '<div class="alert alert-danger" role="alert">';
    echo 'Error fetching notifications: ' . $conn->error;
    echo '</div>';
}
?>

<div class="popup" id="popup">
    <div class="inner_popup" >
        <h3>All Notifications</h3>
        <?php
        $result = $conn->query("SELECT * FROM notification ORDER BY time_submitted DESC");

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<p style="text-align: left;"><i class= "fa fa-users" style="font-size:20px;"></i> ' . $row['message'] . '</p>';
                echo '<p class="text-muted time-submitted" style="text-align: left;">' . $row['time_submitted'] . '</p>';
                echo '<hr>';
            }
        } else {
            echo '<p>No notifications available.</p>';
        }
        ?>
        <span class="close" onclick="hidePopup">&times;</span>
    </div>
</div>

<script>
    function showPopup() {
        $(".popup").show();
    }

    function hidePopup() {
        $(".popup").hide();
    }
    
    // Add a click event listener for the close button
    $(".popup .inner_popup .close").on("click", function() {
        hidePopup();
    });
</script>

</body>
</html>
