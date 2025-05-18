<?php
// This allows a user to delete a wiki on the platform.
// PLEASE NOTE: This page is not completed yet.
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Include config file
require_once "config.php";

// Pull the information from the database
$sql = "SELECT * FROM pdfarchives";
$stmt = mysqli_prepare($link, $sql);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$selected_id = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
	if($_POST["selected_id"] === "Select one PDF archive to delete"){
		$selected_id_err = "Please select a valid option";
	} else {
		$selected_id = trim($_POST["selected_id"]);
		// Pull folder location for selected wiki
		while($row = mysqli_fetch_array($result)){
			if($selected_id == $row['id']){
				$archived_name = $row['archivedname'];
				$archive_dir = $row['wikiarchivedir'];
				$crontab_line = $row['crontab'];
				$db_name = $row['dbname'];
			}
		}
		echo $archivedname;
		// Delete files and folder
		echo shell_exec("rm -rf " . escapeshellarg($archive_dir));
		
		
		// Delete mysql database
		echo shell_exec("mysql --user='root' --password='{$mysql_password}' --execute='REMOVE DATABASE {$db_name};'");

		// Remove wiki from wikistax db
		$sql = "DELETE FROM wikis WHERE id = ?";
		if($stmt = mysqli_prepare($link, $sql)){
			// Bind variables to the prepared statement as parameters
			mysqli_stmt_bind_param($stmt, "s", $selected_id);
			
			// Attempt to execute the prepard statement
			if(mysqli_stmt_execute($stmt)){
				header("location: welcome.php"); // Redirect back to welcome page
			} else{
				echo "Oops! The PDF archive wasn't removed from the database. Please let the SysAdmin know.";
			}
			
			// Close statement
			mysqli_close($link);
		}
		// Remove archive from the crontab
		
	}

}
?>

<!DOCTYPE html>
<?php include_once("menu.php");?>
<html lang="en">
<head>
    <title>Delete PDF Archive - WikiStax</title>
</head>
<body>
	<div class="content">
		<h2>Delete a PDF Archive below</h2>
		<p class="warning">Please Note this is not reversable! All scripts and PDFs will be destroyed!</p>
		<p><span class="warning">This page is to be only used by Wiki SysAdmins.</span></p>
		<p>Please select the PDF archive you wish to delete</p>
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
			<select name="selected_id" id="selected_id" class="form-control">
				<option selected="selected" >Select one PDF archive to delete</option>
				<?php
				while($row = mysqli_fetch_array($result)){
					$id = $row['id'];
					$wikiname = $row['wikiname'];
					echo "<option value='$id'>$wikiname</option>";
				}
				?>
			</select>
			<input type="submit" class="btn btn-primary" value="Submit">
		</form>
		<?php
		mysqli_stmt_close($stmt);
		mysqli_close($link); ?>
	</div>
</body>
</html>
<?php include_once("footer.php");?>