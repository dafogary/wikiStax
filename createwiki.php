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
<?php include_once("menu.php");?>
<html lang="en">
<head>
    <title>Create Wiki - MWAdmin</title>
</head>
<body>
	<div class="content">
		<h2>Create a wiki below</h2>
		<p>Please fill this form to create a new wiki.</p>
		<div class="wrapper">
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
				<div class="form-group">
					<label>Wiki Directory e.g. "/var/www/html/wiki"</label>
					<input type="text" name="wiki_dir" class="form-control <?php echo (!empty($wiki_dir_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $wiki_dir; ?>">
					<span class="invalid-feedback"><?php echo $wiki_dir_err; ?></span>
				</div>
				<div class="form-group">
					<label>URL Domain e.g. "https://wiki.test.co.uk"</label>
					<input type="text" name="url_raw" class="form-control <?php echo (!empty($url_raw_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $url_raw; ?>">
					<span class="invalid-feedback"><?php echo $url_raw_err; ?></span>
				</div>
				<div class="form-group">
					<label>Subfolder e.g. "/wiki"</label>
					<input type="text" name="subfolder" class="form-control <?php echo (!empty($subfolder_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $subfolder; ?>">
					<span class="invalid-feedback"><?php echo $subfolder_err; ?></span>
				</div>
				<div class="form-group">
					<label>Database Name</label>
					<input type="text" name="db_name_raw" class="form-control <?php echo (!empty($db_name_raw_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $db_name_raw; ?>">
					<span class="invalid-feedback"><?php echo $db_name_raw_err; ?></span>
				</div>
				<div class="form-group">
					<label>Wiki name</label>
					<input type="text" name="wiki_name" class="form-control <?php echo (!empty($wiki_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $wiki_name; ?>">
					<span class="invalid-feedback"><?php echo $wiki_name_err; ?></span>
				</div>
				<div class="form-group">
					<label>Wiki Namespace</label>
					<input type="text" name="wiki_ns" class="form-control <?php echo (!empty($wiki_ns_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $wiki_ns; ?>">
					<span class="invalid-feedback"><?php echo $wiki_ns_err; ?></span>
				</div>
				<div class="form-group">
					<input type="submit" class="btn btn-primary" value="Submit">
					<input type="reset" class="btn btn-secondary ml-2" value="Reset">
				</div>
			</form>
		</div>
	</div>
</body>
</html>