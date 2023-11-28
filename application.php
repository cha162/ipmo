<?php
session_start();
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Create a timestamp
    $timestamp = date('Y-m-d H:i:s');

    // Log the user submission with timestamp
    $log_message = $timestamp . " - User with ID " . $_SESSION["user_id"] . " submitted the form.";
    error_log($log_message . PHP_EOL, 3, "logs/user_activity.log");

    // Define common variables based on your requirements
    $user_id = $_SESSION['user_id']; // Replace with the actual user ID as an integer
    $thesis_classification = $_POST['thesis_classification'];
    $author = $_POST['author'];
    $thesis_title = $_POST['thesis_title'];
    $adviser = $_POST['adviser'];
    $date_of_submission = date("Y-m-d"); // Set the current date
    $remarks = 'Processing'; // Set the initial status to 'Processing'
    $comments = ''; // Initialize comments as empty
    $year = date("Y"); // Get the current year
    $school_year = $_POST['school_year'];

    // Determine the mode of operation (main or campus branch)
    if (isset($_POST['branch_mode']) && $_POST['branch_mode'] === 'main') {
        $mode = 'main';
        $randomgen = strtoupper($year . 'M' . substr(base64_encode(sha1(mt_rand())), 0, 15 - strlen($year) - 1));
        $collegeID = $_POST['collegesDropdown']; // College ID for main
        $programID = $_POST['programsDropdown']; // Program ID for main
    } else {
        $mode = 'branch';
        $randomgen = strtoupper($year . 'B' . substr(base64_encode(sha1(mt_rand())), 0, 15 - strlen($year) - 1));
        $branchID = $_POST['branchesDropdown']; // Branch ID for branch
        $bcProgramID = $_POST['bcProgramsDropdown']; // BCProgram ID for branch
    }

    // Define upload directory based on the mode
    if ($mode === 'main') {
        $upload_dir = "student_file/$thesis_title/";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
    } else {
        $upload_dir = "student_filecb/$thesis_title/";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
    }

    // Handle file uploads
    $fileData = [];
    for ($i = 1; $i <= 6; $i++) {
        $file_field_name = "file_0" . $i;

        if (isset($_FILES[$file_field_name]) && $_FILES[$file_field_name]['error'] === UPLOAD_ERR_OK) {
            $file_name = $_FILES[$file_field_name]['name'];
            $file_type = $_FILES[$file_field_name]['type'];

            // Check if the file is a PDF
            $allowed_types = ['application/pdf'];
            if (!in_array($file_type, $allowed_types)) {
                echo "Error: Only PDF files are allowed.";
                exit();
            }

            $target_path = $upload_dir . $file_name;

            if (move_uploaded_file($_FILES[$file_field_name]['tmp_name'], $target_path)) {
                // Store file data for later insertion
                $fileData[] = $file_name;
            } else {
                echo "Error: Unable to move the uploaded file.";
                exit();
            }
        }
    }

    // Prepare and execute an INSERT query to store the data in the appropriate table
    $query = "";
    if ($mode === 'main') {
        $query = "INSERT INTO mainapplication (MUserID, RefNum, School_Year, Author, Author_02, Author_03, Author_04, Author_05, Author_06, Author_07, Author_08, Author_09, Author_10, 
              Adviser, Adviser_02, Adviser_03, Adviser_04, Adviser_05, ThesisTitle, CollegeID, ProgramID, DateOfSubmission, Remarks, Comments) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    } else {
        $query = "INSERT INTO campusbranchesapplication (CBUserID, RefNum, School_Year, Author, Author_02, Author_03, Author_04, Author_05, Author_06, Author_07, Author_08, Author_09, Author_10,
              Adviser, Adviser_02, Adviser_03, Adviser_04, Adviser_05, ThesisTitle, BranchID, BCProgramID, DateOfSubmission, Remarks, Comments) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    }
    $stmt = $conn->prepare($query);

    if ($stmt) {
        if ($mode === 'main') {
            $stmt->bind_param(
                "issssssssssssssssssiisss",
                $user_id,
                $randomgen,
                $school_year,
                $author[0],
                $author[1],
                $author[2],
                $author[3],
                $author[4],
                $author[5],
                $author[6],
                $author[7],
                $author[8],
                $author[9],
                $adviser[0],
                $adviser[1],
                $adviser[2],
                $adviser[3],
                $adviser[4],
                $thesis_title,
                $collegeID,
                $programID,
                $date_of_submission,
                $remarks,
                $comments
            );
        } else {
            $stmt->bind_param(
                "issssssssssssssssssiisss",
                $user_id,
                $randomgen,
                $school_year,
                $author[0],
                $author[1],
                $author[2],
                $author[3],
                $author[4],
                $author[5],
                $author[6],
                $author[7],
                $author[8],
                $author[9],
                $adviser[0],
                $adviser[1],
                $adviser[2],
                $adviser[3],
                $adviser[4],
                $thesis_title,
                $branchID,
                $bcProgramID,
                $date_of_submission,
                $remarks,
                $comments
            );
        }

        // Execute the query
        if ($stmt->execute()) {
            $application_id = $conn->insert_id; // Get the newly inserted Application ID

            // Insert all file data into the appropriate table
            if ($mode === 'main') {
                $file_table = "mainapplicationfile";
            } else {
                $file_table = "campusbranchesapplicationfile";
            }
            $file_columns = '';
            if ($mode === 'main') {
                $file_columns = "(MAppID, MFile_01, MFile_02, MFile_03, MFile_04, MFile_05, MFile_06)";
            } else {
                $file_columns = "(CBAppID, CBFile_01, CBFile_02, CBFile_03, CBFile_04, CBFile_05, CBFile_06)";
            }

            $insert_file_query = "INSERT INTO $file_table $file_columns VALUES (?, ?, ?, ?, ?, ?, ?)";
            $insert_file_stmt = $conn->prepare($insert_file_query);

            if ($insert_file_stmt) {
                // Create variables for the parameters
                $appID = $application_id;
                $file_01 = $fileData[0];
                $file_02 = $fileData[1];
                $file_03 = $fileData[2];
                $file_04 = $fileData[3];
                $file_05 = $fileData[4];
                $file_06 = $fileData[5];

                // Bind the parameters
                $insert_file_stmt->bind_param("issssss", $appID, $file_01, $file_02, $file_03, $file_04, $file_05, $file_06);
                $insert_file_stmt->execute();
                $insert_file_stmt->close();
            } else {
                echo "Error: " . $conn->error;
            }

            $notification_message = '';

            if ($mode === 'main') {
                $notification_message = "New Submission: From Main Campus, $thesis_title";
            } else {
                $notification_message = "New Submission: From Branch Campus, $thesis_title";
            }

            $notificationQuery = "INSERT INTO notification (MUserID, message) VALUES (?, ?)";
            $notificationStmt = $conn->prepare($notificationQuery);

            if ($notificationStmt) {
                $notificationStmt->bind_param("is", $user_id, $notification_message);
                $notificationStmt->execute();
                $notificationStmt->close();
            } else {
                echo "Error creating notification: " . $conn->error;
            }

            echo "Application submitted successfully!";
            if ($mode === 'main') {
                header("Location: main.php");
            } else {
                header("Location: branch.php");
            }
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Error: " . $conn->error;
    }
}

$stmt->close();
$conn->close();
