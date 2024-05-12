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

// Check if roll number to remove is set
if (isset($_POST['rollToRemove'])) {
    $rollToRemove = $_POST['rollToRemove'];

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

    // Prepare a delete statement
    $sql = "DELETE FROM students WHERE roll = ?";

    if ($stmt = $mysqli->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("s", $rollToRemove);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            echo "Student removed successfully.";
        } else {
            echo "Error removing student.";
        }

        // Close statement
        $stmt->close();
    } else {
        echo "Error preparing statement.";
    }

    // Close connection
    $mysqli->close();
} else {
    echo "Roll number to remove is not set.";
}
?>
