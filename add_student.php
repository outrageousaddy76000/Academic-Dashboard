<?php
//include config file
include 'config.php';

//access database configuration
$host = $dbConfig['host'];
$username = $dbConfig['username'];
$password = $dbConfig['password'];
$database = $dbConfig['database'];

//MySQLi connection for database
$mysqli = new mysqli($host, $username, $password, $database);

//Check connection, if connection failed, show error message
if ($mysqli->connect_errno) {
    die("Connection failed: " . $mysqli->connect_error);
}

//Retrieve form data from the POST request
$roll = strtoupper($_POST['roll']);
$name = $_POST['name'];
//if table does not exist create table
$sql = "CREATE TABLE IF NOT EXISTS students (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    roll VARCHAR(10) NOT NULL,
    name VARCHAR(100) NOT NULL,
    phone_number VARCHAR(10) NOT NULL,
    spi_sem1 FLOAT(4,2) NOT NULL,
    spi_sem2 FLOAT(4,2) NOT NULL,
    spi_sem3 FLOAT(4,2) NOT NULL,
    spi_sem4 FLOAT(4,2) NOT NULL,
    spi_sem5 FLOAT(4,2) NOT NULL,
    spi_sem6 FLOAT(4,2) NOT NULL,
    spi_sem7 FLOAT(4,2) NOT NULL,
    spi_sem8 FLOAT(4,2) NOT NULL,
    cpi_sem1 FLOAT(4,2) NOT NULL,
    cpi_sem2 FLOAT(4,2) NOT NULL,
    cpi_sem3 FLOAT(4,2) NOT NULL,
    cpi_sem4 FLOAT(4,2) NOT NULL,
    cpi_sem5 FLOAT(4,2) NOT NULL,
    cpi_sem6 FLOAT(4,2) NOT NULL,
    cpi_sem7 FLOAT(4,2) NOT NULL,
    cpi_sem8 FLOAT(4,2) NOT NULL,
    positions VARCHAR(100) NOT NULL,
    backlog_courses VARCHAR(100) NOT NULL,
    all_backlog_courses VARCHAR(100) NOT NULL,
    minor_courses VARCHAR(100) NOT NULL
)";

//Check if the student already exists in the database
$sql = "SELECT * FROM students WHERE roll = '$roll'";
$result = $mysqli->query($sql);
if ($result->num_rows > 0) {
    //If student exists, send error response
    echo "Student with the same roll number already exists! Please remove the student first before adding";
    exit();
}

//Insert data into the database if student does not exist
$sql = "INSERT INTO students (roll, name) VALUES ('$roll', '$name')";

//Execute SQL query
if ($mysqli->query($sql) === TRUE) {
    //If insertion is successful, send success response
    echo "Student added successfully!";
} else {
    //If an error occurs, send error response
    echo "Error: " . $sql . "<br>" . $mysqli->error;
}

//Close database connection
$mysqli->close();
?>