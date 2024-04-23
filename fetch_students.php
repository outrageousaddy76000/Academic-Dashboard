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

// Check if roll number is provided
if(isset($_GET['roll'])) {
    // Sanitize input
    $roll = $mysqli->real_escape_string($_GET['roll']);

    // Prepare SQL statement with prepared statement
    $sql = "SELECT roll, name, phone_number, spi_sem1, spi_sem2, spi_sem3, spi_sem4, spi_sem5, spi_sem6, spi_sem7, spi_sem8, cpi_sem1, cpi_sem2, cpi_sem3, cpi_sem4, cpi_sem5, cpi_sem6, cpi_sem7, cpi_sem8, positions,backlog_courses, all_backlog_courses, minor_courses FROM students WHERE roll = ?";

    // Prepare statement
    if($stmt = $mysqli->prepare($sql)) {
        // Bind parameters
        $stmt->bind_param("s", $roll);

        // Execute SQL query
        $stmt->execute();

        // Get result
        $result = $stmt->get_result();

        // Check if query was successful
        if($result) {
            // Fetch student details as an associative array
            $student = $result->fetch_assoc();
            $performanceData = array();

            // Loop through semesters and add data if not 0.00
            for ($i = 1; $i <= 8; $i++) {
                if ($student["spi_sem$i"] != 0.00) {
                    $performanceData[] = array(
                        "semester" => "Sem $i",
                        "spi" => $student["spi_sem$i"],
                        "cpi" => $student["cpi_sem$i"]
                    );
                }
            }

            // Combine student details and performance data
            $combinedData = array(
                "student" => $student,
                "performance" => $performanceData
            );

            // Encode data as JSON
            echo json_encode($combinedData);
        } else {
            // Query failed
            echo "Error: " . $sql . "<br>" . $mysqli->error;
        }
        // Close statement
        $stmt->close();
    } else {
        // Statement preparation failed
        echo "Error: Unable to prepare statement";
    }
} else {
    // Roll number not provided
    echo "Roll number not provided";
}

// Close database connection
$mysqli->close();
?>
