<?php
// Check if the email is set in localStorage
echo "<script>
        var email = localStorage.getItem('email');
        if (!email) {
            // Redirect to the login page
            window.location.href = 'login.php';
        }
      </script>";

// Database connection parameters
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

// Function to populate table rows
function populate_table($mysqli) {
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
    //fetch from database and sort by roll number in ascending order
    $sql = "SELECT * FROM students ORDER BY roll ASC";
    $result = $mysqli->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            //calculate year
            $year = date('Y') - (int)substr($row['roll'], 0, 2);
            $year = $year % 10;
            //calculate last cpi_sem with>0
            $cpi = 0;
            for ($i = 1; $i <= 8; $i++) {
                if ($row['cpi_sem' . $i] > 0) {
                    $cpi = $row['cpi_sem' . $i];
                }
            }
            $nameWithPosition = $row['name'];
            if ($row['positions']) {
                $nameWithPosition .= " (<span class='position-text'>" . $row['positions'] . "</span>)"; 
            }
            $backlogText = $row['backlog_courses'] ? '<span class="backlog-yes">Yes</span>' : 'No';
            //if backlog_courses is not null echo yes else no
            // Start row with click handler
            echo '<tr class="student-row" data-roll="' . $row['roll'] . '">';
            echo '<td>' . $row['roll'] . '</td>';
            echo '<td>' . $nameWithPosition . '</td>';
            echo '<td>' . $backlogText . '</td>';
            echo '<td>' . $row['phone_number'] . '</td>';
            echo '<td>' . $cpi . '</td>';
            echo '<td>' . $year . '</td>';
            echo '</tr>';
        }
    }
}

