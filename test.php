<?php
// Basic PHP test
echo "<h1>PHP Test Page</h1>";

// Test PHP version
echo "<p>PHP Version: " . phpversion() . "</p>";

// Test basic functionality
echo "<p>Current time: " . date('Y-m-d H:i:s') . "</p>";

// Test if we can write to the directory
$test_file = "test_write.txt";
if (file_put_contents($test_file, "Test successful!")) {
    echo "<p>File write test: Success</p>";
    unlink($test_file); // Clean up
} else {
    echo "<p>File write test: Failed</p>";
}

// Show PHP configuration
echo "<h2>PHP Configuration</h2>";
echo "<pre>";
print_r(get_loaded_extensions());
echo "</pre>";
?> 