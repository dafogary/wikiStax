<?php
// This allows users to view all wikis within the platform.
// Please Note option in the future to allow editing is comming soon.
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

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
	echo $_POST["id"];
}
?>
<!DOCTYPE html>
<?php include_once("menu.php");?>
<html lang="en">
<head>
    <title>PDF Archives - WikiStax</title>
</head>
<body>
	<div class="content">
		<h2>The latest PDF Wiki Archives</h2>
		<table border="3" width="100%">
			<tr>
				<td>Archive ID</td>
				<td>Archive name</td>
				<td>Edit PHP Wiki Archive</td>
				<td>Wiki URL</td>
			</tr>
			<?php while($row = mysqli_fetch_array($result)){ ?>
				<tr>
					<td><?=$row['id']?></td>
					<td>archiving/<?=$row['wikiarchivedir']?>/pdf/<?=$row['archivedname']?>.pdf</td>
					<td><form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
						<input type="hidden" name="id" value="<?php echo $row['id'];?>">
						<input type="submit" class="btn btn-primary" value="Edit archive"></form></td>
						<td><?=$row['wikiurl']?></td>				
				</tr>
			<?php } ?>
		</table>
		<?php
		mysqli_stmt_close($stmt);
		mysqli_close($link); ?>
	</div>
</body>
</html>
<?php include_once("footer.php");?>