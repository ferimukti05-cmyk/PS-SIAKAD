<?php
require_once 'config.php';

echo "Setting up Database...\n\n";

try {
    // Connect to MySQL server without specifying database
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS);
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    echo "✓ Connected to MySQL server!\n";

    // Drop database if exists and recreate
    $conn->query("DROP DATABASE IF EXISTS " . DB_NAME);
    echo "✓ Dropped existing database if any\n";

    // Create database if it doesn't exist
    $sql = file_get_contents('database.sql');
    if ($sql === false) {
        throw new Exception("Could not read database.sql file");
    }

    // Split the SQL file into individual statements
    $statements = array_filter(array_map('trim', explode(';', $sql)));

    foreach ($statements as $statement) {
        if (!empty($statement)) {
            echo "Executing: " . substr($statement, 0, 50) . "...\n";
            if ($conn->query($statement) === TRUE) {
                echo "✓ Success\n";
            } else {
                echo "✗ Error: " . $conn->error . "\n";
            }
        }
    }

    echo "\n✓ Database setup completed!\n";
    $conn->close();

} catch (Exception $e) {
    echo "✗ Database setup failed: " . $e->getMessage() . "\n";
    echo "Please ensure:\n";
    echo "1. XAMPP is running (Apache and MySQL)\n";
    echo "2. MySQL service is started\n";
    echo "3. database.sql file exists\n";
}
?>
