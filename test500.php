<?php
// Create a simple test to check PHP limits and log environment info
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Test memory
echo "<h3>Memory Limit: " . ini_get("memory_limit") . "</h3>";

// Test execution time
echo "<h3>Max Execution Time: " . ini_get("max_execution_time") . "</h3>";

// Create a large loop to test timeouts
for ($i = 0; $i < 1000000; $i++) {
    if ($i % 100000 === 0) {
        echo "Progress: $i<br>";
        flush(); // Push output
    }
}

echo "<h3>Test Completed</h3>";
?>
