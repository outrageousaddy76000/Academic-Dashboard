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

// Get data from the AJAX request (sent via POST)
$roll = $mysqli->real_escape_string($_POST['roll']); // Assuming 'roll' is hidden in the form
$phone_number = $mysqli->real_escape_string($_POST['phone_number']);
$cpi_sem1 = $mysqli->real_escape_string($_POST['cpi_sem1']);
$cpi_sem2 = $mysqli->real_escape_string($_POST['cpi_sem2']);
$cpi_sem3 = $mysqli->real_escape_string($_POST['cpi_sem3']);
$cpi_sem4 = $mysqli->real_escape_string($_POST['cpi_sem4']);
$cpi_sem5 = $mysqli->real_escape_string($_POST['cpi_sem5']);
$cpi_sem6 = $mysqli->real_escape_string($_POST['cpi_sem6']);
$cpi_sem7 = $mysqli->real_escape_string($_POST['cpi_sem7']);
$cpi_sem8 = $mysqli->real_escape_string($_POST['cpi_sem8']);
$spi_sem1 = $mysqli->real_escape_string($_POST['spi_sem1']);
$spi_sem2 = $mysqli->real_escape_string($_POST['spi_sem2']);
$spi_sem3 = $mysqli->real_escape_string($_POST['spi_sem3']);
$spi_sem4 = $mysqli->real_escape_string($_POST['spi_sem4']);
$spi_sem5 = $mysqli->real_escape_string($_POST['spi_sem5']);
$spi_sem6 = $mysqli->real_escape_string($_POST['spi_sem6']);
$spi_sem7 = $mysqli->real_escape_string($_POST['spi_sem7']);
$spi_sem8 = $mysqli->real_escape_string($_POST['spi_sem8']);
$positions = $mysqli->real_escape_string($_POST['positions']);
$backlog_courses = strtoupper($mysqli->real_escape_string($_POST['backlog_courses']));
$minor_courses = strtoupper($mysqli->real_escape_string($_POST['minor_courses']));

// Prepare the SQL UPDATE statement
$sql = "UPDATE students SET 
        phone_number = ?, 
        cpi_sem1 = ?, 
        cpi_sem2 = ?, 
        cpi_sem3 = ?,
        cpi_sem4 = ?,
        cpi_sem5 = ?,
        cpi_sem6 = ?,
        cpi_sem7 = ?,
        cpi_sem8 = ?,
        spi_sem1 = ?,
        spi_sem2 = ?,
        spi_sem3 = ?,
        spi_sem4 = ?,
        spi_sem5 = ?,
        spi_sem6 = ?,
        spi_sem7 = ?, 
        spi_sem8 = ?,
        positions = ?,
        backlog_courses = ?,
        minor_courses = ?
        WHERE roll = ?"; 

// Create a prepared statement
$stmt = $mysqli->prepare($sql);

//if cpi or spi is empty or not a decimal, set it to 0.00
for($i=1; $i<=8; $i++){
    if(${"cpi_sem$i"} == '' || !is_numeric(${"cpi_sem$i"})){
        ${"cpi_sem$i"} = 0.00;
    }
    if(${"spi_sem$i"} == '' || !is_numeric(${"spi_sem$i"})){
        ${"spi_sem$i"} = 0.00;
    }
}


// Bind parameters to the statement
$stmt->bind_param("sssssssssssssssssssss", $phone_number, 
    $cpi_sem1, $cpi_sem2, $cpi_sem3, $cpi_sem4, $cpi_sem5, $cpi_sem6, $cpi_sem7, $cpi_sem8, 
    $spi_sem1, $spi_sem2, $spi_sem3, $spi_sem4, $spi_sem5, $spi_sem6, $spi_sem7, $spi_sem8, 
    $positions, $backlog_courses, $minor_courses, $roll);


// Execute the statement
$stmt->execute();

// Check if the update was successful
if ($stmt->affected_rows > 0) {
    //create table if not exist
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
    // Update all_backlog_courses
    $backlog_courses_array = explode(',', strtoupper($_POST['backlog_courses']));
    $backlog_courses_array = array_map('trim', $backlog_courses_array); // Trim whitespace from each course code
    $existing_backlog_courses = $mysqli->query("SELECT all_backlog_courses FROM students WHERE roll = '$roll'")->fetch_assoc()['all_backlog_courses'];
    $existing_backlog_courses_array = explode(',', $existing_backlog_courses);
    $new_backlog_courses = array_diff($backlog_courses_array, $existing_backlog_courses_array);
    $updated_backlog_courses = implode(',', array_unique(array_merge($existing_backlog_courses_array, $new_backlog_courses)));
    $mysqli->query("UPDATE students SET all_backlog_courses = '$updated_backlog_courses' WHERE roll = '$roll'");

    echo "Student updated successfully";
} else if ($stmt->affected_rows === 0) {
    // No rows were affected, so no update occurred
    echo "No changes were made.";
} else {
    // There was an error updating
    echo "Error updating student: " . $stmt->error; // Provide error details for debugging
}


// Close the prepared statement and database connection
$stmt->close();
$mysqli->close();
?>