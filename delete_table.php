<?php
// Delete RecordsTB table
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "AccountDB";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete table
$sql = "DROP TABLE IF EXISTS RecordsTB";

if ($conn->query($sql) === TRUE) {
    echo "Table RecordsTB deleted successfully";
} else {
    echo "Error deleting table: " . $conn->error;
}

$conn->close();
?>