?>
<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Students</title>
    <link rel="icon" type="image/png" sizes="697x768" href="assets/img/RGIPT%20Logo.png?h=da992af9ac2e0a09cf93cc62adbb5ae4">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css?h=97380e22c8933e9aa79cbc2390b9f15a">
    <link rel="stylesheet" href="assets/css/Nunito.css?h=af3d911350614f13e63e169d51c66bd1">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.0/css/all.css">
    <link rel="stylesheet" href="assets/css/Filter.css?h=4467af38b8c3b27bbeb3b739717b0c06">
    <style>
        .student-row {
            cursor: pointer;
        }
        .position-text {
            color: blue;
        }
        .backlog-yes {
            color: red;
        }
    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        <nav class="navbar align-items-start sidebar sidebar-dark accordion bg-gradient-primary p-0 navbar-dark">
            <div class="container-fluid d-flex flex-column p-0"><a class="navbar-brand d-flex justify-content-center align-items-center sidebar-brand m-0" href="#"><img width="40" height="40" src="assets/img/RGIPT%20Logo.png?h=da992af9ac2e0a09cf93cc62adbb5ae4">
                    <div class="sidebar-brand-icon rotate-n-15"></div>
                    <div class="sidebar-brand-text mx-3"><span>Academic<br>Dashboard</span></div>
                </a>
                <hr class="sidebar-divider my-0">
                <ul class="navbar-nav text-light" id="accordionSidebar">
                    <li class="nav-item"><a class="nav-link" href="index.php"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="courses.php"><i class="fas fa-book-reader"></i><span>Courses</span></a></li>
                    <li class="nav-item"><a class="nav-link active" href="students.php"><i class="fas fa-users"></i><span>Students</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="remind.php"><i class="fas fa-hourglass-start"></i><span>Remind</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a></li>
                </ul>
                <div class="text-center d-none d-md-inline"><button class="btn rounded-circle border-0" id="sidebarToggle" type="button"></button></div>
            </div>
        </nav>
        <div class="d-flex flex-column" id="content-wrapper">
            <div id="content">
                <div class="container-fluid" style="padding-top: 0px;margin-top: 10px;">
                    <h3 class="text-dark mb-4">Students</h3>
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="row justify-content-between">
                                <div class="col-md-6">
                                    <button class="btn btn-success me-3" type="button" data-bs-toggle="modal" data-bs-target="#addModal">Add</button>
                                    <button class="btn btn-danger me-3" type="button" data-bs-toggle="modal" data-bs-target="#removeModal">Remove</button>
                                </div>
                            </div>
                            <!-- Add Modal -->
                            <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="addModalLabel">Add Student</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- create a form to ask for student's data -->
                                            <form id="addStudentForm"> <!-- Added id attribute -->
                                                <div class="mb-3">
                                                    <label for="roll" class="form-label">Roll Number</label>
                                                    <input type="text" class="form-control" id="roll" name="roll" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Name</label>
                                                    <input type="text" class="form-control" id="name" name="name" required>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Add</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Remove Modal -->
                            <div class="modal fade" id="removeModal" tabindex="-1" aria-labelledby="removeModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="removeModalLabel">Remove Student</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="removeStudentForm">
                                                <div class="mb-3">
                                                    <label for="rollToRemove" class="form-label">Roll Number to Remove</label>
                                                    <input type="text" class="form-control" id="rollToRemove" name="rollToRemove" required>
                                                </div>
                                                <button type="submit" class="btn btn-danger">Remove</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Details Modal -->
                            <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="detailsModalLabel" style="color: black; font-weight: bold;">Student Details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body" style="margin-bottom: 20px;">
                                            <div class="details">
                                                <p><strong>Roll Number:</strong> <span id="rollNumber"></span></p>
                                                <p><strong>Name:</strong> <span id="studentName"></span></p>
                                                <p><strong>Phone Number:</strong> <span id="phoneNumber"></span></p>
                                                <!-- CPI Dropdown -->
                                                <p><strong>CPI:</strong>
                                                    <select id="cpiDropdown" class="form-select"></select>
                                                </p>
                                                <!-- SPI Dropdown -->
                                                <p><strong>SPI:</strong>
                                                    <select id="spiDropdown" class="form-select"></select>
                                                </p>
                                                <!-- Positions -->
                                                <p id="positionsDetails" style="display: none;"><strong>Positions:</strong> <span id="positionsValue"></span></p>
                                                <!-- Backlog Courses -->
                                                <p id="backlogCoursesDetails" style="display: none;"><strong>Backlog Courses:</strong> <span id="backlogCoursesValue"></span></p>
                                                <!-- All Backlog Courses -->
                                                <p id="allBacklogCoursesDetails" style="display: none;"><strong>All Backlog Courses:</strong> <span id="allBacklogCoursesValue"></span></p>
                                                <!-- Minor Courses -->
                                                <p id="minorCoursesDetails" style="display: none;"><strong>Minor Courses:</strong> <span id="minorCoursesValue"></span></p>
                                            </div>
                                            <!-- Canvas for performance chart -->
                                            <canvas id="performanceChart"></canvas>
                                        </div>
                                        <div class="modal-footer">
                                            <!-- Add button to update student details -->
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateModal">Update</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Update Modal -->
                            <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="updateModalLabel" style="color: black; font-weight: bold;">Update Student Information</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body" style="margin-top: 20px;">
                                            <form id="updateStudentForm">
                                                <div class="mb-3">
                                                    <label for="updatePhoneNumber" class="form-label">Phone Number</label>
                                                    <input type="text" class="form-control" id="updatePhoneNumber" name="phone_number" pattern="[0-9]{10}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="cpiDropdown" class="form-label">CPI</label>
                                                    <div class="row">
                                                        <?php for ($i = 1; $i <= 8; $i++): ?>
                                                        <div class="col-md-3">
                                                            <input type="number" class="form-control" id="cpi_sem<?= $i ?>" name="cpi_sem<?= $i ?>" placeholder="Sem <?= $i ?>" step="0.01"> 
                                                        </div>
                                                        <?php endfor; ?>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="spiDropdown" class="form-label">SPI</label>
                                                    <div class="row">
                                                        <?php for ($i = 1; $i <= 8; $i++): ?>
                                                        <div class="col-md-3">
                                                            <input type="number" class="form-control" id="spi_sem<?= $i ?>" name="spi_sem<?= $i ?>" placeholder="Sem <?= $i ?>" step="0.01"> 
                                                        </div>
                                                        <?php endfor; ?>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="updatePositions" class="form-label">Positions</label>
                                                    <input type="text" class="form-control" id="updatePositions" name="positions">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="updateBacklogCourses" class="form-label">Backlog Courses</label>
                                                    <input type="text" class="form-control" id="updateBacklogCourses" name="backlog_courses">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="updateMinorCourses" class="form-label">Minor Courses</label>
                                                    <input type="text" class="form-control" id="updateMinorCourses" name="minor_courses">
                                                </div>
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!--Table to display student details -->
                            <div class="table-responsive table mt-2" id="dataTable" role="grid" aria-describedby="dataTable_info">
                                <table class="table my-0" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>Roll Number</th>
                                            <th>Name</th>
                                            <th>Backlog Courses</th>
                                            <th>Phone Number</th>
                                            <th>CPI</th>
                                            <th>Year</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php populate_table($mysqli); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="bg-white sticky-footer">
                <div class="container my-auto">
                    <div class="text-center my-auto copyright"><span>Copyright © Dept. Mathematical Sciences, 2024</span></div>
                </div>
            </footer>
        </div><a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function() {
            // AJAX form submission for adding a student
            $('#addStudentForm').on('submit', function(event) {
                event.preventDefault(); // Prevent default form submission

                // Serialize form data
                var formData = $(this).serialize();
                console.log(formData); // Debugging (optional)

                // Send AJAX request to add_student.php
                $.ajax({
                    url: 'add_student.php',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        // Handle success response
                        alert(response); // Display success message
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        console.error(error);
                        alert('Error adding student. Please try again.');
                    }
                });
            });
            $('#removeStudentForm').on('submit', function(event) {
                event.preventDefault(); // Prevent default form submission

                // Serialize form data
                var formData = $(this).serialize();
                console.log(formData); // Debugging (optional)

                // Send AJAX request to remove_student.php
                $.ajax({
                    url: 'remove_student.php',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        // Handle success response
                        alert(response); // Display success message
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        console.error(error);
                        alert('Error removing student. Please try again.');
                    }
                });
            });
            function fetchStudentDetails(roll) {
                $.ajax({
                    url: 'fetch_students.php',
                    type: 'GET',
                    data: { roll: roll },
                    success: function(response) {
                        // Parse JSON response
                        var data = JSON.parse(response);

                        // Populate student details in the modal
                        $('#rollNumber').text(data.student.roll);
                        $('#studentName').text(data.student.name);
                        $('#phoneNumber').text(data.student.phone_number);
                        $('#updatePhoneNumber').val(data.student.phone_number); // Set phone number in update form

                        // Populate CPI dropdown
                        $('#cpiDropdown').empty(); // Clear existing options
                        for (var i = 1; i <= 8; i++) {
                            var cpiValue = parseFloat(data.student['cpi_sem' + i]); 
                            if (!isNaN(cpiValue) && cpiValue > 0) {
                                $('#cpiDropdown').append('<option value="' + cpiValue.toFixed(2) + '">Sem ' + i + ': ' + cpiValue.toFixed(2) + '</option>');
                            }
                        }

                        // Populate SPI dropdown
                        $('#spiDropdown').empty(); // Clear existing options
                        for (var i = 1; i <= 8; i++) {
                            var spiValue = parseFloat(data.student['spi_sem' + i]);
                            if (!isNaN(spiValue) && spiValue > 0) {
                                $('#spiDropdown').append('<option value="' + spiValue.toFixed(2) + '">Sem ' + i + ': ' + spiValue.toFixed(2) + '</option>');
                            }
                        }
                        for (var i = 1; i <= 8; i++) {
                            // Check if cpi and spi are not 0.00
                            if (data.student['cpi_sem' + i] !== '0.00' && data.student['spi_sem' + i] !== '0.00') {
                                $('#cpi_sem' + i).val(data.student['cpi_sem' + i]);
                                $('#spi_sem' + i).val(data.student['spi_sem' + i]);
                            }
                        }
                        // Populate positions, backlog_courses, and minor_courses fields in the update popup
                        $('#updatePositions').val(data.student.positions);
                        $('#updateBacklogCourses').val(data.student.backlog_courses);
                        $('#updateMinorCourses').val(data.student.minor_courses);

                        // Display positions, backlog courses, all backlog courses and minor courses if not null in details modal
                        if (data.student.positions) {
                            $('#positionsValue').text(data.student.positions);
                            $('#positionsDetails').show();
                        } else {
                            $('#positionsDetails').hide();
                        }

                        if (data.student.backlog_courses) {
                            $('#backlogCoursesValue').text(data.student.backlog_courses);
                            $('#backlogCoursesDetails').show();
                        } else {
                            $('#backlogCoursesDetails').hide();
                        }
                        if (data.student.all_backlog_courses){
                            // Remove leading comma and add space after each comma
                            var formattedAllBacklogCourses = data.student.all_backlog_courses.replace(/^,/, '').replace(/,/g, ', ');
                            $('#allBacklogCoursesValue').text(formattedAllBacklogCourses);
                            $('#allBacklogCoursesDetails').show();
                        } else {
                            $('#allBacklogCoursesDetails').hide();
                        }
                        if (data.student.minor_courses) {
                            $('#minorCoursesValue').text(data.student.minor_courses);
                            $('#minorCoursesDetails').show();
                        } else {
                            $('#minorCoursesDetails').hide();
                        }

                        // Plot performance chart
                        plotPerformance(data.performance);

                        // Show the details modal
                        $('#detailsModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        alert('Error fetching student details. Please try again.');
                    }
                });
            }
            // Click event handler for student rows
            $(document).on('click', '.student-row', function() {
                var roll = $(this).data('roll');
                // Fetch and display student details
                fetchStudentDetails(roll);
            });

            // Click event handler for the update button in the details modal
            $('#detailsModal').on('click', '.btn-primary', function() {
                // Show the update modal
                $('#updateModal').modal('show');
                // Close the details modal
                $('#detailsModal').modal('hide');
            });

            $('#updateStudentForm').on('submit', function(event) {
                event.preventDefault(); // Prevent default form submission

                var roll = $('#rollNumber').text(); 

                // Serialize form data and add the roll number
                var formData = $(this).serialize() + '&roll=' + roll;

                // Send AJAX request to update_student.php
                $.ajax({
                    url: 'update_student.php',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        // Handle success response
                        alert(response);

                        // Refresh details modal after successful update (see Step 2 below)
                        fetchStudentDetails($('#rollNumber').text()); 

                        // Close the update modal
                        $('#updateModal').modal('hide');
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        console.error(error);
                        alert('Error updating student. Please try again.');
                    }
                });
            });
        });
        $('#addModal').on('hidden.bs.modal', function (e) {
            location.reload(); // Reload the page
        });
        // Bind handler to modal close event for Remove Modal
        $('#removeModal').on('hidden.bs.modal', function (e) {
            location.reload(); // Reload the page
        });
        $('#detailsModal').on('hidden.bs.modal', function (e) {
            // Update the table (see Step 2 below)
            updateStudentTable();
        });
        // Function to fetch student details and display in details modal
        function fetchStudentDetails(roll) {
            $.ajax({
                url: 'fetch_students.php',
                type: 'GET',
                data: { roll: roll },
                success: function(response) {
                    // Parse JSON response
                    var data = JSON.parse(response);
                    // Display student details in the details modal
                    $('#detailsModal .modal-body .details').html(data.student);
                    // Plot performance chart
                    plotPerformance(data.performance);
                    // Show the details modal
                    $('#detailsModal').modal('show');
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    alert('Error fetching student details. Please try again.');
                }
            });
        }
        let performanceChart = null; // Variable to store the chart instance

        function plotPerformance(data) {
            const semesters = data.map(item => item.semester);
            const spiValues = data.map(item => item.spi);
            const cpiValues = data.map(item => item.cpi);

            // Destroy the existing chart if it exists
            if (performanceChart) {
                performanceChart.destroy();
            }

            const ctx = document.getElementById('performanceChart').getContext('2d');
            performanceChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: semesters,
                    datasets: [
                        {
                            label: 'SPI',
                            data: spiValues,
                            borderColor: 'blue',
                            fill: false,
                            pointBackgroundColor: 'lightgreen',
                            pointBorderColor: 'blue',
                            pointRadius: 5,
                            pointHoverRadius: 10
                        },
                        {
                            label: 'CPI',
                            data: cpiValues,
                            borderColor: 'red',
                            fill: false,
                            pointBackgroundColor: 'yellow',
                            pointBorderColor: 'red',
                            pointRadius: 5,
                            pointHoverRadius: 10
                        }
                    ]
                },
                options: {
                    scales: {
                        y: {
                            suggestedMax: 10
                        }
                    }
                }
            });
        }
        function updateStudentTable() {
            // Get the roll number of the student (assuming it's stored in the details modal)
            var roll = $('#rollNumber').text();

            // Fetch updated student data from the server
            $.ajax({
                url: 'fetch_students.php', // Or a specific endpoint to fetch a single student
                type: 'GET',
                data: { roll: roll },
                success: function(response) {
                    // Parse JSON response
                    var data = JSON.parse(response);
                    // Find the table row with the matching roll number
                    var rowToUpdate = $('#dataTable tbody tr[data-roll="' + roll + '"]');
                    // Create name with position (if available) with red color
                    var nameWithPosition = data.student.name;
                    if (data.student.positions) {
                        nameWithPosition += ' (<span class="position-text">' + data.student.positions + '</span>)';
                    }
                    var cpiToUpdate=0.00;
                    if(data.performance.length!=0){
                        cpiToUpdate = data.performance[data.performance.length-1].cpi;;
                    }
                    var backlogText = data.student.backlog_courses ? '<span class="backlog-yes">Yes</span>' : 'No';
                     // Update the table cells with the new data
                    rowToUpdate.find('td:eq(1)').html(nameWithPosition);
                    rowToUpdate.find('td:eq(2)').html(backlogText);
                    rowToUpdate.find('td:eq(3)').text(data.student.phone_number); // Phone Number
                    rowToUpdate.find('td:eq(4)').text(cpiToUpdate);         // CPI
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    alert('Error fetching updated student data.');
                }
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/bs-init.js?h=e2b0d57f2c4a9b0d13919304f87f79ae"></script>
    <script src="assets/js/theme.js?h=79f403485707cf2617c5bc5a2d386bb0"></script>
</body>

</html>