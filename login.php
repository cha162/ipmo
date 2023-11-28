<?php
    session_start();
    require 'config.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $student_number = $_POST["student_number"];
        $password = $_POST["password"];
        $error_message = "";

        if (empty($student_number)) {
            $error_message = "Student number is required";
        } elseif (empty($password)) {
            $error_message = "Password is required";
        } else {
            $stmt = $conn->prepare("SELECT * FROM users WHERE Student_Number = ? AND Password = ?");
            $stmt->bind_param('ss', $student_number, $password);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Successful login, set session variables
                $student = $result->fetch_assoc();
                $_SESSION["user_id"] = $student["MUserID"];
                $_SESSION["role"] = "user";
            
                // Check if the student is from "main" or "branch"
                if ($student["Campus"] === "Main") {
                    // Redirect to home.php for "main" students using JavaScript
                    echo '<script>window.location.href = "main.php";</script>';
                    exit();
                } elseif ($student["Campus"] === "Branch") {
                    // Redirect to branchhome.php for "branch" students using JavaScript
                    echo '<script>window.location.href = "branch.php";</script>';
                    exit();
                }
            } else {
                // Invalid login, set the error message
                $error_message = "Invalid login credentials";
            }
        }
    }
    

    // Set a JavaScript variable to indicate whether the popup should be displayed
    echo '<script>var showErrorPopup = ' . (empty($error_message) ? 'false' : 'true') . ';</script>';
    ?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Intellectual Property Management Office </title>
    <link type="text/css" rel="stylesheet" href="css/std.css"/>
    <link type="text/css" rel="stylesheet" href="css/pop.css"/>
    <link rel="icon" href="imgs/logo.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
   

</head>
<body>


<div class="front" >
    <div class="box" >
            <img class="logo" alt="PUP-Logo" src="imgs/logo.png" style="height:12.4%; width:22.8%;">
            <br>
            <h4 style="margin-left: 40px;"><b>Student - Copyright Application</b></h4>
        <br>
            <h5 style="font-size: small;">Sign in to start your application:</h5>

        <form method="post">
         <div class="form-group">
                    <label>Student Number</label>
                    <input type="text" class="form-control" required id="student_number" name="student_number" aria-describedby="emailHelp" placeholder="Enter Student Number">
            </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" class="form-control" required id="Mpassword" name="password" placeholder="Password">
                </div>
                    <div class="d-grid gap-4">
                        <button class="btn btn-primary" id="button" type="submit"> Sign in</button>
                    </div>

                    <br>
            <a href="forgot.php" style="text-decoration: none;"><small id="emailHelp" class="form-text text-muted">forgot my password</small></a>
        </form>

        <div class="privacy" >
        <p>By using this service, you understood and agree to the PUP Online</p>
        <p>Services  <a href="#" style="text-decoration: none;">Terms of Use</a> and <a href="#" style="text-decoration: none;">Privacy Statement</a> </p>
        </div>
    </div>

<div class="w3-animate-top">
    <div class="popup" id="popup">
        <strong id="popup-message" style="color: white; line-height: 5;">Invalid login credentials!</strong>
        <span onclick="closePopup();" class="w3-button w3-display-topright" style="color: white;">&times;</span>
    </div>
</div>

<script>
    function openPopup() {
        var popup = document.getElementById("popup");
        popup.classList.add("open-popup");
        setTimeout(closePopup, 1100);
    }

    function closePopup() {
        var popup = document.getElementById("popup");
        popup.classList.remove("open-popup");
    }
    if (showErrorPopup) {
        openPopup();
    }
</script>



</body>
</html>
