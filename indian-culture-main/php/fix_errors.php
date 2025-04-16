<?php
session_start();

echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>System Diagnostics & Repair</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; max-width: 900px; margin: 0 auto; padding: 20px; }
        h1 { color: #333; text-align: center; }
        h2 { color: #444; margin-top: 30px; border-bottom: 1px solid #ddd; padding-bottom: 5px; }
        .success { color: green; background: #eeffee; padding: 10px; border-radius: 5px; }
        .error { color: red; background: #ffeeee; padding: 10px; border-radius: 5px; }
        .warning { color: orange; background: #ffffee; padding: 10px; border-radius: 5px; }
        .info { color: blue; background: #eeeeff; padding: 10px; border-radius: 5px; }
        code { background: #f5f5f5; padding: 2px 5px; border-radius: 3px; font-family: monospace; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 5px; overflow: auto; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        button, .button { display: inline-block; background: #4CAF50; color: white; border: none; padding: 10px 15px; 
                border-radius: 4px; cursor: pointer; text-decoration: none; font-size: 14px; }
        button:hover, .button:hover { background: #45a049; }
        .fixed { background-color: #e7f7e7; border-left: 3px solid green; padding: 10px; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>System Diagnostics & Repair</h1>
    <p>This script will check for common issues and attempt to fix them automatically.</p>";

// Output buffer to collect all messages
$successMessages = [];
$errorMessages = [];
$warningMessages = [];
$infoMessages = [];
$fixedIssues = [];

// Function to add messages to respective arrays
function addMessage($type, $message) {
    global $successMessages, $errorMessages, $warningMessages, $infoMessages;
    
    switch($type) {
        case 'success':
            $successMessages[] = $message;
            break;
        case 'error':
            $errorMessages[] = $message;
            break;
        case 'warning':
            $warningMessages[] = $message;
            break;
        case 'info':
            $infoMessages[] = $message;
            break;
    }
}

function addFixedIssue($issue, $solution) {
    global $fixedIssues;
    $fixedIssues[] = ['issue' => $issue, 'solution' => $solution];
}

// ----------------------------------------
// STEP 1: Check Database Connectivity
// ----------------------------------------
echo "<h2>1. Database Connectivity Check</h2>";

// Define database connection variables
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'indian_culture';

// Test database connection
try {
    $conn = new mysqli($dbHost, $dbUser, $dbPass);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    addMessage('success', "Successfully connected to MySQL server at {$dbHost}");
    
    // Check if database exists
    $result = $conn->query("SHOW DATABASES LIKE '{$dbName}'");
    
    if ($result->num_rows == 0) {
        addMessage('error', "Database '{$dbName}' does not exist");
        
        // Create the database
        if ($conn->query("CREATE DATABASE {$dbName}")) {
            addMessage('success', "Created database '{$dbName}'");
            addFixedIssue("Missing database", "Created the '{$dbName}' database");
        } else {
            addMessage('error', "Failed to create database: " . $conn->error);
        }
    } else {
        addMessage('success', "Database '{$dbName}' exists");
    }
    
    // Select the database
    $conn->select_db($dbName);
    
    // Check for users table
    $result = $conn->query("SHOW TABLES LIKE 'users'");
    
    if ($result->num_rows == 0) {
        addMessage('error', "Table 'users' does not exist");
        
        // Create users table
        $sql = "CREATE TABLE users (
            id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
            username VARCHAR(50) NOT NULL UNIQUE,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )";
        
        if ($conn->query($sql)) {
            addMessage('success', "Created 'users' table with email field");
            addFixedIssue("Missing 'users' table", "Created the 'users' table with proper structure");
        } else {
            addMessage('error', "Failed to create users table: " . $conn->error);
        }
    } else {
        addMessage('success', "Table 'users' exists");
        
        // Check if email column exists
        $result = $conn->query("SHOW COLUMNS FROM users LIKE 'email'");
        
        if ($result->num_rows == 0) {
            addMessage('error', "Column 'email' does not exist in 'users' table");
            
            // Add email column
            $sql = "ALTER TABLE users ADD COLUMN email VARCHAR(255) NOT NULL UNIQUE AFTER username";
            
            if ($conn->query($sql)) {
                addMessage('success', "Added 'email' column to 'users' table");
                addFixedIssue("Missing 'email' column", "Added the 'email' column to the 'users' table");
            } else {
                addMessage('error', "Failed to add email column: " . $conn->error);
            }
        } else {
            addMessage('success', "Column 'email' exists in 'users' table");
        }
    }
    
} catch (Exception $e) {
    addMessage('error', "Database connection error: " . $e->getMessage());
    
    // Suggest XAMPP MySQL service fix
    addMessage('warning', "Ensure MySQL service is running in XAMPP Control Panel");
}

// ----------------------------------------
// STEP 2: Check and Fix File Path Issues
// ----------------------------------------
echo "<h2>2. File Path and Configuration Check</h2>";

// Update config.php to ensure it has correct connection parameters
$configFile = 'config.php';

if (file_exists($configFile)) {
    $configContent = file_get_contents($configFile);
    
    // Check database parameters
    if (strpos($configContent, "define('DB_NAME', '{$dbName}')") === false) {
        $configContent = preg_replace(
            "/define\('DB_NAME',\s*'[^']*'\);/",
            "define('DB_NAME', '{$dbName}');",
            $configContent
        );
        file_put_contents($configFile, $configContent);
        addMessage('success', "Updated database name in config.php");
        addFixedIssue("Incorrect database name", "Updated database name in config.php to '{$dbName}'");
    } else {
        addMessage('success', "config.php has correct database name");
    }
    
    addMessage('success', "config.php file is present and properly configured");
} else {
    addMessage('error', "config.php file is missing");
    
    // Create config.php
    $configContent = "<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', '{$dbName}');

try {
    \$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    
    if(\$conn->connect_error){
        throw new Exception(\"Connection failed: \" . \$conn->connect_error);
    }
    
    \$conn->set_charset(\"utf8\");
} catch(Exception \$e) {
    die(\"ERROR: Could not connect. \" . \$e->getMessage());
}
?>";
    
    file_put_contents($configFile, $configContent);
    addMessage('success', "Created config.php file with proper database settings");
    addFixedIssue("Missing config.php", "Created config.php with proper database connection settings");
}

// Fix URL paths in login.php, register.php, and logout.php
$baseUrl = "http://localhost/indian-culture-main/indian-culture-main";
$filesToCheck = ['login.php', 'register.php', 'logout.php', 'welcome.php', 'reset-password.php'];

foreach ($filesToCheck as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $modified = false;
        
        // Fix redirect URLs
        if (strpos($content, "header(\"location: ") !== false) {
            // Fix URLs that don't use the correct base URL
            $content = preg_replace(
                '/header\("location:\s*(?!http:\/\/localhost\/indian-culture-main\/indian-culture-main)([^"]+)"\)/',
                "header(\"location: {$baseUrl}\\1\")",
                $content,
                -1,
                $count
            );
            
            if ($count > 0) {
                $modified = true;
                addFixedIssue("Incorrect redirect URL in {$file}", "Updated redirect URLs to use the correct base URL");
            }
        }
        
        // Fix form action and href attributes
        if (strpos($content, "href=\"") !== false) {
            // Keep relative URLs for css/js/images, fix absolute ones
            $content = preg_replace(
                '/href="(?!http|https|\/\/|#|\.\.|css|js|images)\/([^"]+)"/',
                "href=\"{$baseUrl}/\\1\"",
                $content,
                -1,
                $count
            );
            
            if ($count > 0) {
                $modified = true;
                addFixedIssue("Incorrect href URLs in {$file}", "Updated href attributes to use the correct base URL");
            }
        }
        
        if ($modified) {
            file_put_contents($file, $content);
            addMessage('success', "Fixed URLs in {$file}");
        } else {
            addMessage('info', "No URL issues found in {$file}");
        }
    } else {
        addMessage('warning', "File {$file} does not exist");
    }
}

// ----------------------------------------
// STEP 3: Test User Functions
// ----------------------------------------
echo "<h2>3. User Authentication Functions Test</h2>";

// Check if we can create a test user
if (isset($conn)) {
    // Check if test user exists
    $testUser = 'testuser_' . rand(1000, 9999);
    $testEmail = $testUser . '@example.com';
    $testPass = 'test12345';
    
    // Create test user
    $hashedPassword = password_hash($testPass, PASSWORD_DEFAULT);
    
    try {
        // First check if user exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $testUser, $testEmail);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            addMessage('info', "Test user already exists, skipping creation");
        } else {
            // Create test user
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $testUser, $testEmail, $hashedPassword);
            
            if ($stmt->execute()) {
                addMessage('success', "Created test user '{$testUser}' successfully");
                addMessage('info', "Username: {$testUser}");
                addMessage('info', "Password: {$testPass}");
                addMessage('info', "Email: {$testEmail}");
                addFixedIssue("Test user validation", "Created a test user to verify the authentication system");
            } else {
                addMessage('error', "Failed to create test user: " . $stmt->error);
            }
        }
    } catch (Exception $e) {
        addMessage('error', "Error testing user creation: " . $e->getMessage());
    }
    
    // Close the database connection
    $conn->close();
}

// ----------------------------------------
// STEP 4: Display Summary & Provide Links
// ----------------------------------------
echo "<h2>4. Diagnostics Summary</h2>";

// Display all success messages
if (!empty($successMessages)) {
    echo "<h3>✅ Successful Operations</h3>";
    echo "<ul>";
    foreach ($successMessages as $msg) {
        echo "<li class='success'>{$msg}</li>";
    }
    echo "</ul>";
}

// Display warnings
if (!empty($warningMessages)) {
    echo "<h3>⚠️ Warnings</h3>";
    echo "<ul>";
    foreach ($warningMessages as $msg) {
        echo "<li class='warning'>{$msg}</li>";
    }
    echo "</ul>";
}

// Display errors
if (!empty($errorMessages)) {
    echo "<h3>❌ Errors</h3>";
    echo "<ul>";
    foreach ($errorMessages as $msg) {
        echo "<li class='error'>{$msg}</li>";
    }
    echo "</ul>";
}

// Display info messages
if (!empty($infoMessages)) {
    echo "<h3>ℹ️ Information</h3>";
    echo "<ul>";
    foreach ($infoMessages as $msg) {
        echo "<li class='info'>{$msg}</li>";
    }
    echo "</ul>";
}

// Display fixed issues
if (!empty($fixedIssues)) {
    echo "<h3>🔧 Fixed Issues</h3>";
    echo "<div class='fixed'>";
    foreach ($fixedIssues as $fix) {
        echo "<p><strong>{$fix['issue']}:</strong> {$fix['solution']}</p>";
    }
    echo "</div>";
}

// Provide navigation links
echo "<h3>Navigation</h3>";
echo "<p>Click the links below to test your application:</p>";
echo "<p>
    <a href='{$baseUrl}/' class='button'>Home Page</a>
    <a href='{$baseUrl}/php/login.php' class='button'>Login Page</a>
    <a href='{$baseUrl}/php/register.php' class='button'>Register Page</a>
</p>";

echo "</body></html>";
?> 