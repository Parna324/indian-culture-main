echo "Attempting to fix/create the 'state_visits' table...<br>";

try {
    // Disable foreign key checks
    echo "- Temporarily disabling foreign key checks...<br>";
    if (mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 0;")) {
        echo "  - Foreign key checks DISABLED successfully.<br>";
    } else {
        echo "  <strong style='color:red;'>- FAILED to disable foreign key checks: " . mysqli_error($conn) . "</strong><br>";
        // Throw an exception to be caught below if this fails, as it's critical
        throw new Exception("Could not disable foreign key checks."); 
    }

    // SQL to drop the table if it exists
    $sql_drop = "DROP TABLE IF EXISTS state_visits;";
    echo "- Attempting to drop old table...<br>";
    if (mysqli_query($conn, $sql_drop)) {
        echo "  - Old 'state_visits' table dropped successfully.<br>";
    } else {
        // Throw exception if drop fails
        throw new mysqli_sql_exception("Error dropping table: " . mysqli_error($conn));
    }

    // SQL to create the table correctly
    $sql_create = "CREATE TABLE state_visits (
        id INT AUTO_INCREMENT PRIMARY KEY,
        state_name VARCHAR(100) NOT NULL UNIQUE,
        best_time VARCHAR(255),
        description TEXT,
        culture TEXT
    );";
    echo "- Attempting to create new table...<br>";
    if (mysqli_query($conn, $sql_create)) {
        echo "  - New 'state_visits' table created successfully.<br>";
    } else {
        // Throw exception if create fails
         throw new mysqli_sql_exception("CRITICAL ERROR creating table: " . mysqli_error($conn));
    }

    // Re-enable foreign key checks (inside try, as it depends on success so far)
    echo "- Re-enabling foreign key checks...<br>";
    if (mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 1;")) {
        echo "  - Foreign key checks ENABLED successfully.<br>";
    } else {
         echo "  <strong style='color:orange;'>- Warning: FAILED to re-enable foreign key checks: " . mysqli_error($conn) . "</strong><br>";
    }

    echo "Table setup complete.<br><hr>";

} catch (mysqli_sql_exception $e) {
    // Catch specific SQL exceptions from DROP or CREATE
    die("<strong style='color:red;'>FATAL SQL ERROR during table setup: " . $e->getMessage() . ". Script halted.</strong><br>Check foreign key constraints manually in phpMyAdmin if this persists.");
} catch (Exception $e) {
    // Catch other general exceptions (like failing to disable checks)
     die("<strong style='color:red;'>FATAL ERROR during table setup: " . $e->getMessage() . ". Script halted.</strong>");
}

echo "Attempting to populate states...<br>";
// ... rest of the population code ... 