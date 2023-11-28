<?php
require 'config.php';

$query = "SELECT campus FROM users WHERE MUserID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $studentId); // Assuming $studentId is an integer
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $student = $result->fetch_assoc();

    // Set the link destination based on the fetched campus value
    $linkDestination = '';
    if ($student["campus"] === "Main") {
        $linkDestination = 'main.php';
    } elseif ($student["campus"] === "Branch") {
        $linkDestination = 'branch.php';
    } else {
        $error_message = "Invalid campus";
        // Handle any other condition or set an error message
    }

    // Output the anchor tag with the dynamically set href attribute
    if ($linkDestination !== '') {
        echo '<a class="navbar-brand" href="' . $linkDestination . '"></a>';
    } else {
        echo 'Error: Invalid campus or link destination not set.';
    }
} else {
    // Handle case where no student with the given ID was found
    $error_message = "Student not found";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" href="imgs/logo.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.bundle.min.js / bootstrap.bundle.js" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
   
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
        .forms{
            text-decoration: none;
            color: white;
            margin-right: 10px;
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
        
        <a class="navbar-brand" href="main.php"><img src="imgs/logo.png" alt="PUP Logo" style="height: 5%; width:5%; margin-left: 7%;"> &nbsp; 
        Intellectual Property Management Office
    </a>    
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false">
                <div class="dropdowns" >
                    <i class="fa fa-user" id="dropbtns" style="font-size:25px; margin-left:350px"></i></li>
                        <div class="dropdowns-content" style="margin-right: 10%">
                        <a href="profile.php" style="color: black;">Profile</a>
                        <a href="logout.php" style="color: black;">Sign Out</a>
                </div>
     
              

        </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav mr-auto" >
                    
                </ul>
                <!-- Modify the Remarks and Status Modal <p style="color: #f9f9f9; margin-right:5%; margin-top:2%; "><?php echo "UserID: " . $_SESSION['user_id']; ?></p>
         -->
         <a href="https://onedrive.live.com/?authkey=%21AGDmxFXDDmRbB5s&id=C5FB49234DD366A6%212286&cid=C5FB49234DD366A6" class="forms"> Download Copyright Procedures and Forms </a>
        <span class="navbar-text">
                <div class="dropdowns" >
                    <i class="fa fa-user" id="dropbtns" style="font-size: 25px;"></i></li>
                        <div class="dropdowns-content">
                        <a href="profile.php" style="color: black;">Profile</a>
                        <a href="logout.php" style="color: black;">Sign out</a>
                </div>
                </div>
            </span>
              
</nav>

</body>
</html>