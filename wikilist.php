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

$sql = "SELECT id, wikiname, admin, adminemail FROM wikis WHERE id = ?";
$stmt = mysqli_prepare($link, $sql);

mysqli_stmt_bind_param($stmt, "i", $pid);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

?>
<!DOCTYPE html>
<?php include_once("menu.php");?>
<html lang="en">
<head>
    <title>Wiki List - MWAdmin</title>
</head>
<body>
	<div class="content">
		<h2>View wikis below</h2>
		<table border="3">
			<tr>
				<td>Wiki ID</td>
				<td>Wiki Name</td>
				<td>Wiki Admin User</td>
				<td>Wiki Admin Email</td>
			</tr>
			<?php while($row = mysqli_fetch_array($result)){ ?>
				<tr>
					<td><?php echo $row['id']?></td>
					<td><?=$row['wikiname']?></td>
					<td><?=$row['admin']?></td>
					<td><?=$row['adminemail']?></td>
				</tr>
			<?php } ?>
		</table>
	</div>
</body>
</html>