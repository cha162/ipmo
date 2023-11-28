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
    <title>Campuses Application</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="icon" href="imgs/logo.png" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" href="css/main_app.css" />
    <link rel="stylesheet" type="text/css" href="css/admin-home.css">
    <script>
        function toggleSelectAll() {
            var checkboxes = document.getElementsByName('selected[]');
            var selectAllCheckbox = document.getElementById('selectAllCheckbox');

            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = selectAllCheckbox.checked;
            }
        }
    </script>
    <style>
        .header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1;
            background: #333;
            /* Set your desired background color */
        }

        .sidebar {
            width: 170px;
            height: 100%;
            background: white;
            padding: 30px 0px;
            position: fixed;
            border-right: 1px solid;
        }

        /* Your existing styles */
        .table-responsive {
            overflow-x: auto;
            overflow-y: hidden;
            margin-right: -15px;
        }

        .table-responsive::-webkit-scrollbar {
            width: -5%;
            background: transparent;
        }

        #collegesDropdown,
        #programsDropdown {
            width: 90%;
        }

        #searchInput {
            width: 95%;
            margin-left: -3%;
        }

        .wrapper {
            height: calc(100vh - 54px);
        }

        .main_content {
            flex-grow: 2;
            overflow-y: auto;
            /* Enable scrolling for content */
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
            overflow-y: auto;
            /* Enable scrolling for content */
            margin-right: 0;
            height: 87vh;
            padding-right: 15px;
            /* Add padding to accommodate scrollbar width */
        }

        .main_content::-webkit-scrollbar {
            width: 12px;
            /* Set the scrollbar width as needed */

        }

        .main_content::-webkit-scrollbar-thumb {
            background-color: gray;
            /* Set the scrollbar thumb color */

        }

        #searchButton {
            margin: 0px -35px;
            background-color: maroon;
            border: none;
        }

        #clearSearchButton {
            margin: 0px 45px;
        }

        button {
            padding: 5px 5px 5px 5px;
        }

        thead {
            background-color: maroon;
            color: white;
        }

        #statusButton {
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

        .application-dropdown:hover+li {
            margin-top: 100px;
        }

        .record-dropdown:hover .dropdown-content {
            display: block;
            position: relative;
            z-index: 1;
        }

        .record-dropdown:hover+.edit-icon {
            margin-top: 5px;
        }

        .show-all-pdf .document-type {
            text-align: center;
        }

        .show-all-pdf .btn-primary {
            margin: 0 auto;
        }

        .show-all-pdf .document-type,
        .show-all-pdf .center-button {
            text-align: center;
        }

        .show-all-pdf .btn-primary {
            margin: 0 auto;
            /* Horizontal centering */
        }

        .sidebar {
            margin-top: 50px;
        }

        .wrapper #CBAppContainer {
            margin-top: 80px;
        }
    </style>

</head>

<body>

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


        <div id="CBAppContainer" class="main_content">
            <div class="info">
                <div class="container mt-4">
                    <h2><strong>Campus and Branches Applications</strong></h2>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="searchInput">Search Reference Number:</label>
                            <div class="input-group">
                                <input type="text" id="searchInput" placeholder="Enter Reference Number">
                                <div class="input-group-append">
                                    <button id="searchButton" class="btn btn-primary">Search</button>
                                    <button id="clearSearchButton" class="btn btn-secondary">Clear Search</button>
                                    <button id="statusButton" class="btn btn-primary" data-toggle="modal" data-target="#statusModal">Status</button>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-5">
                            <label for="branchesDropdown">Select Branch and Campuses:</label>
                            <select id="branchesDropdown" class="form-control">
                                <option value="">Select Branch and Campuses</option>
                                <!-- Options will be populated using JavaScript -->
                            </select>
                        </div>

                        <div class="col-md-5">
                            <label for="bcProgramsDropdown">Select Program:</label>
                            <select id="bcProgramsDropdown" class="form-control" disabled>
                                <option value="">Select Program</option>
                                <!-- Options will be populated using JavaScript -->
                            </select>
                        </div>
                    </div>


                    <div class="col-md-5">
                        <label for="remarksDropdown">Remarks Filter:</label>
                        <select id="remarksDropdown" class="form-control">
                            <option value="clear">Clear Filter</option>
                            <option value="Processing">Processing</option>
                            <option value="Incomplete">Incomplete</option>
                            <option value="Complete">Complete</option>
                        </select>
                    </div>

                    <div class="table-responsive mt-4">
                        <table id="CBAppTable" class="table table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Reference Number</th>
                                    <th>Thesis Title</th>
                                    <th>Date of Submission</th>
                                    <th>View PDF</th>
                                    <th>Remarks and Comments</th>
                                    <th>Download</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Table rows will be populated using JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="table-responsive mt-4">
                    <table id="remarkAppTable" class="table table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>Reference Number</th>
                                <th>Thesis Title</th>
                                <th>Author</th>
                                <th>Date Of Submission</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Table rows will be populated using JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="paginationControls" class="container mt-4 text-center">
                <button id="prevPageButton" class="btn btn-primary" disabled>Previous</button>
                <span id="currentPage" class="mx-3">Page 1</span>
                <button id="nextPageButton" class="btn btn-primary">Next</button>
            </div>
        </div>

        <div id="pdfButtonsContainer" class="pdf-section"></div>

        <!-- Modal to display PDF content -->
        <div id="pdfModal">
            <div id="pdfContent">
                <iframe id="pdfFrame" width="100%" height="500px"></iframe>
                <button id="closePdfButton">Close</button>
            </div>
        </div>

        <!-- Include PDF.js library -->
        <link rel="stylesheet" type="text/css" href="pdfjs/web/pdf_viewer.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="js/branch_app.js"></script>
        <script src="js/populate_tablecb.js"></script>
    </div>
    </div>
    <!-- Add the Bootstrap modal just before the closing </body> tag -->
    <div class="modal fade" id="statusModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Status</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" style="max-height: 300px; overflow-y: auto;">
                    <?php
                    require 'config.php';

                    // Retrieve records with "Complete" remark
                    $sql = "SELECT faf.*, fa.ThesisTitle
                    FROM campusbranchesapplicationfile AS faf
                    LEFT JOIN campusbranchesapplication AS fa ON faf.CBAppID = fa.CBAppID
                    WHERE fa.Remarks = 'Complete'";

                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        echo "<div class='text-right mt-2'>";
                        echo "Select All ";
                        echo "<input type='checkbox' id='selectAllCheckbox' onchange='toggleSelectAll()'>";
                        echo "<br>";
                        echo "</div>";
                        echo "<form method='post' action='insert_records.php'>";
                        echo "<input type='hidden' name='table' value='branch'>";
                        echo "<table>";
                        echo "<tr><th>Thesis Title</th><th style='text-align: right;'>Select</th></tr>";
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["ThesisTitle"] . "</td>";
                            echo "<td><input type='checkbox' name='selected[]' value='" . $row["CBAppID"] . "'></td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                        echo "<br>";
                        echo "<button type='submit' name='insertButton' class='btn btn-secondary btn-dark'>Insert Records</button>";
                        echo "</form>";
                    } else {
                        echo "No records found with 'Complete' remark.";
                    }

                    $conn->close();
                    ?>
                </div>

            </div>
        </div>
    </div>

    <script>
        // JavaScript to open the "Status" modal when the button is clicked
        $(document).ready(function() {
            $('#statusButton').click(function() {
                $('#statusModal').modal('show');
            });
        });
    </script>

</body>

</html>