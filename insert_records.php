<?php
session_start();
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["insertButton"]) && isset($_POST["selected"]) && isset($_POST["table"])) {

    $selectedFileIDs = $_POST["selected"];
    $table = $_POST["table"]; // Retrieve the table name from the form

    if ($table === "main") {
        $tableName = "mainapplicationrecords";
        $tableID = "MFileID";
        $redirectPage = "main_app.php";
    } elseif ($table === "branch") {
        $tableName = "campusbranchesrecords";
        $tableID = "CBFileID";
        $redirectPage = "branch_app.php";
    }

    if (!empty($tableName)) {
        foreach ($selectedFileIDs as $selectedFileID) {
            // Insert selected records into the specified table
            $insertSQL = "INSERT INTO $tableName ($tableID) VALUES ('$selectedFileID')";
            if ($conn->query($insertSQL) !== TRUE) {
                echo "Error inserting record: " . $conn->error;
            }
        }

        $conn->close();

        // Redirect to the appropriate page
        header("Location: $redirectPage");
        exit();
    }
}
?>