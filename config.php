<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'akademik_db');

// Create database connection
function getDBConnection() {
    try {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        $conn->set_charset("utf8");
        return $conn;
    } catch (Exception $e) {
        die("Database connection error: " . $e->getMessage());
    }
}

// Helper function to sanitize input
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Helper function to send JSON response
function sendJsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// Helper function to get POST data
function getPostData() {
    return json_decode(file_get_contents('php://input'), true);
}
?>
