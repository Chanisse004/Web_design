<?php
// Database setup script
$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create AccountDB database
$sql = "CREATE DATABASE IF NOT EXISTS AccountDB";
if ($conn->query($sql) === TRUE) {
    echo "Database AccountDB created successfully<br>";
} else {
    echo "Error creating database AccountDB: " . $conn->error . "<br>";
}

// Create MarksDB database
$sql = "CREATE DATABASE IF NOT EXISTS MarksDB";
if ($conn->query($sql) === TRUE) {
    echo "Database MarksDB created successfully<br>";
} else {
    echo "Error creating database MarksDB: " . $conn->error . "<br>";
}

// Select AccountDB and create Credentials table
$conn->select_db("AccountDB");
$sql = "CREATE TABLE IF NOT EXISTS Credentials (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    FIRST_NAME VARCHAR(50) NOT NULL,
    LAST_NAME VARCHAR(50) NOT NULL,
    ADDRESS TEXT NOT NULL,
    TELEPHONE VARCHAR(20) NOT NULL,
    USER_NAME VARCHAR(50) NOT NULL UNIQUE,
    PASSWORD VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table Credentials created successfully in AccountDB<br>";
} else {
    echo "Error creating table Credentials: " . $conn->error . "<br>";
}

// Select MarksDB and create RecordsTB table
$conn->select_db("MarksDB");
$sql = "CREATE TABLE IF NOT EXISTS RecordsTB (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_name VARCHAR(100) NOT NULL,
    module_name VARCHAR(100) NOT NULL,
    cat1_marks DECIMAL(5,2) DEFAULT 0,
    cat2_marks DECIMAL(5,2) DEFAULT 0,
    fat_marks DECIMAL(5,2) DEFAULT 0,
    total_marks DECIMAL(5,2) DEFAULT 0,
    average_marks DECIMAL(5,2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table RecordsTB created successfully in MarksDB<br>";
} else {
    echo "Error creating table RecordsTB: " . $conn->error . "<br>";
}

$conn->close();
echo "Database setup completed successfully!";
?>
