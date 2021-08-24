<?php
// Initialize the session
session_start()

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"] || $_SESSION["loggedin"] !== true){
    header("locationL: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Profile Page - MWAdmin</title>
</head>
<body>
	<?php include_once("menu.php");?>
	<h1 class="my-5">Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>.</h1>
	<div>
		<p>Your Account details are below:</p>
		<table>
			<tr>
				<td>Username:</td>
				<td><?=$_SESSION["username"]?></td>
			</tr>
			<tr>
				<td>Password:</td>
				<td><?=$_SESSION["password"]?></td>
			</tr>
			<tr>
				<td>Email:</td>
				<td><?=$_SESSION["email"]?></td>
			</tr>
		</table>
	</div>
</body>
</html>