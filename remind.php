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

?>
<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Remind</title>
    <link rel="icon" type="image/png" sizes="697x768" href="assets/img/RGIPT%20Logo.png?h=da992af9ac2e0a09cf93cc62adbb5ae4">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css?h=97380e22c8933e9aa79cbc2390b9f15a">
    <link rel="stylesheet" href="assets/css/Nunito.css?h=af3d911350614f13e63e169d51c66bd1">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.0/css/all.css">
    <link rel="stylesheet" href="assets/css/Filter.css?h=4467af38b8c3b27bbeb3b739717b0c06">
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
                    <li class="nav-item"><a class="nav-link" href="index.php"><span><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="courses.php"><i class="fas fa-book-reader"></i><span>Courses</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="students.php"><i class="fas fa-users"></i><span>Students</span></a></li>
                    <li class="nav-item"><a class="nav-link active" href="remind.php"><i class="fas fa-hourglass-start"></i><span>Remind</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a></li>
                </ul>
                <div class="text-center d-none d-md-inline"><button class="btn rounded-circle border-0" id="sidebarToggle" type="button"></button></div>
            </div>
        </nav>
        <div class="d-flex flex-column" id="content-wrapper">
            <div class="container-fluid" style="margin-top: 10px; min-height: calc(100vh - 60px);">
                <div class="d-sm-flex justify-content-between align-items-center mb-4">
                    <h3 class="text-dark mb-0">Remind Students</h3>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header bg-danger text-white text-center">
                                <h5 class="mb-0">Remind for Backlogs</h5>
                            </div>
                            <div class="card-body text-center">
                                <button class="btn btn-danger btn-lg btn-block text-white mb-3" id="backlogsAutomaticBtn">Remind all eligible backlog students automatically</button>
                                <button class="btn btn-danger btn-lg btn-block text-white" data-bs-toggle="modal" data-bs-target="#backlogsModal">Custom mail to all backlog students</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header bg-success text-white text-center">
                                <h5 class="mb-0">Remind for Minor</h5>
                            </div>
                            <div class="card-body text-center">
                                <button class="btn btn-success btn-lg btn-block text-white mb-3" id="minorAutomaticBtn">Remind all eligible minor students automatically</button>
                                <button class="btn btn-success btn-lg btn-block text-white" data-bs-toggle="modal" data-bs-target="#minorModal">Custom mail to all minor students</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Backlogs Modal -->
                <div class="modal fade" id="backlogsModal" tabindex="-1" aria-labelledby="backlogsModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title" id="backlogsModalLabel">Enter your message</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <textarea class="form-control" rows="5" id="backlogsMessage" placeholder="Enter your message"></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" id="backlogsSubmitBtn">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Loading spinner modal -->
                <div class="modal" id="loadingModal" tabindex="-1" aria-labelledby="loadingModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body text-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2">Sending...</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Minor Modal -->
                <div class="modal fade" id="minorModal" tabindex="-1" aria-labelledby="minorModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-success text-white">
                                <h5 class="modal-title" id="minorModalLabel">Enter your message</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <textarea class="form-control" rows="5" id="minorMessage" placeholder="Enter your message"></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-success" id="minorSubmitBtn">Submit</button>
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
    <script>
        // Function to show loading modal
        function showLoadingModal() {
            $('#loadingModal').modal('show');
        }

        // Function to hide loading modal
        function hideLoadingModal() {
            $('#loadingModal').modal('hide');
        }

        // Function to send AJAX request to remind_mail.php
        function sendRemindRequest(message, type) {
            // Show loading modal
            showLoadingModal();

            // AJAX request
            $.ajax({
                url: 'remind_mail.php',
                method: 'POST',
                data: {
                    message: message,
                    type: type
                },
                success: function(response) {
                    // Hide loading modal
                    hideLoadingModal();

                    alert(response);
                },
                error: function(xhr, status, error) {
                    // Hide loading modal
                    hideLoadingModal();

                    // Handle error
                    alert('Error: ' + error);
                }
            });
        }

        // Event listener for Remind all eligible backlog students automatically button
        $('#backlogsAutomaticBtn').click(function() {
            sendRemindRequest('auto', 'backlogs');
        });

        // Event listener for Custom mail to all backlog students button
        $('#backlogsSubmitBtn').click(function() {
            var message = $('#backlogsModal textarea').val();
            sendRemindRequest(message, 'backlogs');
        });

        // Event listener for Remind all eligible minor students automatically button
        $('#minorAutomaticBtn').click(function() {
            sendRemindRequest('auto', 'minor');
        });

        // Event listener for Custom mail to all minor students button
        $('#minorSubmitBtn').click(function() {
            var message = $('#minorModal textarea').val();
            sendRemindRequest(message, 'minor');
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/bs-init.js?h=e2b0d57f2c4a9b0d13919304f87f79ae"></script>
    <script src="assets/js/theme.js?h=79f403485707cf2617c5bc5a2d386bb0"></script>
</body>

</html>