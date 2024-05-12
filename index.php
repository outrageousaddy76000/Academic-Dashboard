<?php
// Check if the email is set in localStorage
echo "<script>
        var email = localStorage.getItem('email');
        if (!email) {
            // Redirect to the login page
            window.location.href = 'login.php';
        }
      </script>";

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

$current_date = date('Y-m-d');
$current_month = date('n', strtotime($current_date));

$year_start_month = 8; // August
$year_end_month = 5;
$roll;
if ($current_month < $year_start_month) {
    // If the current month is before August, then first-year students will have the current year - 1 as the starting roll number
    $roll =  date('Y') - 1;
} else {
    $roll = date('Y');
}
$roll = $roll % 100;
$improvement_threshold=0.1;
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
// Execute SQL query
if ($mysqli->query($sql) === TRUE) {
    echo "Table created successfully";
} else {
    echo "Error creating table: " . $mysqli->error;
}
//go through all students
$sql = "SELECT * FROM students";
$result = $mysqli->query($sql);
//create an array for excellent, good, average, need improvement, backlog, and minor students
$excellent = array(0, 0, 0, 0);
$good = array(0, 0, 0, 0);
$average = array(0, 0, 0, 0);
$need_improvement = array(0, 0, 0, 0);
$backlog = array(0, 0, 0, 0);
$minor = array(0, 0, 0, 0);
$improving=0;
$not_improving=0;
$stable=0;

