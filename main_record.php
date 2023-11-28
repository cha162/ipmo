<?php
session_start();
require 'config.php';

if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="icon" href="imgs/logo.png" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" href="css/main_app.css" />
    <link rel="stylesheet" type="text/css" href="css/admin-home.css">
    <title>Thesis Information</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
        .main_content {
            margin-left: 155px;
            overflow-x: hidden;
            flex-grow: 2;
            overflow-y: auto; /* Enable scrolling for content */
            margin-right: 0;
            height: 87vh;
            padding-right: 15px; /* Add padding to accommodate scrollbar width */
        }

        .main_content::-webkit-scrollbar {
            width: 12px; /* Set the scrollbar width as needed */
         
        }

        .main_content::-webkit-scrollbar-thumb {
            background-color: gray; /* Set the scrollbar thumb color */
      
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
        .table{
            margin-left: -10px;
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

    <!-- Header Section -->

    <?php include 'new-header-admin.php' ?>

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

        <div id="mainAppContainer" class="main_content">
            <div class="info">
            <div class="container mt-4">
                <h2><strong>Records</strong></h2>
                <div class="row">           <div class="col-md-5">
                
                            <label for="startDate">Start Date:</label>
                            <input type="date" id="startDate" name="startDate">
                            <label for="endDate">End Date:</label>
                            <input type="date" id="endDate" name="endDate">

                            <label for="collegesDropdown" style="margin-top:15px;">Select College:</label>
                        <select id="collegesDropdown" name="collegesDropdown"class="form-control">
                            <option value="">Select College</option>
                            <!-- Options will be populated using JavaScript -->
                        </select>

                        <label for="programsDropdown" style="margin-top:8px;">Select Program:</label>
                        <select id="programsDropdown" name="programsDropdown" class="form-control" disabled>
                            <option value="">Select Program</option>
                            <!-- Options will be populated using JavaScript -->
                        </select>

                         <div class="btn" style="margin-left: 800px; ">
                            <button id="filterButton" class="btn btn-primary">Search</button>
                            <button id="clearFilterButton" class="btn btn-secondary">Clear</button>
                            <button id="downloadExcelButton" class="btn btn-primary">Generate Report</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive mt-4">
                <table id="mainAppTable" class="table table-bordered" style="margin-top:-6px;">
                    <thead class="thead-dark">
                        <tr>
                            <th>Registration Number</th>
                            <th>Author</th>
                            <th>Thesis Title</th>
                            <th>College</th>
                            <th>Program</th>
                            <th>Adviser</th>
                            <th>Date Of Submission</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
            <!-- Bootstrap Modal for Editing Registration Number -->
            <div class="modal fade bd-example-modal-sm" id="editModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm modal-dialog-centered"> <!-- Set the maximum width -->
                    <div class="modal-content" style="width:1000%; height: 70%;">
                        <div class="modal-header">
                            <h4 class="modal-title">Certification Number</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <div class="modal-body">
                            <form id="editForm">
                                <input type="hidden" id="recordID" name="recordID">
                                <div class="form-group">
                                    <label for="registrationNumber">Enter Certification Number:</label>
                                    <input type="text" class="form-control" id="registrationNumber" name="registrationNumber">
                                </div>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Bootstrap and jQuery scripts here -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="js/main_record.js"></script>

    <script>
        </script>

</body>

</html>