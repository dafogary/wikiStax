<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Include config file
require_once "config.php";

// Define variables and initalized with empty values
$email = $_SESSION["email"];
$email_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {
	
	// Validate email
	if(empty(trim($_POST["email"]))){
		$email_err = "Please enter a email.";
	} elseif(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
		$email_err = "Invalid email format";
	} else {
		$email = trim($_POST["email"]);
	}
	
	// Check input errors before updating the database
	if(empty($email_err)){
		// Preprare and insert statement
		$sql = "UPDATE users SET email = ? WHERE id = ?";
		
		if($stmt = mysqli_prepare($link, $sql)){
			// Bind variables to the prepared statement as parameters
			mysqli_stmt_bind_param($stmt, "si", $email, $param_id);
			
			// Set paramters
			$param_id = $_SESSION["id"];
			
			// Attempt to execute the prepared statement
			if(mysqli_stmt_execute($stmt)){
				// Redirect to welcome page
				$_SESSION["email"] = $email;
				header("location: welcome.php");
			} else{
				echo "Oops! Something went wrong. Please try again later.";
			}

			// Close statement
			mysqli_stmt_close($stmt);
		}
	}
}
?>

<!DOCTYPE html>
<?php include_once("menu.php"); ?>
<html lang="en">
<head>
	<title>Profile Page - MWAdmin</title>
</head>
<body>
	<h1 class="my-5">Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>.</h1>
	<div class="content">
		<p>Your Account details are below:</p>
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
			<table>
				<tr>
					<td>Username:</td>
					<td><?=$_SESSION["username"]?></td>
				</tr>
				<tr>
					<td>Password:</td>
					<td><a href="reset-password.php" class="btn btn-primary">Change password</a></td>
				</tr>
				<tr>
					<td>Email:</td>
					<td><div class="form-group">
						<input type="text" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
						<span class="invalid-feedback"><?php echo $email_err; ?></span></div></td>
				</tr>
			</table>
			<div class="form-group">
				<input type="submit" class="btn btn-primary" value="Submit">
				<input type="reset" class="btn btn-secondary ml-2" value="Reset">
			</div>
		</form>
	</div>
</body>
</html>