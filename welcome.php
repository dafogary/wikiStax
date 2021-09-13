<?php
// This page is the main welcome page that only Loggedin users can access
// This provides options to manage the wiki environment.
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
    <meta charset="UTF-8">
    <title>MWAdmin</title>
</head>
<body>
	<div class="content">
		<h1 class="my-5">Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to the MediaWiki Management Interface.</h1>
		<p>
			<a href="createwiki.php" class="btn btn-dark">Create wiki</a>
			<a href="wikilist.php" class="btn btn-primary ml-3">Wiki list</a>
			<a href="#" class="btn btn-warning ml-3">Spare</a>
			<a href="removewiki.php" class="btn btn-danger ml-3">Remove Wiki</a>
		</p>
	</div>
</body>
</html>
