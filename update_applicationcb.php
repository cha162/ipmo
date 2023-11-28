<?php
require 'config.php';
session_start();

if (isset($_POST['updatedata'])) {
    $CBUserID = $_SESSION['user_id']; // Assuming 'user_id' is the session key for the user's ID

    // Extract other form data
    $author = $_POST['author'];
    $thesisTitle = $_POST['thesis_title'];
    $adviser = $_POST['adviser'];
    $branchesDropdown = $_POST['branchesDropdown'];
    $bcProgramsDropdown = $_POST['bcProgramsDropdown'];
    $remarks = 'Processing'; // Set remarks as needed

    // Prepare and execute an UPDATE query to update the data in the appropriate table
    $query = "UPDATE campusbranchesapplication 
              SET Author = ?, Author_02 = ?, Author_03 = ?, Author_04 = ?, Author_05 = ?, Author_06 = ?, Author_07 = ?, Author_08 = ?, Author_09 = ?, Author_10 = ?, 
              Adviser = ?, Adviser_02 = ?, Adviser_03 = ?, Adviser_04 = ?, Adviser_05 = ?, ThesisTitle = ?, BranchID = ?, BCProgramID = ?, Remarks = ?
              WHERE CBUserID = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        // Bind the parameters
        $stmt->bind_param("ssssssssssssssssiisi", 
            $author[0], $author[1], $author[2], $author[3], $author[4], $author[5], $author[6], $author[7], $author[8], $author[9],
            $adviser[0], $adviser[1], $adviser[2], $adviser[3], $adviser[4], $thesisTitle, $branchesDropdown, $bcProgramsDropdown, $remarks, $CBUserID);

        // Execute the query
        if ($stmt->execute()) {
            // Update Comments to an empty string
            $updateCommentsQuery = "UPDATE campusbranchesapplication SET Comments = '' WHERE CBUserID = ?";
            $stmtComments = $conn->prepare($updateCommentsQuery);

            if ($stmtComments) {
                // Bind parameters for the comments update
                $stmtComments->bind_param("i", $CBUserID);

                // Execute the comments update query
                $stmtComments->execute();
                $stmtComments->close();
            } else {
                echo "Error preparing comments update statement: " . $conn->error;
            }

            // Update files in mainapplicationfile table
            $updateFilesQuery = "UPDATE campusbranchesapplicationfile 
                SET CBFile_01 = ?, CBFile_02 = ?, CBFile_03 = ?, CBFile_04 = ?, CBFile_05 = ?, CBFile_06 = ?
                WHERE CBAppID = (SELECT CBAppID FROM campusbranchesapplication WHERE CBUserID = ?)";
            $stmtFiles = $conn->prepare($updateFilesQuery);

            if ($stmtFiles) {
                // Handle file uploads here if needed (make sure to move files to the appropriate directory)
                $file_01 = $_FILES['file_01']['name'];  
                $file_02 = $_FILES['file_02']['name'];
                $file_03 = $_FILES['file_03']['name'];
                $file_04 = $_FILES['file_04']['name'];
                $file_05 = $_FILES['file_05']['name'];
                $file_06 = $_FILES['file_06']['name'];

                // Bind parameters for the files update
                $stmtFiles->bind_param("ssssssi", $file_01, $file_02, $file_03, $file_04, $file_05, $file_06, $CBUserID);

                // Execute the files update query
                $stmtFiles->execute();
                $stmtFiles->close();
            } else {
                echo "Error preparing files update statement: " . $conn->error;
            }

            // Update successful
            echo "Application updated successfully!";
            header("Location: branch.php"); // Redirect to the appropriate page after updating
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing update statement: " . $conn->error;
    }

    $conn->close();
}
?>
