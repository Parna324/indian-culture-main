<?php
// Initialize the session
session_start();
 
// Unset all of the session variables
$_SESSION = array();
 
// Destroy the session.
session_destroy();
 
// Redirect to login page using correct XAMPP path
header("location: http://localhost/indian-culture-main/indian-culture-main/php/login.php");
exit;
?> 