<?php
// Check if the email is set in localStorage
echo "<script>
        var email = localStorage.getItem('email');
        if (email) {
            // Redirect to the login page
            window.location.href = 'index.php';
        }
      </script>";
?>
<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Login</title>
    <link rel="icon" type="image/png" sizes="697x768" href="assets/img/RGIPT%20Logo.png?h=da992af9ac2e0a09cf93cc62adbb5ae4">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css?h=97380e22c8933e9aa79cbc2390b9f15a">
    <link rel="stylesheet" href="assets/css/Nunito.css?h=af3d911350614f13e63e169d51c66bd1">
    <link rel="stylesheet" href="assets/css/Filter.css?h=4467af38b8c3b27bbeb3b739717b0c06">
</head>

<body class="bg-gradient-primary">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9 col-lg-12 col-xl-10">
                <div class="card shadow-lg o-hidden border-0 my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-flex">
                                <div class="flex-grow-1 bg-login-image" style="background: url(&quot;assets/img/RGIPT%20Logo.png?h=da992af9ac2e0a09cf93cc62adbb5ae4&quot;) center / contain no-repeat;"></div>
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h4 class="text-dark mb-4">Welcome Back!</h4>
                                    </div>
                                    <form class="user" method="post" action="checklogin.php">
                                        <div class="mb-3"><input class="form-control form-control-user" type="email" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Enter Email Address..." name="email"></div>
                                        <div class="mb-3"> <input class="form-control form-control-user" type="password" id="exampleInputPassword" placeholder="Password" name="password"></div>
                                        <button class="btn btn-primary d-block btn-user w-100" type="submit">Login</button>
                                    </form>
                                    <div class="text-center"><a class="small" href="forgot-password.php">Forgot Password?</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/bs-init.js?h=e2b0d57f2c4a9b0d13919304f87f79ae"></script>
    <script src="assets/js/theme.js?h=79f403485707cf2617c5bc5a2d386bb0"></script>
    <script>
		var alertMessage = "<?php echo $alert_message ?>";
		if (alertMessage !== '') {
			alert(alertMessage);
		}
        alertMessage ="";
	</script>
</body>

</html>