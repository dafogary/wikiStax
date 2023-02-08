<?php
// This allows a user to create a wiki on the platform.
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$db_name = $wiki_name = "";
$db_name_err = $wiki_name_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
	
	// Validate Database name
	if(empty(trim($_POST["db_name"]))){
		$db_name_err = "Please enter a Database Name.";
	} elseif(strpos(trim($_POST["db_name"]),' ') !== false) {
		$db_name_err = "Database name cannot contain spaces.";
	} else{
		$db_name = strtolower(trim($_POST["db_name"]));
	}
	
	// Validate Wiki Name
	if(empty(trim($_POST["wiki_name"]))){
		$wiki_name_err = "Please enter a wiki name.";
	} else{
		$wiki_name = trim($_POST["wiki_name"]);
	}
	
		// Check input errors before performing tasks
	if(empty($db_name_err) && empty($wiki_name_err)){
		
		
		// Create required variables
		$useragreementfile = "$mwadmin/useragreement/{$db_name}/useragreement.php";
		echo shell_exec("mkdir $mwadmin/useragreement/{$db_name}/"); // Making the new directory
		echo shell_exec("cp $mwadmin/config/useragreement.php $mwadmin/useragreement/{$db_name}/useragreement.php");
		echo shell_exec("cp $mwadmin/config/download.php $mwadmin/useragreement/{$db_name}/download.php");
		
		//Performing tasks to create the localsettings.php file for the wiki
		$local = file_get_contents($useragreementfile);
		$local = str_replace("wikidbname", $db_name, $local);
		$local = str_replace("wikiname", $wiki_name, $local);
		file_put_contents($useragreementfile, $local);
				
			
			// Attempt to execute the prepared statement
				header("location: welcome.php"); // Redirect back to welcome page once wiki creation is complete.
			} else{
				echo "Oops! The wiki wasn't added to the database.";
			}
			
			// Close statement
			mysqli_close($link);

	}

?>
<!DOCTYPE html>
<?php include_once("menu.php");?>
<html lang="en">
<head>
    <title>Create Wiki - Useragreement page</title>
</head>
<body>
	<div class="content">
		<h2>Create a useragreement acceptance page below</h2>
		<p><span class="warning">This page is to be only used by Wiki SysAdmins.</span></p>		
		<p>Please fill this form to create a new useragreement acceptance page.</p>
		<div class="wrapper">
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
				<div class="form-group">
					<label>Database Name for the Wiki in question</label>
					<input type="text" name="db_name" class="form-control <?php echo (!empty($db_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $db_name; ?>">
					<span class="invalid-feedback"><?php echo $db_name_err; ?></span>
				</div>
				<div class="form-group">
					<label>Wiki name</label>
					<input type="text" name="wiki_name" class="form-control <?php echo (!empty($wiki_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $wiki_name; ?>">
					<span class="invalid-feedback"><?php echo $wiki_name_err; ?></span>
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
<?php include_once("footer.php");?>