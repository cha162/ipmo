<?php
session_start();
require_once 'config.php';

$_SESSION['ayID'] = '';

// Handle form submissions for navigation based on type (college/program)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['select_type'])) {
        if ($_POST['select_type'] == 'college' && isset($_POST['college_select'])) {
            header("Location: edit_form.php?type=college&id=" . $_POST['college_select']);
            exit();
        } elseif ($_POST['select_type'] == 'program' && isset($_POST['program_select'])) {
            header("Location: edit_form.php?type=program&id=" . $_POST['program_select']);
            exit();
        }
    }

    // Handling the form submission to add a new school year
    if (isset($_POST['add_submit'])) {
        $newSchoolYear = $_POST['new_school_year'];

        // Insert the new school year into the acadyear table
        $insertQuery = "INSERT INTO acadyear (School_Year) VALUES (?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("s", $newSchoolYear);

        if ($stmt->execute()) {
            // Success message after inserting the new school year
            echo "<script>alert('School year added successfully.');</script>";
            // Refresh the page to reflect the updated dropdown values
            header("Refresh:0"); // Refresh the page
            exit();
        } else {
            // Error message if there's an issue with adding the school year
            echo "<script>alert('Error adding school year.');</script>";
        }

        // Close the statement
        $stmt->close();
    }
}

// Fetch colleges from the database
$sqlColleges = "SELECT * FROM `college`";
$resultColleges = $conn->query($sqlColleges);

// Fetch programs from the database
$sqlPrograms = "SELECT * FROM `program`";
$resultPrograms = $conn->query($sqlPrograms);

// Fetch available school years from the database
$syquery = "SELECT SyID, School_Year FROM `acadyear`";
$resultsy = $conn->query($syquery);
$schoolYears = [];

if ($resultsy->num_rows > 0) {
    while ($row = $resultsy->fetch_assoc()) {
        $schoolYears[$row['SyID']] = $row['School_Year'];
    }
}

if (isset($_POST["change_year"])) {
    $ayID = $_POST['select_year'];
    $selectedquery = "UPDATE acadyear SET selected = 'false'";
    $selectedquery = $conn->prepare($selectedquery);
    $selectedquery->execute();
    
    $query = "UPDATE acadyear SET selected = 'true' WHERE School_Year = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $ayID);
    $stmt->execute();
}

?>


<!DOCTYPE html>
<html>
<head>
    <title>Edit College/Program</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" href="imgs/logo.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.bundle.min.js/bootstrap.bundle.js" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"></script> 
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
            .card-title{
                text-align: center;
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

     .header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1;
            background: #333; /* Set your desired background color */
        }
        
        .sidebar {
            width: 170px;
            height: 100%;
            background: white;
            padding: 30px 0px;
            position: fixed;
            border-right: 1px solid;
        }
        
        .wrapper {
            height: calc(100vh - 54px); 
                }

        .application-dropdown:hover .dropdown-content {
            display: block;
            position: relative;
            z-index: 1;

        }

        /* Move the sidebar items down when the dropdown is open */
        .application-dropdown:hover + li {
            margin-top: 100px; /* Adjust the margin as needed */
        }
        .record-dropdown:hover .dropdown-content {
        display: block;
        position: relative;
        z-index: 1;
    }

        .record-dropdown:hover + .edit-icon {
            margin-top: 5px; 
        }
        .pdf-buttons-container.show-all-pdf p {
        margin-bottom: 20px; /* Adjust the margin value as needed */
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

<!-- BODY -->



<div class="card-deck" style="width: 60rem; height: 50%; margin-left: 300px; margin-top: 50px;" >
  <div class="card">
    <div class="card-body">
    <div class="card-title">
    <h3>Select/Add School Year</h3>
    </div>
    <form action="" method="post">

    <label>Select Type:</label>
                
                <div class="form-group row">
                <select name="select_year" class="form-control col-form-label">
                <?php foreach ($schoolYears as $year) : ?>
                    <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="change_year" class="btn" style="background: maroon; margin-top: 15px;">Change Year</button>
        </div>

        <div class="form-group row">
        <label>Enter School Year:</label>
       
            <input type="text" name="new_school_year" class="form-control " placeholder="Enter new school year">
            <button type="submit" name="add_submit"  class="btn" style="background: maroon; margin-top: 15px;">Add School Year</button>
                
        </div>
    </form>
    </div>
  </div>




<div class="card" style="width: 40rem;">
  <div class="card-body">   
    <div class="card-title">
    <h3>College or Program to Edit</h3>
    </div>
    
    
    <form action="" method="post">
                <label>Select Type:</label>
                
                <div class="form-group row">
                <select name="select_type" class="form-control col-form-label">
                    <option value="college">College</option>
                    <option value="program">Program</option>
                </select>
                
                </div>

                <div class="form-group row">
                <label for="college_select" class="col-sm-4 form-label">Select College:</label>
                <select id="college_select" name="college_select" class="form-control">
                    <?php
                    while ($row = $resultColleges->fetch_assoc()) {
                        echo '<option value="' . $row['CollegeID'] . '">' . $row['College_Name'] . '</option>';
                    }
                    ?>
                </select>
                <br>
                </div>

                <div class="form-group row">
                <label for="program_select"  class="col-sm-4 form-label">Select Program:</label>
                <select id="program_select" name="program_select" class="form-control">
                    <?php
                    while ($row = $resultPrograms->fetch_assoc()) {
                        echo '<option value="' . $row['ProgramID'] . '">' . $row['Program_Name'] . '</option>';
                    }
                    ?>
                </select>
                </div>

                <input type="submit" value="Proceed to Edit" id="proceedButton" class="btn" style="background: maroon; float:center;">

                    </form>
                    </div>
        </div>
        </div>
        </div>
  
</body>
</html>