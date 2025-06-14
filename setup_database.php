<?php
$host = 'localhost';
$username = 'root';
$password = '';

try {
    // Create connection without database
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database
    $sql = "CREATE DATABASE IF NOT EXISTS hr_management";
    $pdo->exec($sql);
    echo "Database created successfully<br>";
    
    // Select the database
    $pdo->exec("USE hr_management");
    
    // Create employees table
    $sql = "CREATE TABLE IF NOT EXISTS employees (
        emp_id VARCHAR(20) PRIMARY KEY,
        emp_name VARCHAR(100) NOT NULL,
        emp_phone VARCHAR(20) NOT NULL,
        emp_role VARCHAR(100) NOT NULL,
        emp_gmail VARCHAR(100) NOT NULL UNIQUE,
        emp_join_date DATE NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "Employees table created successfully<br>";
    
    echo "<br>Database setup completed successfully! <a href='dashboard.php?section=employees'>Go to Employees Section</a>";
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 