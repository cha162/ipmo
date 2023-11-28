<?php
session_start();
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_email = $_POST["email"];
    $admin_password = $_POST["password"];
    
    // Prepare a SQL statement
    $stmt = $conn->prepare("SELECT * FROM `admin` WHERE email = ? AND `password` = ?");
    $stmt->bind_param('ss', $admin_email, $admin_password);
    
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
         // Successful login, set session variables
        $admin = $result->fetch_assoc();
        $_SESSION['admin_id'] = $admin['adminID'];
        $_SESSION['role'] = "admin";
        
        // Successful login, redirect to a new page
        header("Location: home_admin.php");
        exit();
    } else {
        $errorMessage = "Invalid login credentials";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title> Intellectual Property Management Office </title>
    <link type="text/css" rel="stylesheet" href="css/std.css"/>
    <link rel="icon" href="imgs/logo.png" type="image/x-icon" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
   
   
</head>
<body>

    <div class="front" >
        <div class="box" >
                <img class="logo" alt="PUP-Logo" src="imgs/logo.png" style="height:23%; width:23%;">
                <br>
                <h4 style="margin-left: 80px;"><b>PUP - IPMO Personnel</b></h4>


                <form method="post"> 
                <div class="form-group">
                        <label for="exampleInputEmail1">Email</label>
                        <input type="email" class="form-control" required id="email" name="email" aria-describedby="emailHelp" placeholder="Enter Username">
                    
                    </div>  
                
                    <div class="form-group-pass">
                        <label for="exampleInputPassword1">Password</label>
                        <input type="password" class="form-control" required id="Mpassword" name="password" placeholder="Password">
                    </div>
                    <div class="d-grid gap-4">
                        <button class="btn btn-primary" id="button" type="submit"> Sign in</button>
                    </div>

                    <br>
                    <a href="landing.php" style="text-decoration: none;"><small id="emailHelp" class="form-text text-muted">I forgot my password</small> </a>
                </form>

                <div class="privacy" >
                <p>By using this service, you understood and agree to the PUP Online</p>
                <p>Services  <a href="#" style="text-decoration: none;">Terms of Use</a> and <a href="#" style="text-decoration: none;">Privacy Statement</a> </p>
                </div>

            </div>
        </div>

</body>
</html>
