<?php
require 'config.php';

// Fetch count of unread notifications for the admin user
$query = "SELECT COUNT(*) AS unread_count FROM notification WHERE status = 'unread'";
$result = $conn->query($query);

$unread_count = 0; // Initialize count as 0
if ($result && $row = $result->fetch_assoc()) {
    $unread_count = $row['unread_count']; // Get the count of unread notifications
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" href="imgs/logo.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
   <link rel="stylesheet" href="css/home.css">
   <link rel="stylesheet" href="style.css">
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.bundle.min.js / bootstrap.bundle.js" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"></script>
   <style>
        .navbar{
            background-color: maroon;

        }
         #dropbtns {
            color: white;
            font-size: 16px;
            border: none;
            cursor: pointer;
            }

        .dropdowns {
            position: relative;
            display: inline-block;
            }

         .dropdowns-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
       
            }

            .dropdowns-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            }

            .dropdowns-content a:hover {background-color: gray; }

            .dropdowns:hover .dropdowns-content {
            display: block;
            
        }
   
         
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg fixed-top navbar-dark">
        
        <a class="navbar-brand" href="main.php"><img src="imgs/logo.png" alt="PUP Logo" style="height: 5%; width:5%; margin-left:7%;"> &nbsp;
         Intellectual Property Management Office
    </a>    
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false">
                <div class="dropdowns" >
                    <i class="fa fa-user" id="dropbtns" style="font-size:25px; margin-left:350px"></i></li>
                        <div class="dropdowns-content" style="margin-right: 10%">
                        <a href="logout.php" style="color: black;">Sign Out</a>
                </div>
        </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav mr-auto" > 
                </ul>

                <span class="badge badge-pill badge-danger"><?php echo $unread_count; ?></span>
                <i class="fa fa-bell" id="notificationBell" style="font-size: 18px; margin-right:20px; color:white; cursor: pointer;"></i> 
            </li>
        <span class="navbar-text">
                <div class="dropdowns">
                <i class="fa fa-user" id="dropbtns" style="font-size: 20px;"></i></li>
                        <div class="dropdowns-content">
                        <a href="logout.php" style="color: black;">Sign Out</a>
                </div>
                </div>
            </span>
    </div>            
</nav>
<!-- Container of Notification -->
<div class="card" id="notificationContainer" style="display: none; position: absolute; width: 30%; top: 60px; right: 20px; background-color: #fff; box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2); z-index: 100;">
    <div class="card-footer"> Show All Activities</div>
    <div id="notificationMessageContainer"></div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        // Function to fetch and display notifications
        function fetchNotifications() {
            $.ajax({
                url: 'fetch_notifications.php', // Replace with the actual URL to fetch notifications
                method: 'GET',
                success: function (response) {
                    $('#notificationContainer').html(response); // Display notifications in the container
                },
                error: function () {
                    console.error('Error fetching notifications.');
                }
            });
        }

        // Function to show/hide notifications on clicking the bell icon
        $('#notificationBell').click(function () {
            $('#notificationContainer').toggle(); // Toggle the display of notifications
            fetchNotifications(); // Fetch notifications when the bell icon is clicked
        });

        // Update the notification count to 0 when the notification container is clicked
        $('#notificationContainer').on('click', function () {
            $.ajax({
                type: 'POST',
                url: 'update_notification_counts.php',
                dataType: 'json', // Expect JSON response
                success: function (response) {
                    if (!response.error) {
                        // Update the badge count with the received unread count
                        $(".badge").text(response.unread_count);
                        // Update the message content
                        $(".notification_ul").html(response.message);
                        
                        // If count is 0, show the container
                        if (response.unread_count === 0) {
                            $('.notification_ul').show();
                        }
                    } else {
                        console.error('Error updating notification count:', response.error);
                    }
                },
                error: function (error) {
                    console.error('Error updating notification count:', error);
                }
            });
        });
        
    });
</script>


</body>
</html>