while ($row = $result->fetch_assoc()) {
    //get the student's roll number using first 2 digits of roll
    $rollstudent = (int)substr($row['roll'], 0, 2);
    //find the students's year using rollstudent and roll, this gives the index of the array
    $year = $roll-$rollstudent;
    //check if the student has any backlogs using the following table information
    if ($row['backlog_courses'] != NULL) {
        $backlog[$year]++;
    }
    //check if the student has any minor courses using the following table information
    if ($row['minor_courses'] != NULL) {
        $minor[$year]++;
    }
    //calculate the last cpi of the student >0
    $cpi = 0;
    $index=-1;
    for ($i = 8; $i > 0; $i--) {
        if ($row['cpi_sem' . $i] > 0) {
            $cpi = $row['cpi_sem' . $i];
            $index=$i;
            break;
        }
    }
    if($cpi==0){
        continue;
    }
    // If only CPI sem1 is greater than 0, put in stable category
    if ($row['cpi_sem2'] == 0) {
        $stable++;
    } else {
        // Calculate improvement from last two CPIs,i.e., cpi sem(index) and cpi sem(index-1)
        $improvement = $row['cpi_sem' . $index] - $row['cpi_sem' . ($index - 1)];
        // Check if improvement meets threshold criteria
        if ($improvement > $improvement_threshold) {
            $improving++;
        } elseif ($improvement < (-1 * $improvement_threshold)) {
            $not_improving++;
        } else {
            $stable++;
        }
    }
    //if cpi greater than 9, excellent
    if ($cpi >= 9) {
        $excellent[$year]++;
    } else if ($cpi >= 8) {
        $good[$year]++;
    } else if ($cpi >= 7) {
        $average[$year]++;
    } else{
        $need_improvement[$year]++;
    }
}
?>
<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Dashboard</title>
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
                    <li class="nav-item"><a class="nav-link active" href="index.php"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="courses.php"><i class="fas fa-book-reader"></i><span>Courses</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="students.php"><i class="fas fa-users"></i><span>Students</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="remind.php"><i class="fas fa-hourglass-start"></i><span>Remind</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a></li>
                </ul>
                <div class="text-center d-none d-md-inline"><button class="btn rounded-circle border-0" id="sidebarToggle" type="button"></button></div>
            </div>
        </nav>
        <div class="d-flex flex-column" id="content-wrapper">
            <div id="content">
                <div class="container-fluid" style="margin-top: 10px;">
                    <div class="d-sm-flex justify-content-between align-items-center mb-4">
                        <h3 class="text-dark mb-0">Dashboard</h3>
                    </div><!-- Start: Chart -->
                    <div class="row">
                        <div class="col-lg-12 col-xl-12">
                            <div class="card shadow mb-4">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="text-primary fw-bold m-0">Performance at a Glance</h6>
                                </div>
                                <div class="card-body">
                                    <div class="chart-area"><canvas data-bss-chart="{&quot;type&quot;:&quot;doughnut&quot;,&quot;data&quot;:{&quot;labels&quot;:[&quot;Improving&quot;,&quot;Not Improving&quot;,&quot;Stable&quot;],&quot;datasets&quot;:[{&quot;label&quot;:&quot;&quot;,&quot;backgroundColor&quot;:[&quot;#4e73df&quot;,&quot;#1cc88a&quot;,&quot;#36b9cc&quot;],&quot;borderColor&quot;:[&quot;#ffffff&quot;,&quot;#ffffff&quot;,&quot;#ffffff&quot;],&quot;data&quot;:[&quot;<?php echo $improving?>&quot;,&quot;<?php echo $not_improving?>&quot;,&quot;<?php echo $stable?>&quot;]}]},&quot;options&quot;:{&quot;maintainAspectRatio&quot;:false,&quot;legend&quot;:{&quot;display&quot;:false,&quot;labels&quot;:{&quot;fontStyle&quot;:&quot;normal&quot;}},&quot;title&quot;:{&quot;fontStyle&quot;:&quot;normal&quot;}}}"></canvas></div>
                                    <div class="text-center small mt-4"><span class="me-2"><i class="fas fa-circle text-primary"></i>&nbsp;Improving</span><span class="me-2"><i class="fas fa-circle text-success"></i>&nbsp;Not Improving</span><span class="me-2"><i class="fas fa-circle text-info"></i>&nbsp;Stable</span></div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End: Chart -->
                    <div class="row">
                        <div class="col">
                            <div class="row">
                                <div class="col-lg-6 mb-4">
                                    <div class="card text-white bg-primary shadow">
                                        <div class="card-body">
                                            <p class="m-0">Excellent (CPI >= 9)</p>
                                            <p class="text-white-50 small m-0">1st Year: <?php echo $excellent[0]?> , 2nd Year: <?php echo $excellent[1]?> , 3rd Year: <?php echo $excellent[2]?>, 4th Year:  <?php echo $excellent[3]?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="card text-white bg-success shadow">
                                        <div class="card-body">
                                            <p class="m-0">Good (CPI >= 8 and CPI < 9)</p>
                                            <p class="text-white-50 small m-0">1st Year: <?php echo $good[0]?> , 2nd Year: <?php echo $good[1]?> , 3rd Year: <?php echo $good[2]?>, 4th Year:  <?php echo $good[3]?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="card text-white bg-info shadow">
                                        <div class="card-body">
                                            <p class="m-0">Average (CPI >= 7 and CPI < 8) </p>
                                            <p class="text-white-50 small m-0">1st Year: <?php echo $average[0]?> , 2nd Year: <?php echo $average[1]?> , 3rd Year: <?php echo $average[2]?>, 4th Year:  <?php echo $average[3]?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="card text-white bg-warning shadow">
                                        <div class="card-body">
                                            <p class="m-0">Need Improvement (CPI < 7)</p>
                                            <p class="text-white-50 small m-0">1st Year: <?php echo $need_improvement[0]?> , 2nd Year: <?php echo $need_improvement[1]?> , 3rd Year: <?php echo $need_improvement[2]?>, 4th Year:  <?php echo $need_improvement[3]?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="card text-white bg-danger shadow">
                                        <div class="card-body">
                                            <p class="m-0">Backlog Students</p>
                                            <p class="text-white-50 small m-0">1st Year: <?php echo $backlog[0]?> , 2nd Year: <?php echo $backlog[1]?> , 3rd Year: <?php echo $backlog[2]?>, 4th Year:  <?php echo $backlog[3]?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="card text-white bg-secondary shadow">
                                        <div class="card-body">
                                            <p class="m-0">Minor Students</p>
                                            <p class="text-white-50 small m-0">1st Year: <?php echo $minor[0]?> , 2nd Year: <?php echo $minor[1]?> , 3rd Year: <?php echo $minor[2]?>, 4th Year:  <?php echo $minor[3]?></p>
                                        </div>
                                    </div>
                                </div>
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
        </div>
        <a class="border rounded d-inline scroll-to-top" href="#page-top" style="text-align: center;"><i class="fas fa-angle-up"></i></a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.min.js"></script>
    <script src="assets/js/bs-init.js?h=e2b0d57f2c4a9b0d13919304f87f79ae"></script>
    <script src="assets/js/theme.js?h=79f403485707cf2617c5bc5a2d386bb0"></script>
</body>

</html>