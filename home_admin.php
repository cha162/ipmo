<!DOCTYPE html>
<html>
<?php
require 'config.php';
session_start(); // Start the session

// Check if the user is logged in as an admin
if (!isset($_SESSION["admin_id"])) {
    // Display any session errors for debugging purposes
    echo "Session error: " . session_status() . "<br>";
    echo "Session ID: " . session_id() . "<br>";
    echo "Session variables: " . print_r($_SESSION, true) . "<br>";
    header("Location:admin_login.php");
    exit();
}
// Rest of your main_app.php code for admin access

$schoolYears = []; // Initialize $schoolYears

// Fetch available school years from the database
$syquery = "SELECT SyID, School_Year FROM `acadyear`";
$resultsy = $conn->query($syquery);

if ($resultsy->num_rows > 0) {
    while ($row = $resultsy->fetch_assoc()) {
        $schoolYears[$row['SyID']] = $row['School_Year'];
    }
}

if (isset($_POST["change_year"])) {
        $selectedYear = $_POST['select_year'];

        // Set 'selected' to 'false' for all records
        $selectedquery = "UPDATE mainapplication SET selected = 'false'";
        $selectedquery = $conn->prepare($selectedquery);
        $selectedquery->execute();
        $selectedquery->close();

        // Set 'selected' to 'true' for the selected school year
        $query = "UPDATE mainapplication SET selected = 'true' WHERE School_Year = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $selectedYear);
        $stmt->execute();
        $stmt->close();
    }

// Retrieve the selected school year from the database
$syquery = "SELECT School_Year FROM mainapplication WHERE selected = 'true'";
$systmt = $conn->prepare($syquery);
$systmt->execute();
$systmt->bind_result($schoolYear);
$systmt->fetch();
$systmt->close();

$conn->close();
?>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="imgs/logo.png" type="image/x-icon" />
    <link rel="stylesheet" href="css/admin-home.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>IPMO - Home</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <link rel="stylesheet" href="css/dashboard.css">
    <style>
         .table-responsive {
            overflow-x: auto;
            overflow-y: hidden;
            margin-right: -15px;
        }
        .table-responsive::-webkit-scrollbar {
            width: -5%;
            background: transparent;
        }
    #collegesDropdown, #programsDropdown {
            width: 90%;
        }
         #searchInput{
            width: 95%;
            margin-left: -3%;
        }
        body {
            overflow-y: hidden; 
        }
   
        .wrapper {
            height: calc(100vh - 54px); 
        }
        .main_content {
            flex-grow: 2;
            overflow-y: auto; /* Enable scrolling for content */
            margin-right: 0;
            height: 87vh;
        }

        #searchInput {
            width: 95%;
            margin-left: -3%;
        }
        .main_content::-webkit-scrollbar {
            width: -4%;
        }
        ::-webkit-scrollbar {
            width: 10px;
        }

        /* Track */
        ::-webkit-scrollbar-track {
            background: #f1f1f1; 
        }
        
        /* Handle */
        ::-webkit-scrollbar-thumb {
            background: #888; 
        }

        #searchButton{
            margin:0px -35px;
            background-color: maroon;
            border: none;
        }
        #clearSearchButton{
            margin:0px 45px;
        }
        button {
            padding: 5px 5px 5px 5px;
        }
        thead{
            background-color: maroon;
            color: white;
        }
        #statusButton{
            background-color: maroon;
            color: white;
            border: none;
            float: right;
        }
        .application-dropdown:hover .dropdown-content {
            display: block;
            position: relative;
            z-index: 1;

        }

        .application-dropdown:hover + li {
            margin-top: 100px;
        }

        .record-dropdown:hover .dropdown-content {
        display: block;
        position: relative;
        z-index: 1;
    }

        .record-dropdown:hover + .edit-icon {
            margin-top: 5px; 
        }
        .card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        /* Style for individual card */
        .card {
            margin-bottom: 20px;
        }
        .sidebar{
            margin-top: 50px;
        }
        .wrapper #mainAppContainer{
            margin-top: 80px;
        }
    </style>
