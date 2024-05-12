<?php
//Include config file
include 'config.php';

//Access database configuration
$host = $dbConfig['host'];
$username = $dbConfig['username'];
$password = $dbConfig['password'];
$database = $dbConfig['database'];

//MySQLi connection
$mysqli = new mysqli($host, $username, $password, $database);

//Check connection
if ($mysqli->connect_errno) {
    die("Connection failed: " . $mysqli->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //Get the email and password from the form submission
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    //Query for the user with the given email
    $query = "SELECT * FROM users WHERE email='$email'";
    $result = $mysqli->query($query);

    //Check if the query was successful
    if ($result) {
        //Check if the user exists
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            //Check if the password matches
            if ($password == $user['password']) {
                //Start session and redirect to the main page
                if ($password == $user['password']) {
                    //Start session and set user data in localStorage
                    echo "<script>
                            localStorage.setItem('email', '" . $email . "');
                            window.location.href = 'index.php';
                          </script>";
                    exit();
                }
            } else {
                //Display an alert if the password doesn't match
                $alert_message = 'Wrong password. Please try again.';
            }
        } else {
            //Display an alert if the user doesn't exist
            $alert_message = 'User does not exist. Please add user to the database.';
        }
    } else {
        //Handle query error
        echo "Error: " . $mysqli->error;
    }

    //Close the result set (if not already closed)
    if (isset($result)) {
        $result->free();
    }

    //Include login page with alert message if necessary
    include('login.php');
}
?>
