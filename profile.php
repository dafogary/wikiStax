<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
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
				<td><?=$_SESSION["email"]?></td>
			</tr>
		</table>
	</div>
</body>
</html>