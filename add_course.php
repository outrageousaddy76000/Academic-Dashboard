<?php
//include config file
include 'config.php';

//access database configuration
$host = $dbConfig['host'];
$username = $dbConfig['username'];
$password = $dbConfig['password'];
$database = $dbConfig['database'];

//using MySQLi connection for database
$mysqli = new mysqli($host, $username, $password, $database);

//Check connection, if failed - return error message
if ($mysqli->connect_errno) {
    die("Connection failed: " . $mysqli->connect_error);
}

//Retrieve form data from the POST request
$courseCode = strtoupper($_POST['courseCode']);
$courseName = $_POST['courseName'];
$semester = $_POST['semester'];
$deptName = $_POST['deptName'];
$courseType = $_POST['courseType'];
$credit = $_POST['credit'];
$creditStructure = $_POST['creditStructure'];
$year = $_POST['year'];
$elective = isset($_POST['elective']) ? 1 : 0; //Convert checkbox value to boolean
//Check if the course code already exists in the database
$sql = "SELECT * FROM courses WHERE course_code = '$courseCode'";
$result = $mysqli->query($sql);
if ($result->num_rows > 0) {
    //If course code exists, send error response
    echo "Course with the same course code already exists! Please remove the course first before adding";
    exit();
}


//If table does not exit, create table
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

//Insert data into the database
$sql = "INSERT INTO courses (course_code, course_name, semester, dept_name, course_type, credit, credit_structure, year, elective) VALUES ('$courseCode', '$courseName', '$semester', '$deptName', '$courseType', $credit, '$creditStructure', $year, $elective)";
//Execute SQL query and check if it was successful
if ($mysqli->query($sql) === TRUE) {
    //If insertion is successful, send success response
    echo "Course added successfully!";
} else {
    //If an error occurs, send error response
    echo "Error: " . $sql . "<br>" . $mysqli->error;
}

//Close database connection
$mysqli->close();
?>