</head>
<body>
<?php include 'new-header-admin.php'?>


<div class="wrapper">
<div class="sidebar">
            <ul>
                <li><a href="home_admin.php"><i class="fa fa-home" style="width: 20px;"></i>Home</a></li>
                
               

                <div class="dropdown application-dropdown">
                    <li><i class="fa fa-file-text" style="width: 20px;"></i>Applications</li>
                    <div class="dropdown-content" style="left: 0;">
                        <a href="main_app.php">Main Campus</a>
                        <a href="branch_app.php">Branch/Campuses</a>
                    </div>
                </div>
          
                <div class="dropdown record-dropdown">
                    <li><i class="fa fa-folder-open" style="width: 20px;"></i>Records</li>
                    <div class="dropdown-content" style="left: 0;">
                        <a href="main_record.php">Main Campus</a>
                        <a href="branch_record.php">Branch/Campuses</a>
                    </div>
   
                </div>
                <li class="edit-icon"><a href="select_edit.php"><i class="fa fa-edit" style="width: 20px;"></i>Edit</a></li>
            </ul>
        </div>

        <div class="main_content"  class="container allContent-section py-4" id="mainAppContainer">
            <div class="info">
                <h1><strong> Dashboard</strong> </h1>
                <label for="year">Choose a year:</label>
        <select id="select_year" name="select_year">
        <?php foreach ($schoolYears as $year) : ?>
                        <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                    <?php endforeach; ?>
        </select>
        <button type="submit" id="change_year"name="change_year" value="Submit">
            </div>

            <div class="main-title">
        <h3 style="line-height: 2;">Copyright Applications - Branches/Campuses</h3>
        </div>

        <div class="main-cards">

            
            <div class="card" style="color:black;">
            <div class="card-inner">    
                    <i class="fa fa-users" style="font-size: 30px; color:black;"></i>
                    <h5 style="color:Black;">
                    <?php
                        $totalProcessing = 0;
                        $sqlProcessing = "SELECT COUNT(*) as total FROM mainapplication WHERE remarks = 'processing'";  
                        $resultProcessing = $conn->query($sqlProcessing);
                        if ($resultProcessing->num_rows > 0) {
                        $row = $resultProcessing->fetch_assoc();
                        $totalProcessing = $row['total'];
                        }
                        
                    ?>
                    <h4>Processing:<br> <?php echo $totalProcessing; ?></h4>
                    </h5>
                </div>
            </div>

            <div class="card" style="color:black;">
            <div class="card-inner">
                    <i class="fa fa-users  mb-2" style="font-size: 30px; color:black;"></i>
                    <h5 style="color:Black;">
                    <?php
                        $totalIncomplete = 0;
                        $sqlIncomplete = "SELECT COUNT(*) as total FROM mainapplication WHERE remarks = 'incomplete'";
                        $resultIncomplete = $conn->query($sqlIncomplete);
                        if ($resultIncomplete->num_rows > 0) {
                        $row = $resultIncomplete->fetch_assoc();
                        $totalIncomplete = $row['total'];
                        }
                        
                    ?>
                      <h4>Incomplete:<br> <?php echo $totalIncomplete; ?></h4>
                    </h5>
                </div>
            </div>

            <div class="card" style="color:black;">
            <div class="card-inner">
                    <i class="fa fa-users  mb-2" style="font-size: 30px; color:black;"></i>
                    <h5 style="color:Black;">
                    <?php
                        $totalComplete = 0;
                        $sqlComplete = "SELECT COUNT(*) as total FROM mainapplication WHERE remarks = 'complete'";
                        $resultComplete = $conn->query($sqlComplete);
                        if ($resultComplete->num_rows > 0) {
                        $row = $resultComplete->fetch_assoc();
                        $totalComplete = $row['total'];
                        }
                        
                    ?>
                     <h4>Complete:<br> <?php echo $totalComplete; ?></h4>
                    </h5>
                </div>
            </div>

            <div class="card" style="color:black;">
                    <div class="card-inner">
                    <i class="fa fa-users" style="font-size: 25px; color:black;"></i>
                    <h5 style="color:Black;">
                    <?php
                        $totalCompletes = 0;
                        $sqlCompletes = "SELECT COUNT(*) as total FROM campusbranchesapplication WHERE remarks = 'registered'";
                        $resultCompletes = $conn->query($sqlCompletes);
                        if ($resultCompletes->num_rows > 0) {
                        $rows = $resultCompletes->fetch_assoc();
                        $totalRegistered = $rows['total'];
                        }
                        
                    ?>
                     <h4>Registered: <br> <?php echo $totalRegistered; ?></h4>
                    </h5>
                    </div>
                </div>
                    </div>
            
            

            <div class="main-title">
        <h3 style="line-height: 2;">Copyright Applications - Branches/Campuses</h3>
        </div>
      
        <div class="main-cards">

            
            <div class="card" style="color:black; font-family: 'Montserrat', sans-serif;">
            <div class="card-inner">
                    <i class="fa fa-users  mb-2" style="font-size: 30px;color:black;"></i>
                    <h5 style="color:Black;">
                    <?php
                        $totalProcessings = 0;
                        $sqlProcessings = "SELECT COUNT(*) as total FROM campusbranchesapplication WHERE remarks = 'processing'";  
                        $resultProcessings = $conn->query($sqlProcessings);
                        if ($resultProcessings->num_rows > 0) {
                        $rows = $resultProcessings->fetch_assoc();
                        $totalProcessings = $rows['total'];
                        }
                        
                    ?>
                    <h4>Processing:<br> <?php echo $totalProcessings; ?></h4>
                    </h5>
                    </div>
                </div>
            
            
                <div class="card" style="color:black;">
            <div class="card-inner">
                    <i class="fa fa-users  mb-2" style="font-size: 30px; color:black;"></i>
                    <h5 style="color:Black;">
                    <?php
                        $totalIncompletes = 0;
                        $sqlIncompletes = "SELECT COUNT(*) as total FROM campusbranchesapplication WHERE remarks = 'incomplete'";
                        $resultIncompletes = $conn->query($sqlIncompletes);
                        if ($resultIncompletes->num_rows > 0) {
                        $rows = $resultIncompletes->fetch_assoc();
                        $totalIncompletes = $rows['total'];
                        }
                        
                    ?>
                      <h4>Incomplete:<br> <?php echo $totalIncompletes; ?></h4>
                    </h5>
                    </div>
                </div>
            
          
                <div class="card" style="color:black;">
            <div class="card-inner">
                    <i class="fa fa-users  mb-2" style="font-size: 30px; color:black;"></i>
                    <h5 style="color:Black;">
                    <?php
                        $totalCompletes = 0;
                        $sqlCompletes = "SELECT COUNT(*) as total FROM campusbranchesapplication WHERE remarks = 'complete'";
                        $resultCompletes = $conn->query($sqlCompletes);
                        if ($resultCompletes->num_rows > 0) {
                        $rows = $resultCompletes->fetch_assoc();
                        $totalCompletes = $rows['total'];
                        }
                        
                    ?>
                     <h4>Complete:<br> <?php echo $totalCompletes; ?></h4>
                    </h5>
                    </div>
                </div>
    
                <div class="card" style="color:black;">
                    <div class="card-inner">
                    <i class="fa fa-users" style="font-size: 25px; color:black;"></i>
                    <h5 style="color:Black;">
                    <?php
                        $totalCompletes = 0;
                        $sqlCompletes = "SELECT COUNT(*) as total FROM campusbranchesapplication WHERE remarks = 'registered'";
                        $resultCompletes = $conn->query($sqlCompletes);
                        if ($resultCompletes->num_rows > 0) {
                        $rows = $resultCompletes->fetch_assoc();
                        $totalRegistereds = $rows['total'];
                        }
                        
                    ?>
                     <h4>Registered: <br> <?php echo $totalRegistereds; ?></h4>
                    </h5>
                    </div>
                </div>
        </div>
    </div>
</body >
</html >