<?php
// Include database configuration
require_once "config.php";

echo "<html><head><title>Database Update</title>";
echo "<style>
    body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
    h1 { color: #333; }
    .success { color: green; background: #eeffee; padding: 10px; border-radius: 5px; }
    .error { color: red; background: #ffeeee; padding: 10px; border-radius: 5px; }
    .info { color: blue; background: #eeeeff; padding: 10px; border-radius: 5px; }
    a { display: inline-block; margin-top: 20px; padding: 10px 15px; background: #4CAF50; color: white; 
        text-decoration: none; border-radius: 5px; }
    a:hover { background: #45a049; }
</style></head><body>";

echo "<h1>Database Update Tool</h1>";

// Function to check if column exists
function columnExists($conn, $table, $column) {
    $sql = "SHOW COLUMNS FROM `$table` LIKE '$column'";
    $result = $conn->query($sql);
    return $result->num_rows > 0;
}

// Check if the email column already exists
if(columnExists($conn, "users", "email")) {
    echo "<p class='info'>The 'email' column already exists in the 'users' table.</p>";
} else {
    // Add the email column
    $sql = "ALTER TABLE users ADD COLUMN email VARCHAR(255) NOT NULL UNIQUE AFTER username";
    
    if($conn->query($sql) === TRUE) {
        echo "<p class='success'>Successfully added 'email' column to 'users' table.</p>";
    } else {
        echo "<p class='error'>Error adding 'email' column: " . $conn->error . "</p>";
    }
}

// Output database information for debugging
echo "<h2>Current Database Structure</h2>";
$result = $conn->query("DESCRIBE users");
if($result && $result->num_rows > 0) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["Field"] . "</td>";
        echo "<td>" . $row["Type"] . "</td>";
        echo "<td>" . $row["Null"] . "</td>";
        echo "<td>" . $row["Key"] . "</td>";
        echo "<td>" . ($row["Default"] === NULL ? "NULL" : $row["Default"]) . "</td>";
        echo "<td>" . $row["Extra"] . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
} else {
    echo "<p class='error'>Error retrieving table structure: " . $conn->error . "</p>";
}

$conn->close();

echo "<p><a href='http://localhost/indian-culture-main/indian-culture-main/php/register.php'>Go to Registration Page</a></p>";

echo "</body></html>";
?> 