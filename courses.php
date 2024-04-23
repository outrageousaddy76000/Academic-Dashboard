<?php
// Check if the email is set in localStorage
echo "<script>
        var email = localStorage.getItem('email');
        if (!email) {
            // Redirect to the login page
            window.location.href = 'login.php';
        }
      </script>";
?>
<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Courses</title>
    <link rel="icon" type="image/png" sizes="697x768" href="assets/img/RGIPT%20Logo.png?h=da992af9ac2e0a09cf93cc62adbb5ae4">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css?h=97380e22c8933e9aa79cbc2390b9f15a">
    <link rel="stylesheet" href="assets/css/Nunito.css?h=af3d911350614f13e63e169d51c66bd1">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.0/css/all.css">
    <link rel="stylesheet" href="assets/css/Filter.css?h=4467af38b8c3b27bbeb3b739717b0c06">
    <style>
        th,td,tr{
            border-width: 1px;
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
                    <li class="nav-item"><a class="nav-link active" href="courses.php"><i class="fas fa-book-reader"></i><span>Courses</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="students.php"><i class="fas fa-users"></i><span>Students</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="remind.php"><i class="fas fa-hourglass-start"></i><span>Remind</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a></li>
                </ul>
                <div class="text-center d-none d-md-inline"><button class="btn rounded-circle border-0" id="sidebarToggle" type="button"></button></div>
            </div>
        </nav>
        <div class="d-flex flex-column" id="content-wrapper">
            <div id="content">
                <div class="container">
                <div class="row justify-content-center align-items-center" style="margin-top: 10px;">
                    <div class="col-md-4 col-sm-6 mb-2">
                        <div class="dropdown">
                            <button class="btn btn-primary btn-lg dropdown-toggle" style="width: 100%;" type="button" id="yearDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Select Year
                            </button>
                            <div class="dropdown-menu" aria-labelledby="yearDropdown">
                                <a class="dropdown-item text-center" href="#" onclick="selectYear(1)"><span class="font-weight-bold">1<sup>st</sup></span></a>
                                <a class="dropdown-item text-center" href="#" onclick="selectYear(2)"><span class="font-weight-bold">2<sup>nd</sup></span></a>
                                <a class="dropdown-item text-center" href="#" onclick="selectYear(3)"><span class="font-weight-bold">3<sup>rd</sup></span></a>
                                <a class="dropdown-item text-center" href="#" onclick="selectYear(4)"><span class="font-weight-bold">4<sup>th</sup></span></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 mb-2">
                        <div class="dropdown" id="semesterDropdown">
                            <button class="btn btn-primary btn-lg dropdown-toggle" style="width: 100%;" type="button" id="semesterDropdownButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" disabled>
                                Select Semester
                            </button>
                            <div class="dropdown-menu text-center" aria-labelledby="semesterDropdownButton">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 mb-2">
                        <div class="dropdown">
                            <button class="btn btn-primary btn-lg dropdown-toggle" style="width: 100%;" type="button" id="modifyDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Modify
                            </button>
                            <div class="dropdown-menu" aria-labelledby="modifyDropdown">
                                <a class="dropdown-item text-center" href="#" onclick="openAddCoursesPopup()">Add Courses</a>
                                <a class="dropdown-item text-center" href="#" onclick="openRemoveCoursesPopup()">Remove Courses</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Popup HTML -->
                <div id="popup" class="modal fade" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Courses Actions</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Content of your popup goes here -->
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="container d-flex justify-content-center align-items-center" style="min-height: calc(100vh - 50px);">
                        <div id="contentDiv">
                            <!-- Content will be dynamically updated here based on selection -->
                            <p class="display-2" id="dynamicContent">Select Year and Semester to See and Modify Courses</p>
                        </div>
                    </div>
                </div>

                <!-- Ensure full version of jQuery is included -->
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <!-- Include Bootstrap JavaScript -->
                <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
                <script>
                // Define global variables to keep track of selected year and semester
                    let selectedYear = null;
                    let selectedSemester = null;
                    function updateCourses() {
                        // Make AJAX request to get course data
                        $.ajax({
                            url: 'fetch_courses.php',
                            type: 'POST',
                            data: {
                                year: selectedYear,
                                semester: selectedSemester
                            },
                            success: function(response) {
                                // Update the dynamic content with the fetched courses
                                $('#dynamicContent').html(response);
                            },
                            error: function(xhr, status, error) {
                                console.error(error);
                                // Handle error
                            }
                        });
                    }

                    function selectYear(year) {
                        // Update the year dropdown button text
                        $('#yearDropdown').text('Year ' + year);

                        // Store the selected year
                        selectedYear = year;

                        // Enable semester dropdown
                        $('#semesterDropdownButton').removeAttr('disabled');

                        // Clear previous options
                        $('#semesterDropdown .dropdown-menu').empty();

                        // Populate options for semester
                        $('#semesterDropdown .dropdown-menu').append(`<a class="dropdown-item" href="#" onclick="selectSemester(${year}, 'odd')">Odd Semester</a>`);
                        $('#semesterDropdown .dropdown-menu').append(`<a class="dropdown-item" href="#" onclick="selectSemester(${year}, 'even')">Even Semester</a>`);

                        // If semester is already selected, update content
                        if (selectedSemester) {
                            selectSemester(selectedYear, selectedSemester);
                        }
                    }

                    function selectSemester(year, semester) {
                        // Update the semester dropdown button text
                        $('#semesterDropdownButton').text(semester.charAt(0).toUpperCase() + semester.slice(1));

                        // Store the selected semester
                        selectedSemester = semester;

                        // Update courses
                        updateCourses();
                    }
                    
                    $(document).ready(function() {
                        // Attach form submission event listeners when the document is ready
                        $(document).on('submit', '#addCourseForm', function(event) {
                            event.preventDefault(); // Prevent default form submission

                            // Serialize form data
                            var formData = $(this).serialize();

                            // Send AJAX request to add_course.php
                            $.ajax({
                                url: 'add_course.php',
                                type: 'POST',
                                data: formData,
                                success: function(response) {
                                    alert(response);
                                },
                                error: function(xhr, status, error) {
                                    alert('Error adding course. Please try again.');
                                }
                            });
                        });

                        $(document).on('submit', '#removeCourseForm', function(event) {
                            event.preventDefault(); // Prevent default form submission
                            // Serialize form data
                            var formData = $(this).serialize();
                            // Send AJAX request to remove_course.php
                            $.ajax({
                                url: 'remove_course.php',
                                type: 'POST',
                                data: formData,
                                success: function(response) {
                                    // Check if the course was successfully removed
                                    if (response.trim() === "Course removed successfully") {
                                        // Handle success response
                                        alert(response); // Display success message
                                    } else {
                                        // Handle if the course removal was not successful
                                        alert(response); // Display error message
                                    }
                                },
                                error: function(xhr, status, error) {
                                    // Handle error response
                                    console.error(error);
                                    alert('Error removing course. Please try again.');
                                }
                            });
                        });
                    });

                    function openAddCoursesPopup() {
                        // Set modal title
                        document.querySelector('#popup .modal-title').innerText = "Add Courses";
                        // Create the form
                        let formContent = `
                            <form id="addCourseForm">
                                <div class="mb-3">
                                    <label for="courseCode" class="form-label">Course Code</label>
                                    <input type="text" class="form-control" id="courseCode" name="courseCode" required>
                                </div>
                                <div class="mb-3">
                                    <label for="courseName" class="form-label">Course Name</label>
                                    <input type="text" class="form-control" id="courseName" name="courseName" required>
                                </div>
                                <div class="mb-3">
                                    <label for="semester" class="form-label">Semester</label>
                                    <select class="form-select" id="semester" name="semester" required>
                                        <option value="">Select Semester</option>
                                        <option value="Odd">Odd</option>
                                        <option value="Even">Even</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="deptName" class="form-label">Department Name</label>
                                    <input type="text" class="form-control" id="deptName" name="deptName" required>
                                </div>
                                <div class="mb-3">
                                    <label for="courseType" class="form-label">Course Type</label>
                                    <input type="text" class="form-control" id="courseType" name="courseType" required>
                                </div>
                                <div class="mb-3">
                                    <label for="credit" class="form-label">Credit</label>
                                    <input type="number" class="form-control" id="credit" name="credit" required>
                                </div>
                                <div class="mb-3">
                                    <label for="creditStructure" class="form-label">Credit Structure</label>
                                    <input type="text" class="form-control" id="creditStructure" name="creditStructure" required>
                                </div>
                                <div class="mb-3">
                                    <label for="year" class="form-label">Year</label>
                                    <input type="number" class="form-control" id="year" name="year" required>
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="elective" name="elective">
                                    <label class="form-check-label" for="elective">Elective</label>
                                </div>
                                <button type="submit" class="btn btn-primary">Add Course</button>
                            </form>
                        `;
                        // Set the form content in the modal body
                        document.getElementById('popup').querySelector('.modal-body').innerHTML = formContent;
                        $('#popup').modal('show');
                    }

                    function openRemoveCoursesPopup() {
                        // Set modal title
                        document.querySelector('#popup .modal-title').innerText = "Remove Courses";
                        // Create the form
                        let formContent = `
                            <form id="removeCourseForm">
                                <div class="mb-3">
                                    <label for="courseCodeToRemove" class="form-label">Course Code to Remove</label>
                                    <input type="text" class="form-control" id="courseCodeToRemove" name="courseCodeToRemove" required>
                                </div>
                                <button type="submit" class="btn btn-danger">Remove Course</button>
                            </form>
                        `;
                        // Set the form content in the modal body
                        document.getElementById('popup').querySelector('.modal-body').innerHTML = formContent;
                        $('#popup').modal('show');
                    }
                    // Close button event listener
                    document.querySelector('#popup .btn-close').addEventListener('click', function() { // Changed '.close' to '.btn-close'
                        $('#popup').modal('hide');
                        if(selectSemester && selectedYear) {
                            updateCourses();
                        }
                    });

                    // Bind handler to modal close event
                    $('#popup').on('hidden.bs.modal', function (e) {
                        // Call updateCourses function when the modal is closed
                        if(selectSemester && selectedYear) {
                            updateCourses();
                        }
                    });

                </script>
            </div>
            <footer class="bg-white sticky-footer">
                <div class="container my-auto">
                    <div class="text-center my-auto copyright"><span>Copyright © Dept. Mathematical Sciences, 2024</span></div>
                </div>
            </footer>
        </div><a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/bs-init.js?h=e2b0d57f2c4a9b0d13919304f87f79ae"></script>
    <script src="assets/js/theme.js?h=79f403485707cf2617c5bc5a2d386bb0"></script>
</body>

</html>