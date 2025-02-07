<?php
// logout.php

session_start(); // Start the session

// Unset all of the session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to login page
header("location: login.php");
exit; // Make sure that the script stops executing after the header() function
?>