<?php 
require_once("config.php");
session_start();

if(isset($_POST['login'])){
    $loginEmail = $_POST['email'];
    $loginPassword = $_POST['password'];

    $sql = "SELECT id FROM students WHERE email = :email AND password = :password";
    $checkStmt = $db->prepare($sql);
    $checkStmt->bindParam(':email', $loginEmail);
    $checkStmt->bindParam(':password', $loginPassword);
    $checkStmt->execute();
    $loginId = $checkStmt->fetchColumn();

	$sql = "SELECT name FROM students WHERE email = :email AND password = :password";
    $checkStmt = $db->prepare($sql);
    $checkStmt->bindParam(':email', $loginEmail);
    $checkStmt->bindParam(':password', $loginPassword);
    $checkStmt->execute();
    $loginName = $checkStmt->fetchColumn();

    if($checkStmt AND $loginId != ""){
		$_SESSION['User'] = $loginName;
        echo "<script>alert('Welcome $loginName!'); window.location.href = 'list.php';</script>";
	} else {
		echo "<script>alert('Invalid Credentials!'); window.location.href = 'login.php';</script>";
	}
}
?>

<!DOCTYPE html>
	<head>
		<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
		<title>CRUD</title>
		<link href="style.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
        <script>
        $(document).ready(function() {
            $('#admin_form').validate({
            rules: {
                email: {
                required: true,
                email: true
                },
                password: {
                required: true,
                minlength: 6
                }
            },
            messages: {
                email: {
                required: "Please enter your email address",
                email: "Please enter a valid email address"
                },
                password: {
                required: "Please enter a password",
                minlength: "Your password must be at least 6 characters long"
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
            });
        });
        </script>

	</head>
	<body>	
		<div class="hotel-listing">
			<div class="regi-main">
				<div id="wrapper2">
					<div class="my-account">					
						<div class="myaccount-outer ">
							<div class="account-right-div">					
								<div class="dashboard-heading">
									<h2><?php echo 'Login Page'; ?></h2>
									<ul class="breadcrumb right_bc">
										<!-- <a class="active" href="registration.php">Sign-Up</a> -->
									</ul>
								</div>
								<div class="dashboard-inner">
									<div class="main-dash-summry Edit-profile edit-dealer-prof dealer-edit">
										<form action="" method="post" name="admin_form" id="admin_form" novalidate="novalidate">
										<div class="login-column">
											<div class="input-row">
												<div class="full">
													<div class="input-block">
														<label>Email Id: <span class="star">*</span></label>
														<span class="reg_span">
                                                        <input type="text" name="email" value="" id="email" class="inputbox-main">
														</span>
													</div>
												</div>
											</div>
											<div class="input-row">
												<div class="full">
													<div class="input-block">
														<label>Password: <span class="star">*</span></label>
														<span class="reg_span">
															<input type="password" name="password" value="" id="password" class="inputbox-main">
														</span>
													</div>
												</div>
											</div>
										</div>
										<div class="submit-class">
											<div class="full">
												<input type="submit" name="login" value="Login" class="btn-submit btn"> 
											</div>	
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
                </div>
			</div>
		</div>
	</body>
</html>
