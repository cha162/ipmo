<?php
session_start();
require_once 'config.php';

$type = isset($_POST['type']) ? $_POST['type'] : '';
$id = isset($_POST['id']) ? $_POST['id'] : '';
$new_name = isset($_POST['new_name']) ? $_POST['new_name'] : '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($type) && !empty($id) && !empty($new_name)) {
        if ($type === 'college') {
            $table = 'college';
            $id_column = 'CollegeID';
            $name_column = 'College_Name';
        } elseif ($type === 'program') {
            $table = 'program';
            $id_column = 'ProgramID';
            $name_column = 'Program_Name';
        }

        $sql = "UPDATE $table SET $name_column = '$new_name' WHERE $id_column = $id";

        if ($conn->query($sql) === TRUE) {
            // Redirect to another page on success
            header("Location: select_edit.php");
            exit(); // Make sure to exit after the header to prevent further execution
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } else {
        echo "Invalid data provided for update";
    }
}
?>
