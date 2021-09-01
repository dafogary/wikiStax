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

// Pull the information from the database
$sql = "SELECT * FROM wikis";
$stmt = mysqli_prepare($link, $sql);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$selected_id = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
	if($_POST["selected_id"] === "Select one wiki to delete"){
		$selected_id_err = "Please select a valid option";
	} else {
		$selected_id = trim($_POST["selected_id"]);
		echo "The selected id is: {$selected_id}";
		// Pull folder location for selected wiki
		
		// Delete folder
		
		// Delete mysql database
		
		// Remove wiki from mwadmin db
	}

}
?>

<!DOCTYPE html>
<?php include_once("menu.php");?>
<html lang="en">
<head>
    <title>Delete Wiki - MWAdmin</title>
</head>
<body>
	<div class="content">
		<h2>Delete a wiki below</h2>
		<p style="color: red">Please Note this is not reversable</p>
		<p>Please select the wiki you wish to delete</p>
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
			<select name="selected_id" id="selected_id" class="form-control">
				<option selected="selected" >Select one wiki to delete</option>
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