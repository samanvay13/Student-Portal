<?php
require_once('config.php');
session_start();

$name = $email = $gender = $password = $editId = "";
$nameErr = $emailErr = $genderErr = $passwordErr = "";

if(isset($_SESSION['User'])){
    if (isset($_GET['edit_id'])) {
        $editId = $_GET['edit_id'];
        $editSql = "SELECT * FROM students WHERE id = :id";
        $editStmt = $db->prepare($editSql);
        $editStmt->bindParam(':id', $editId);
        $editStmt->execute();
        $editRow = $editStmt->fetch(PDO::FETCH_ASSOC);
    
        $name = $editRow['name'];
        $email = $editRow['email'];
        $gender = $editRow['gender'];
        $password = $editRow['password'];
    }
    
    elseif(isset($_POST["create"])){
        $name = $_POST["name"];
        $email = $_POST["email"];
        $gender = $_POST["gender"];
        $password = $_POST["password"];
        // die('here'.var_dump($_POST['tocheckforedit']));
       
        if (isset($_POST['tocheckforedit']) && $_POST['tocheckforedit'] != "") {
            $editId = $_POST['tocheckforedit'];
    
            $sql = "SELECT EXISTS(SELECT 1 FROM students WHERE email = :email AND id != :id) AS email_exists";
            $checkStmt = $db->prepare($sql);
            $checkStmt->bindParam(':email', $email);
            $checkStmt->bindParam(':id', $editId);
            $checkStmt->execute();
            $result = $checkStmt->fetchColumn();
    
            if ($result > 0) {
                echo "<script>alert('E-mail already exists!'); window.location.href = 'list.php';</script>";
            } else {
                $updateSql = "UPDATE students SET name = :name, email = :email, gender = :gender, password = :password WHERE id = :id";
                $updateStmt = $db->prepare($updateSql);
                $updateStmt->bindParam(':name', $name);
                $updateStmt->bindParam(':email', $email);
                $updateStmt->bindParam(':gender', $gender);
                $updateStmt->bindParam(':password', $password);
                $updateStmt->bindParam(':id', $editId);
                $result = $updateStmt->execute();
    
                if ($result) {
                    echo "<script>alert('Record saved successfully!'); window.location.href = 'list.php';</script>";
                } else {
                    echo "<script>alert('There were errors saving the data!');</script>";
                }
            }
        } else {
            $sql = "SELECT EXISTS(SELECT 1 FROM students WHERE email = :email) AS email_exists";
            $checkStmt = $db->prepare($sql);
            $checkStmt->bindParam(':email', $email);
            $checkStmt->execute();
            $result = $checkStmt->fetchColumn();
    
            if ($result > 0) {
                echo "<script>alert('E-mail already exists!'); window.location.href = 'list.php';</script>";
            } else {
                $sql = "INSERT INTO students (name, email, gender, password) VALUES (:name, :email, :gender, :password)";
                $stmntinsert = $db->prepare($sql);
                $stmntinsert->bindParam(':name', $name);
                $stmntinsert->bindParam(':email', $email);
                $stmntinsert->bindParam(':gender', $gender);
                $stmntinsert->bindParam(':password', $password);
                $result = $stmntinsert->execute();
    
                if ($result) {
                    echo "<script>alert('Record saved successfully!'); window.location.href = 'list.php';</script>";
                } else {
                    echo "<script>alert('There were errors saving the data!');</script>";
                }
            }
        }     
    }
} else {
    header('location:login.php');
}

// echo "<pre>";
// print_r($_POST);
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
                name: {
                required: true,
                minlength: 2
                },
                gender: {
                required: true
                },
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
                name: {
                required: "Please enter your name"
                },
                gender: {
                required: "Please select your gender"
                },
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
									<h2><?php echo isset($_GET['edit_id']) ? 'Edit Student' : 'Add Student'; ?></h2>
									<ul class="breadcrumb right_bc">
										<a class="active" href="list.php">Back to Listing</a>
									</ul>
								</div>
								<div class="dashboard-inner">
									<div class="main-dash-summry Edit-profile edit-dealer-prof dealer-edit">
										<form action="registration.php" method="post" name="admin_form" id="admin_form" novalidate="novalidate">
                                            <input type="hidden" name="tocheckforedit" value="<?php echo $editId; ?>">
										<div class="left-column">
											<div class="input-row">
												<div class="full">
													<div class="input-block">
														<label>Name: <span class="star">*</span></label>
														<span class="reg_span">
                                                            <input type="text" name="name" value="<?php echo $name; ?>" id="name" class="inputbox-main">
														</span>
													</div>
												</div>
											</div>
											<div class="input-row ">
												<div class="full">
													<div class="input-block">
														<label>Gender: <span class="star">*</span></label>
														<span class="reg_span">
                                                            <select name="gender" id="gender" class="inputbox-main">
                                                                <option value="">Select Gender</option>
                                                                <option value="male" <?php if ($gender == 'male') echo 'selected'; ?>>Male</option>
                                                                <option value="female" <?php if ($gender == 'female') echo 'selected'; ?>>Female</option>
                                                            </select>
														</span>
													</div>
												</div>
											</div>
										</div>
										<div class="right-column">
											<div class="input-row">
												<div class="full">
													<div class="input-block">
														<label>Email Id: <span class="star">*</span></label>
														<span class="reg_span">
                                                        <input type="text" name="email" value="<?php echo $email; ?>" id="email" class="inputbox-main">
														</span>
													</div>
												</div>
											</div>
											<div class="input-row">
												<div class="full">
													<div class="input-block">
														<label>Password: <span class="star">*</span></label>
														<span class="reg_span">
															<input type="password" name="password" value="<?php echo $password; ?>" id="password" class="inputbox-main">
														</span>
													</div>
												</div>
											</div>
										</div>
										<div class="submit-class">
											<div class="full">
												<input type="submit" name="create" value="<?php echo isset($_GET['edit_id']) ? 'Update' : 'Add'; ?>" class="btn-submit btn" href> 
												<input type="button" value="Cancel" onclick="window.location.href='http://localhost/student%20registration/list.php';" class="btn-submit btn">
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
