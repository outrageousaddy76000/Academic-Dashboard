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
$courseCode = strtoupper($_POST['courseCode']);
$courseName = $_POST['courseName'];
$semester = $_POST['semester'];
$deptName = $_POST['deptName'];
$courseType = $_POST['courseType'];
$credit = $_POST['credit'];
$creditStructure = $_POST['creditStructure'];
$year = $_POST['year'];
$elective = isset($_POST['elective']) ? 1 : 0; // Convert checkbox value to boolean
// Check if the course code already exists in the database
$sql = "SELECT * FROM courses WHERE course_code = '$courseCode'";
$result = $mysqli->query($sql);
if ($result->num_rows > 0) {
    // If course code exists, send error response
    echo "Course with the same course code already exists! Please remove the course first before adding";
    exit();
}


// Insert data into the database
$sql = "INSERT INTO courses (course_code, course_name, semester, dept_name, course_type, credit, credit_structure, year, elective) VALUES ('$courseCode', '$courseName', '$semester', '$deptName', '$courseType', $credit, '$creditStructure', $year, $elective)";
// Execute SQL query
if ($mysqli->query($sql) === TRUE) {
    // If insertion is successful, send success response
    echo "Course added successfully!";
} else {
    // If an error occurs, send error response
    echo "Error: " . $sql . "<br>" . $mysqli->error;
}

// Close database connection
$mysqli->close();
?>
