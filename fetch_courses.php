<?php
// Include config file and establish database connection
include 'config.php';

// Check if year and semester are set
if(isset($_POST['year'], $_POST['semester'])) {
    $year = $_POST['year'];
    $semester = $_POST['semester'];

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

    // Query to fetch courses based on year and semester
    // Example query, replace with your actual query to fetch courses
    //if table does not exist create table
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
    $query = "SELECT * FROM courses WHERE year = $year AND semester = '$semester'";
    $result = $mysqli->query($query);
    // Check if query executed successfully
    if ($result) {
        if ($result->num_rows > 0) {
            echo '<div class="container">';
            echo '<div class="row">';
            echo '<div class="col-sm-12">';
            echo '<h2 class="text-center">Core Courses</h2>';
            echo '</div>';
            echo '</div>';
            echo '<div class="row">';
            echo '<div class="col-sm-12">';
            echo '<div class="table-container" style="width: 100%; overflow-x: auto;">'; // Container with horizontal scrolling
            echo '<table class="table table-striped">';
            echo '<thead>';
            echo '<tr>';
            echo '<th class="text-center">Course Code</th>';
            echo '<th class="text-center">Course Name</th>';
            echo '<th class="text-center">Semester</th>';
            echo '<th class="text-center">Department</th>';
            echo '<th class="text-center">Course Type</th>';
            echo '<th class="text-center">Credit</th>';
            echo '<th class="text-center">Credit Structure</th>';
            echo '<th class="text-center">Year</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            $totalCredits=0;
            // Loop through each row
            while ($row = $result->fetch_assoc()) {
                // Display non-elective courses only
                if (!$row['elective']) {
                    echo '<tr>';
                    echo '<td class="text-center">' . $row['course_code'] . '</td>';
                    echo '<td class="text-center">' . $row['course_name'] . '</td>';
                    echo '<td class="text-center">' . $row['semester'] . '</td>';
                    echo '<td class="text-center">' . $row['dept_name'] . '</td>';
                    echo '<td class="text-center">' . $row['course_type'] . '</td>';
                    echo '<td class="text-center">' . $row['credit'] . '</td>';
                    echo '<td class="text-center">' . $row['credit_structure'] . '</td>';
                    echo '<td class="text-center">' . $row['year'] . '</td>';
                    echo '</tr>';
                    // Add credit to totalCredits
                    $totalCredits += $row['credit'];
                }
            }
            echo '</tbody>';
            // Display the total row in the table footer
            echo '<tfoot>';
            echo '<tr height="75px">';
            echo '<td colspan="5" class="text-center">Total Credits:</td>';
            echo '<td colspan="3" class="text-center">' . $totalCredits . '</td>';
            echo '</tr>';
            echo '</tfoot>';
            echo '</table>';
            echo '</div>'; // Close table-container
            echo '</div>';
            echo '</div>'; // Close core courses row and container
        
            // Check if there are elective courses
            $electiveCoursesExist = false;
            $result->data_seek(0); // Reset the result pointer
            while ($row = $result->fetch_assoc()) {
                if ($row['elective']) {
                    $electiveCoursesExist = true;
                    break;
                }
            }
        
            // Output elective courses without displaying their total credits if they exist
            if ($electiveCoursesExist) {
                echo '<div class="row">';
                echo '<div class="col-sm-12">';
                echo '<h2 class="text-center">Elective Courses</h2>';
                echo '</div>';
                echo '</div>';
                echo '<div class="row">';
                echo '<div class="col-sm-12">';
                echo '<div class="table-container" style="width: 100%; overflow-x: auto;">'; // Container with horizontal scrolling
                echo '<table class="table table-striped">';
                echo '<thead>';
                echo '<tr>';
                echo '<th class="text-center">Course Code</th>';
                echo '<th class="text-center">Course Name</th>';
                echo '<th class="text-center">Semester</th>';
                echo '<th class="text-center">Department</th>';
                echo '<th class="text-center">Course Type</th>';
                echo '<th class="text-center">Credit</th>';
                echo '<th class="text-center">Credit Structure</th>';
                echo '<th class="text-center">Year</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
        
                // Loop through each row again to display elective courses
                $result->data_seek(0); // Reset the result pointer
                while ($row = $result->fetch_assoc()) {
                    if ($row['elective']) {
                        echo '<tr>';
                        echo '<td class="text-center">' . $row['course_code'] . '</td>';
                        echo '<td class="text-center">' . $row['course_name'] . '</td>';
                        echo '<td class="text-center">' . $row['semester'] . '</td>';
                        echo '<td class="text-center">' . $row['dept_name'] . '</td>';
                        echo '<td class="text-center">' . $row['course_type'] . '</td>';
                        echo '<td class="text-center">' . $row['credit'] . '</td>';
                        echo '<td class="text-center">' . $row['credit_structure'] . '</td>';
                        echo '<td class="text-center">' . $row['year'] . '</td>';
                        echo '</tr>';
                    }
                }
                echo '</tbody>';
                echo '</table>';
                echo '</div>'; // Close table-container
                echo '</div>';
                echo '</div>'; // Close elective courses row and container
            }
        }else {
            echo "<p>No courses found for Year $year, $semester semester.</p>";
        }
    }
     else {
        echo "<p>Error fetching courses.</p>";
    }
    // Close database connection
    $mysqli->close();
} else {
    echo "<p>Invalid request.</p>";
}
?>
