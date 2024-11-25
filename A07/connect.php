<?php
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$db = "palla_activity4";

// Initialize the connection
$conn = new mysqli($dbhost, $dbuser, $dbpass, $db);

// Check if connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to execute queries
function executeQuery($query) {
    global $conn;  // Use the global $conn variable inside the function
    return mysqli_query($conn, $query);
}
?>
