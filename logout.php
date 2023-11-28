<?php
session_start();

// Check the role to determine which user is logging out
if ($_SESSION["role"] === "user") {
    // User is logging out
    // Perform any user-specific logout actions if needed
    $_SESSION["logged_out"] = true; // Set a flag indicating the user is logged out
    header('location:login.php');
} elseif ($_SESSION["role"] === "admin") {
    // Admin is logging out
    // Perform any admin-specific logout actions if needed
    $_SESSION["logged_out"] = true; // Set a flag indicating the user is logged out
    header('location:admin_login.php');
}

session_unset();
session_destroy();


?>
