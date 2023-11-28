<?php
session_start();
require 'config.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $studentNumber = $_POST['student_number'];
    $email = $_POST['email'];

    // Check if the user with the provided student number and email exists in the database
    $sql = "SELECT * FROM users WHERE Student_Number = '$studentNumber' AND email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        // Generate a new random password (you can modify this method as needed)
        $newPassword = generateRandomPassword(); // Replace this with your method to generate a new password

        // Update the user's password in the database without hashing
        $updateSql = "UPDATE users SET Password = '$newPassword' WHERE Student_Number = '$studentNumber' AND email = '$email'";
        $updateResult = mysqli_query($conn, $updateSql);

        if ($updateResult) {
            // Send an email to the user with the new password
            $subject = "Password Reset";
            $message = "Your new password is: $newPassword"; // Customize this message as needed

            // PHPMailer configuration and sending email
            $mail = new PHPMailer(true);

            try {
                //Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';  // Replace with your SMTP host
                $mail->SMTPAuth = true;
                $mail->Username = 'cdlbacani@gmail.com'; // Replace with your SMTP username
                $mail->Password = 'cpybhvokyupnxhfp'; // Replace with your SMTP password
                $mail->SMTPSecure = 'ssl';
                $mail->Port = 465; // TCP port to connect to
                //Recipients
                $mail->setFrom('cdlbacani@gmail.com', 'Charles Bacani'); // Replace with your sender details
                $mail->addAddress($email); // Add a recipient

                // Content
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body    = $message;

                $mail->send();

                // Redirect to a success page or display a success message
                header("Location: login.php"); // Redirect to a success page
                exit();
            } catch (Exception $e) {
                // Handle email sending errors
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            // Handle database update error
            echo "Error updating password: " . mysqli_error($conn);
        }
    } else {
        // Handle invalid student number or email
        echo "Invalid student number or email";
    }
}

function generateRandomPassword() {
    // Replace this with your logic to generate a random password
    $length = 10;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomPassword = '';
    for ($i = 0; $i < $length; $i++) {
        $randomPassword .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomPassword;
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
             .card-footer{
                position: fixed;
                left: 0;
                bottom: 0;
                width: 100%;
                text-align: center;
            }
            .card{
             width: 100%;
             text-align: center;
             margin-left: 35%;
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

<nav class="navbar navbar-expand-lg fixed-top navbar-dark">
        
        <a class="navbar-brand" href="login.php"><img src="imgs/logo.png" alt="PUP Logo" style="height: 5%; width:5%; margin-left:5%;"> 
        Intellectual Property Management Office
    </a>    
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false">
                <div class="dropdowns" >
                    <i class="fa fa-user" id="dropbtns" style="font-size:25px; margin-left:350px"></i></li>
                        <div class="dropdowns-content" style="margin-right: 10%">
                        <a href="#" style="color: black;">Profile</a>
                        <a href="logout.php" style="color: black;">Sign out</a>
        </div>
        </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav mr-auto" >  
                </ul>
</nav>

<div class="card" style="width: 25rem;">
<div class="card-body">   
        <p class="card-text text-muted">You forgot your password? You can easily request a new password here.</p>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <input type="text" class="form-control" name="student_number" placeholder="Student Number">
            </div>
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="Email Address">
            </div>
            <button type="submit" class="btn btn-block">Send a new password</button>
        </form>
        <a href="login.php" class="card-link">Login</a>
    </div>
</div>

<div class="card-footer" style="background-color: #f9f9f9; height:6%;">
            <small>All rights reserved 2023</small>
</div>
</body>
</html>