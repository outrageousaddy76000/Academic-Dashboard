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
