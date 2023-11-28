<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
include 'config.php';

// Check request method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $remarks = $_POST['Remarks'];
    $comments = $_POST['Comments'];
    $appType = $_POST['AppType'];
    $MAppID = $_POST['MAppID']; // Store the MAppID

    // Set table and columns based on the application type
    if ($appType === 'main') {
        $idColumn = 'MAppID';
        $idVariable = $_POST['MAppID'];
        $userIDColumn = 'MUserID';
        $table = 'mainapplication';
        $titleColumn = 'ThesisTitle'; // Assuming the column name for thesis title in mainapplication table
    } else {
        $idColumn = 'CBAppID';
        $idVariable = $_POST['CBAppID'];
        $userIDColumn = 'CBUserID';
        $table = 'campusbranchesapplication';
        $titleColumn = 'ThesisTitle'; // Assuming the column name for thesis title in campusbranchesapplication table
    }

    // Prepare and execute query to update Remarks and Comments
    $query = "UPDATE $table SET Remarks = ?, Comments = ? WHERE $idColumn = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param('ssi', $remarks, $comments, $idVariable);

        if ($stmt->execute()) {
            echo "Remarks and Comments updated successfully";

// Check if a new file has been uploaded
if (isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileError = $file['error'];

    if ($fileError === UPLOAD_ERR_OK) {
        // Fetch the applicant's thesis title from the database
        $titleQuery = "SELECT $titleColumn FROM $table WHERE $idColumn = ?";
        $titleStmt = $conn->prepare($titleQuery);
        $titleStmt->bind_param('i', $idVariable);
        $titleExecuted = $titleStmt->execute();

        if ($titleExecuted) {
            $titleResult = $titleStmt->get_result();

            if ($titleResult->num_rows > 0) {
                $titleRow = $titleResult->fetch_assoc();
                $thesisTitle = $titleRow[$titleColumn];

                // Define upload directory based on the thesis title
                $uploadDir = 'uploads/' . $thesisTitle . '/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true); // Create directory if it doesn't exist
                }

                $uploadedFilePath = $uploadDir . $fileName;

                // Move the new uploaded file to the defined directory
                if (move_uploaded_file($fileTmpName, $uploadedFilePath)) {
                    // Check if the file already exists for this application
                    $existingFileQuery = "SELECT * FROM uploadedfiles WHERE $idColumn = ?";
                    $existingFileStmt = $conn->prepare($existingFileQuery);
                    $existingFileStmt->bind_param('i', $MAppID);
                    $existingFileExecuted = $existingFileStmt->execute();

                    if ($existingFileExecuted) {
                        $existingFileResult = $existingFileStmt->get_result();

                        if ($existingFileResult->num_rows > 0) {
                            // Update the existing file details
                            $updateFileQuery = "UPDATE uploadedfiles SET FileName = ?, FilePath = ? WHERE $idColumn = ?";
                            $updateFileStmt = $conn->prepare($updateFileQuery);

                            if ($updateFileStmt) {
                                $updateFileStmt->bind_param('ssi', $fileName, $uploadedFilePath, $MAppID);
                                if ($updateFileStmt->execute()) {
                                    echo "File updated successfully";
                                } else {
                                    echo "Error updating file: " . $updateFileStmt->error;
                                }
                                $updateFileStmt->close();
                            } else {
                                echo "Error preparing update statement: " . $conn->error;
                            }
                        } else {
                            // Insert the new file details
                            $insertFileQuery = "INSERT INTO uploadedfiles ($idColumn, FileName, FilePath) VALUES (?, ?, ?)";
                            $insertFileStmt = $conn->prepare($insertFileQuery);

                            if ($insertFileStmt) {
                                $insertFileStmt->bind_param('iss', $MAppID, $fileName, $uploadedFilePath);
                                if ($insertFileStmt->execute()) {
                                    echo "New file inserted successfully";
                                } else {
                                    echo "Error inserting new file: " . $insertFileStmt->error;
                                }
                                $insertFileStmt->close();
                            } else {
                                echo "Error preparing insert statement: " . $conn->error;
                            }
                        }
                    } else {
                        echo "Error executing query to check existing file: " . $conn->error;
                    }
                } else {
                    echo "Error moving the uploaded file";
                }
            } else {
                echo "No thesis title found for this application";
            }
        } else {
            echo "Error executing query to fetch thesis title: " . $conn->error;
        }
    } else {
        echo "Error uploading the file: " . $fileError;
    }
}
// Send email to the student with or without the uploaded file
// (Add your email sending logic here)
if (in_array($remarks, ['Incomplete', 'Complete', 'Registered'])) {
    if ($appType === 'main') {
        $emailQuery = "SELECT u.email FROM users u 
                        INNER JOIN mainapplication m ON u.MUserID = m.MUserID
                        WHERE m.MAppID = ?";
    } else {
        $emailQuery = "SELECT u.email FROM users u 
                        INNER JOIN campusbranchesapplication c ON u.MUserID = c.CBUserID
                        WHERE c.CBAppID = ?";
    }

    $emailStmt = $conn->prepare($emailQuery);
    $emailStmt->bind_param('i', $idVariable);
    $emailExecuted = $emailStmt->execute();

    if ($emailExecuted) {
        $result = $emailStmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $studentEmail = $row['email'];

                $subject = 'Application Status Update';
                $message = "Dear Student, <br><br>Your application status has been updated to $remarks.";

                // PHPMailer configuration for sending email
                $mail = new PHPMailer(true);
                try {
                    // Server settings
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';  // Replace with your SMTP host
                    $mail->SMTPAuth = true;
                    $mail->Username = 'cdlbacani@gmail.com'; // Replace with your SMTP username
                    $mail->Password = 'cpybhvokyupnxhfp'; // Replace with your SMTP password
                    $mail->SMTPSecure = 'ssl';
                    $mail->Port = 465; // TCP port to connect to

                    // Recipients
                    $mail->setFrom('cdlbacani@gmail.com', 'Charles Bacani'); // Replace with your sender details
                    $mail->addAddress($studentEmail); // Add a recipient


                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = $subject;
                    $mail->Body = $message;

                    // Attach the uploaded file if remarks are "Complete"
                         // Attach the uploaded file if remarks are "Complete"
            if ($remarks === "Complete") {
            $getFileQuery = "SELECT FileName, FilePath FROM uploadedfiles WHERE MAppID = ?";
            $getFileStmt = $conn->prepare($getFileQuery);
            $getFileStmt->bind_param('i', $MAppID);
            $getFileExecuted = $getFileStmt->execute();

            if ($getFileExecuted) {
                $fileResult = $getFileStmt->get_result();

                if ($fileResult->num_rows > 0) {
                    while ($fileRow = $fileResult->fetch_assoc()) {
                        $uploadedFileName = $fileRow['FileName'];
                        $uploadedFilePath = $fileRow['FilePath'];

                        if (file_exists($uploadedFilePath)) {
                            $mail->addAttachment($uploadedFilePath, $uploadedFileName); // Attach the file
                        } else {
                            echo "File not found to attach in email";
                        }
                    }
                } else {
                    echo "No uploaded file found for this application";
                }
            } else {
                echo "Error executing query to fetch uploaded file: " . $conn->error;
            }
        }

        $mail->send();
        echo ' Email sent to the student.';
    } catch (Exception $e) {
        echo ' Email could not be sent. Mailer Error: ' . $mail->ErrorInfo;
    }
}
        } else {
            echo "No user found with the given ID";
        }
    } else {
        echo "Error executing email query: " . $conn->error;
    }
}
        } else {
            echo "Error updating Remarks and Comments: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
} else {
    echo "Error: Invalid request method";
}

$conn->close();
?>