<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function

$u_email = $_POST['email'];

// Include config file
include 'config.php';
$mailhost = $mailConfig['host'];
$mailusername = $mailConfig['username'];
$mailpassword = $mailConfig['password'];

$host = $dbConfig['host'];
$username = $dbConfig['username'];
$password = $dbConfig['password'];
$database = $dbConfig['database'];

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

// Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    // Check if the email exists in the users table
    $conn = new mysqli($host, $username, $password, $database);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM users WHERE email = '$u_email'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // Email exists, configure and send the mail
        // Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = $mailhost;                    //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = $mailusername;                     //SMTP username
        $mail->Password   = $mailpassword;                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
        $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        // Fetch user data
        $user1 = $result->fetch_assoc();
        $password1 = $user1['password'];

        //Recipients
        $mail->setFrom($mailusername, 'Dept. Mathematical Sciences'); // Add a recipient
        $mail->addAddress($u_email); // Name is optional
        $mail->addReplyTo($mailusername, 'Dept. Mathematical Sciences');

        //Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'Password for Academic Dashboard';
        $mail->Body    = 'Your password to access academic dashboard is: ' . $password1;
        $mail->AltBody = 'Your password to access academic dashboard is: ' . $password1;

        // Send the mail
        $mail->send();
        $alert_message = 'Your password has been sent to your email address. Please check your inbox.';
        include 'login.php';
    } else {
        // Email does not exist, include forgotten-password.php
        $alert_message = 'User does not exist';
        include 'login.php';
    }

    $conn->close(); // Close the database connection
} catch (Exception $e) {
    $alert_message = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    include 'forgotten-password.php';
}
?>
