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
    <title>WikiStax</title>
</head>
<body>
	<div class="content">
		<h1 class="my-5">Hi <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to the WikiStax Interface.</h1>
		<p><h2>Links for all users</h2>
		The following links are for all users.<br><br>
			<a href="wikilist.php" class="btn btn-primary ml-3">Wiki list</a>
			<a href="pdfarchives.php" class="btn btn-primary ml-3">PDF Archive</a>
			<br><br>
			<a href="thankyou.php" class="btn btn-primary ml-3">WikiStax thank you</a>
		<br><br>		
		<h2>Links for SysAdmin users only</h2>
		The following links are only for SysAdmins only.<br><br>
			<a href="createwiki.php" class="btn btn-dark">Create wiki</a>
			<a href="createuseragreement.php" class="btn btn-warning ml-3">Create user agreement</a>
			<a href="createpdfarchive.php" class="btn btn-info ml-3">Create PDF archive</a>
			<br><br>
			<a href="#" class="btn btn-secondary ml-3">Create MD archive<br>(coming soon)</a>
			<a href="#" class="btn btn-secondary ml-3">Create XML archive<br>(coming soon)</a>
			<br><br>
			<a href="removewiki.php" class="btn btn-danger ml-3">Remove Wiki</a>
			<a href="removepdfarchive.php" class="btn btn-danger ml-3">Remove PDF Archive</a>
			<a href="removeuseragreement.php" class="btn btn-danger ml-3">Remove User Agreement</a>
		</p>
	</div>
</body>
</html>
<?php include_once("footer.php");?>