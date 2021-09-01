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
?>

<!DOCTYPE html>
<?php include_once("menu.php");?>
<html lang="en">
<head>
    <title>Delete Wiki - MWAdmin</title>
</head>
<body>
	<div class="content">
		<h2>Delect a wiki below</h2>
		<p>Please select the wiki you wish to delete</p>
	</div>
</body>
</html>