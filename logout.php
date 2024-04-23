<?php
// Clear the email from localStorage
echo "<script>
        localStorage.removeItem('email');
        // Redirect to the login page
        window.location.href = 'login.php';
      </script>";
?>