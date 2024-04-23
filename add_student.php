<?php
// Include config file
include 'config.php';

// Access database configuration
$host = $dbConfig['host'];
$username = $dbConfig['username'];
$password = $dbConfig['password'];
$database = $dbConfig['database'];

// MySQLi connection
$mysqli = new mysqli($host, $username, $password, $database);

// Check connection
if ($mysqli->connect_errno) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Retrieve form data
$roll = strtoupper($_POST['roll']);
$name = $_POST['name'];

// Check if the student already exists in the database
$sql = "SELECT * FROM students WHERE roll = '$roll'";
$result = $mysqli->query($sql);
if ($result->num_rows > 0) {
    // If student exists, send error response
    echo "Student with the same roll number already exists! Please remove the student first before adding";
    exit();
}

// Insert data into the database
$sql = "INSERT INTO students (roll, name) VALUES ('$roll', '$name')";
// Execute SQL query
if ($mysqli->query($sql) === TRUE) {
    // If insertion is successful, send success response
    echo "Student added successfully!";
} else {
    // If an error occurs, send error response
    echo "Error: " . $sql . "<br>" . $mysqli->error;
}

// Close database connection
$mysqli->close();
?>