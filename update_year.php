<?php
session_start();
require "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['select_year'])) 
$schoolYear = $_POST['select_year'];
?>
