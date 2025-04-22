<?php
require_once "config.php";
// Go up THREE directories from current php/ folder to find the vendor/ folder in htdocs/
require __DIR__ . '/../../../vendor/autoload.php'; 

use PHPMailer\PHPMailer\PHPMailer;
// ... rest of the code ... 