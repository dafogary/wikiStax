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
    <title>Create MD Archive - WikiStax</title>
</head>
<body>
	<div class="content">
		<h2>Create a markdown archive below</h2>
		<p><span class="warning">This page is to be only used by Wiki SysAdmins.</span></p>		
		<p>Please fill this form to create a new markdown archive page.</p>
		<div class="wrapper">
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
			
				<div class="form-group">
					<label>DB name (you can find this under the Wiki list page. Please do not use spaces)</label>
					<input type="text" name="db_name" class="form-control <?php echo (!empty($db_name)) ? 'is-invalid' : ''; ?>" value="<?php echo $wikiurl; ?>">
					<span class="invalid-feedback"><?php echo $db_name_err; ?></span>
				</div>
				<div class="form-group">
					<label>Wiki URL (do not include https:// or http://)</label>
					<input type="text" name="wikiurl" class="form-control <?php echo (!empty($wikiurl)) ? 'is-invalid' : ''; ?>" value="<?php echo $wikiurl; ?>">
					<span class="invalid-feedback"><?php echo $wikiurl_err; ?></span>
				</div>
				<div class="form-group">
					<label>Wiki name.</label>
					<input type="text" name="wiki_name" class="form-control <?php echo (!empty($wiki_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $wiki_name; ?>">
					<span class="invalid-feedback"><?php echo $wiki_name_err; ?></span>
				</div>)
				<div class="form-group">
					<label>Category (detail which category is to be archived)</label>
					<input type="text" name="selectedcat" class="form-control <?php echo (!empty($selectedcat_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $selectedcat; ?>">
					<span class="invalid-feedback"><?php echo $selectedcat_err; ?></span>
				</div>
				<div class="form-group">
					<label>Meaningful directory name (please do not include spaces, use _ if a space is required)</label>
					<input type="text" name="archivedirectory" class="form-control <?php echo (!empty($archivedirectory_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $arhivedirectory; ?>">
					<span class="invalid-feedback"><?php echo $archivedirectory_err; ?></span>
				</div>
				<div class="form-group">
					<label>Meaningful filename (please do not include spaces, use _ if a space is required)</label>
					<input type="text" name="archivedname" class="form-control <?php echo (!empty($archivedname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $arhivedname; ?>">
					<span class="invalid-feedback"><?php echo $archivedname_err; ?></span>
				</div>
				<div class="form-group">
					<label>Archive title (the character limit is 256)</label>
					<input type="text" name="archivetitle" class="form-control <?php echo (!empty($archivetitle_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $arhivetitle; ?>">
					<span class="invalid-feedback"><?php echo $archivetitle_err; ?></span>
				</div>
				<div class="form-group">
					<label>Periodicity</label>
					<select name="periodicity" class="form-control <?php echo (!empty($periodicity_err)) ? 'is-invalid' : ''; ?>">
						<option value="">Select Periodicity</option>
						<option value="monthly">Monthly</option>
						<option value="weekly">Every six months</option>
						<option value="yearly">Yearly</option>
					</select>
					<span class="invalid-feedback"><?php echo $periodicity_err; ?></span>
				</div>
				<div class="form-group">
					<label>Start month</label>
					<select name="startmonth" class="form-control <?php echo (!empty($startmonth_err)) ? 'is-invalid' : ''; ?>">
						<option value="">Select Month</option>
						<option value="january">January</option>
						<option value="weekly">Feburary</option>
						<option value="yearly">March</option>
						<option value="yearly">April</option>
						<option value="yearly">May</option>
						<option value="yearly">June</option>
						<option value="yearly">July</option>
						<option value="yearly">August</option>
						<option value="yearly">September</option>
						<option value="yearly">October</option>
						<option value="yearly">November</option>
						<option value="yearly">December</option>
					</select>
					<span class="invalid-feedback"><?php echo $startmonth_err; ?></span>
				</div>
				<div class="form-group">
					<label>Start month</label>
					<select name="startmonth" class="form-control <?php echo (!empty($startmonth_err)) ? 'is-invalid' : ''; ?>">
						<option value="">Select Month</option>
						<option value="january">January</option>
						<option value="february">Feburary</option>
						<option value="march">March</option>
						<option value="april">April</option>
						<option value="may">May</option>
						<option value="june">June</option>
						<option value="july">July</option>
						<option value="august">August</option>
						<option value="september">September</option>
						<option value="october">October</option>
						<option value="november">November</option>
						<option value="december">December</option>
					</select>
					<span class="invalid-feedback"><?php echo $startmonth_err; ?></span>
				</div><div class="form-group">
					<label>Archive day</label>
					<select name="archiveday" class="form-control <?php echo (!empty($archiveday_err)) ? 'is-invalid' : ''; ?>">
						<option value="">Select day</option>
						<option value="First Monday">First Monday</option>
						<option value="First Tuesday">First Tuesday</option>
						<option value="First Wednesday">First Wednesday</option>
						<option value="First Thursday">First Thursday</option>
						<option value="First Friday">First Friday</option>
						<option value="First Saturday">First Saturday</option>
						<option value="First Sunday">First Sunday</option>
						<option value="Second Monday">Second Monday</option>
						<option value="Second Tuesday">Second Tuesday</option>
						<option value="Second Wednesday">Second Wednesday</option>
						<option value="Second Thursday">Second Thursday</option>
						<option value="Second Friday">Second Friday</option>
						<option value="Second Saturday">Second Saturday</option>
						<option value="Second Sunday">Second Sunday</option>
						<option value="Third Monday">Third Monday</option>
						<option value="Third Tuesday">Third Tuesday</option>
						<option value="Third Wednesday">Third Wednesday</option>
						<option value="Third Thursday">Third Thursday</option>
						<option value="Third Friday">Third Friday</option>
						<option value="Third Saturday">Third Saturday</option>
						<option value="Third Sunday">Third Sunday</option>
						<option value="Fourth Monday">Fourth Monday</option>
						<option value="Fourth Tuesday">Fourth Tuesday</option>
						<option value="Fourth Wednesday">Fourth Wednesday</option>
						<option value="Fourth Thursday">Fourth Thursday</option>
						<option value="Fourth Friday">Fourth Friday</option>
						<option value="Fourth Saturday">Fourth Saturday</option>
						<option value="Fourth Sunday">Fourth Sunday</option>
					</select>
					<span class="invalid-feedback"><?php echo $archiveday_err; ?></span>
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