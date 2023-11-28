<?php
session_start();
require 'config.php';


if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if (isset($_SESSION["logged_out"]) && $_SESSION["logged_out"] === true) {
    header('location:login.php');
    exit();
}


$studentId = $_SESSION["user_id"]; // Assuming the user_id is the student's ID

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the form is submitted
    $newEmail = $_POST["email"];

    // Update the email in the database
    $updateQuery = "UPDATE users SET email = ? WHERE MUserID = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param('ss', $newEmail, $studentId);
    $updateStmt->execute();
    $updateStmt->close();

    // Optionally, you can also update the session variable with the new email
    $_SESSION["email"] = $newEmail;

    // Redirect to a page or perform any other actions after the update
    // You can modify the header location based on your application's structure
    header("Location: profile.php");
    exit();
}

$query = "SELECT MUserID, Student_Number, Student_Name, email FROM users WHERE MUserID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $studentId);
$stmt->execute();
$stmt->bind_result($MUserID, $student_number, $student_name, $email);
$stmt->fetch();
$stmt->close();
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
             .card-footer{
                position: fixed;
                left: 0;
                bottom: 0;
                width: 100%;
                text-align: center;
            }
            .card{
             width: 100%;
             margin-left: 27%;
             margin-top: 10%;
             background: #f2f2f2;
            }
            
            .btn{
                background:maroon;
                color:white;
            }
            .card-link{
                color:maroon;
                line-height: 3;
            }
            
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
<?php include 'new-header.php'; ?>
<!-- BODY -->
<div class="card" style="width: 40rem;">
  <div class="card-body">   
    <div class="card-title">
            <h3 >Update Email</h3>
    </div>
    <form method="post">
         <div class="form-group">
                    <label  class="col-sm form-label">Student Number</label>
                    <input type="text" class="form-control" id="student_number" name="student_number" aria-describedby="emailHelp" value="<?php echo $student_number; ?>" disabled>
            </div>

            <div class="form-group">
                    <label class="col-sm form-label">Name</label>
                    <input type="text" class="form-control" id="student_name" name="student_name" placeholder="Name" value="<?php echo $student_name; ?>" disabled>
            </div>

                <div class="form-group">
                    <label  class="col-sm form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="<?php echo $email; ?>">
                </div>
                    <div class="d-grid gap-4">
                        <button class="btn btn-block" id="button" type="submit"> Update </button>
                        
                    </div>
                    <br>
        </form>

  </div>
</div>


<!-- FOOTER -->
<div class="card-footer" style="background-color: #f9f9f9; height:6%;">
            <small>All rights reserved 2023</small>
</div>
</body>
</html>
