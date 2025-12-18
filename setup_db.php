<?php
// Include database connection
require_once 'backend/db.php';

function executeSqlFile($pdo, $file) {
    // Read the SQL file
    $sql = file_get_contents($file);
    
    if ($sql === false) {
        die("Error: Unable to read SQL file: $file");
    }

    try {
        // Execute the SQL
        $pdo->exec($sql);
        echo "Database setup completed successfully!";
    } catch (PDOException $e) {
        die("Error executing SQL: " . $e->getMessage());
    }
}

try {
    // Create database connection
    $database = new Database();
    $pdo = $database->getConnection();
    
    // Execute the SQL file
    executeSqlFile($pdo, 'database.sql');
    
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}