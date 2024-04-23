<?php
// Include config files
include 'config.php';
require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Email configuration
$mailhost = $mailConfig['host'];
$mailusername = $mailConfig['username'];
$mailpassword = $mailConfig['password'];

// Database configuration
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

// Calculate semester based on current month
$current_month = date('n');

// Academic year start and end months
$year_start_month = 8; // August
$year_end_month = 5;   // May
$semester = ($current_month >= $year_start_month) ? "Odd" : "Even";

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if message and type are set in the POST data
    if (isset($_POST['message']) && isset($_POST['type'])) {
        // Retrieve message and type from POST data
        $message = $_POST['message'];
        $type = $_POST['type'];
        
        // Perform actions based on the type
        if ($type === 'backlogs') {
            if ($message === 'auto') {
                // Logic to send automatic reminder emails to students with backlogs
                $subject = "Registration for backlog courses in $semester Semester";
                sendEmails($subject, $message);
            } else {
                // Logic to send custom reminder emails to students with backlogs
                // Sanitize and validate $message if needed
                $subject = "Regarding Backlogs";
                sendEmails($subject, $message);
            }
        } elseif ($type === 'minor') {
            if ($message === 'auto') {
                // Logic to send automatic reminder emails to students with minors
                $subject = "Registration for minor courses in $semester Semester";
                sendEmails($subject, $message);
            } else {
                // Logic to send custom reminder emails to students with minors
                // Sanitize and validate $message if needed
                $subject = "Regarding Minors";
                sendEmails($subject, $message);
            }
        } else {
            // Invalid type
            echo "Invalid request type";
        }
    } else {
        // Message or type not provided
        echo "Message or type not provided";
    }
} else {
    // Only POST requests are allowed
    echo "Invalid request method";
}

// Function to send emails using PHPMailer
function sendEmails($subject, $message) {
    global $mailhost, $mailusername, $mailpassword, $mysqli, $semester;

    $mail = new PHPMailer(true); // Create a new PHPMailer instance
    
    try {
        // Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF;       // Enable verbose debug output
        $mail->isSMTP();                          // Send using SMTP
        $mail->Host       = $mailhost;            // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                 // Enable SMTP authentication
        $mail->Username   = $mailusername;        // SMTP username
        $mail->Password   = $mailpassword;        // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
        $mail->Port       = 587;                  // TCP port to connect to

        //Recipients
        $mail->setFrom($mailusername, 'Dept. Mathematical Sciences'); // Add a sender
        $mail->addReplyTo($mailusername, 'Dept. Mathematical Sciences');

        // Fetch students with backlog courses from the database
        $result = $mysqli->query("SELECT roll, backlog_courses FROM students WHERE backlog_courses IS NOT NULL");
        if ($result->num_rows > 0) {
            // Loop through each student
            while ($row = $result->fetch_assoc()) {
                $roll = $row['roll'];
                $backlog_courses = explode(',', $row['backlog_courses']);
                $course_list = [];
                foreach ($backlog_courses as $course) {
                    $course = trim($course); // Trim whitespace

                    // Fetch semester information for the backlog course
                    $course_info = $mysqli->query("SELECT course_name,year,credit,credit_structure FROM courses WHERE course_code = '$course' AND semester = '$semester'");
                    if ($course_info->num_rows > 0) {
                        $course_row = $course_info->fetch_assoc();
                        $course_name = $course_row['course_name'];
                        $year = $course_row['year'];
                        $credit = $course_row['credit'];
                        $credit_structure = $course_row['credit_structure'];
                        $course_list[] = "Course Code: $course\nCourse Name: $course_name\nCredit: $credit\nCredit Structure: $credit_structure\nSemester: $semester";
                        
                    }
                }
                if (!empty($course_list)) {
                    // Derive email from roll number
                    $u_email = strtolower($roll) . '@rgipt.ac.in';
                    error_log($u_email);
                    $body = '';
                    // Prepare email body
                    if ($message === 'auto') {
                        $student_info = $mysqli->query("SELECT name FROM students WHERE roll = '$roll'");
                        $student_row = $student_info->fetch_assoc();
                        $name = $student_row['name'];
                        $body = "Dear $name,\n\nYou can register in the following courses in $semester Semester. Please go through the courses and reach out to us as soon as possible.\n\n" . implode("\n\n", $course_list);
                    } else {
                        $body = $message;
                    }

                    // Send the email
                    $mail->addAddress($u_email); // Add recipient
                    $mail->isHTML(false);         // Set email format to plain text
                    $mail->Subject = $subject;    // Set email subject
                    $mail->Body    = $body;       // Set email body
                    $mail->send();
                    // Clear all addresses and attachments for next iteration
                    $mail->clearAddresses();
                }
            }
        }

        echo 'Emails sent successfully';
    } catch (Exception $e) {
        echo "Emails could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

// Close database connection
$mysqli->close();
?>
