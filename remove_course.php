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

// Retrieve course code to remove from form data
$courseCodeToRemove = strtoupper($_POST['courseCodeToRemove']);
//create table if not exists
$sql = "CREATE TABLE IF NOT EXISTS courses (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    course_code VARCHAR(10) NOT NULL,
    course_name VARCHAR(100) NOT NULL,
    semester VARCHAR(10) NOT NULL,
    dept_name VARCHAR(100) NOT NULL,
    course_type VARCHAR(100) NOT NULL,
    credit INT(2) NOT NULL,
    credit_structure VARCHAR(100) NOT NULL,
    year INT(4) NOT NULL,
    elective BOOLEAN NOT NULL
)";

// Check if the course code exists in the database
$sql_check = "SELECT * FROM courses WHERE course_code = '$courseCodeToRemove'";
$result_check = $mysqli->query($sql_check);

if ($result_check->num_rows == 0) {
    // If course code does not exist, send error response
    echo "Course with the specified course code does not exist!";
} else {
    // Course code exists, proceed with deletion
    // SQL to delete course from database
    $sql = "DELETE FROM courses WHERE course_code = '$courseCodeToRemove'";

    // Execute SQL query
    if ($mysqli->query($sql) === TRUE) {
        // If deletion is successful, send success response
        echo "Course removed successfully!";
    } else {
        // If an error occurs, send error response
        echo "Error: " . $sql . "<br>" . $mysqli->error;
    }
}

// Close database connection
$mysqli->close();
?>
