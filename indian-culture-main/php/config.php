<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', ''); // <-- LEAVE EMPTY unless you SET a MySQL password
define('DB_NAME', 'indian_culture');

/* Attempt to connect to MySQL database */
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if($conn === false){
    // Don't die here, let the calling script handle the error or check $conn
    // die("ERROR: Could not connect. " . mysqli_connect_error());
}
?> 