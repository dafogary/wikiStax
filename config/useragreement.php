<?php
// This allows a user to create a wiki on the platform.
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../../login.php");
    exit;
}

// Include config file
require_once "../../config.php";

$con=mysqli_connect("localhost","dbuser","password","wikidbname");
// Check connection
if (mysqli_connect_errno())
{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
$user = mysqli_query($con, "SELECT * FROM user");
$result = mysqli_query($con,"SELECT * FROM useragreement");
?>
<!DOCTYPE html>
<?php include_once("../menu.php");?>
<html>
<head>
<title>WaaS Wiki User Agreement</title>
<link rel="stylesheet" href="../src/styles.css">
</head>
<body><div class="content">
<h1>The Wiki as a Service SyOPs agreement</h1>
<p>This table shows the User agreement signed dates for all of the users on the wikiname wiki</p>
<p>This snapshot was taken on:</p>
<?php
date_default_timezone_set("UTC");
echo "Today's date " . date("d/m/Y") . "<br>";
echo "Time: " . date("H:i:s") . " UTC/Z";
?>
<form>
<input type="button" value="Print or PDF this page" onClick="window.print()">
</form><br><br>
<div class="container">
	<form method='post' action='download.php'>
	<input type='submit' value='Export' name='Export'>
	<table border='1' style='border-collapse:collapse;'>
		<tr>
			<th>User Name</th>
			<th>User Email</th>
			<th>User ID</th>
			<th>Last Signed Date</th>
		</tr>
	<?php
	while($row = mysqli_fetch_array($result)) {
		$userid=$row['ua_user'];
		while($row2 = mysqli_fetch_array($user)) {
			if ($userid==$row2['user_id']) {
				$username = $row2['user_name'];
				$useremail = $row2['user_email'];
				break;
			}
		}
		$userid = $row['ua_user'];
		$useraccepttime = $row['ua_user_accepted_timestamp'];
		$user_arr[] = array($username,$useremail,$userid,$useraccepttime);
	?>
		<tr>
			<td><?php echo $username; ?></td>
			<td><?php echo $useremail; ?></td>
			<td><?php echo $userid; ?></td>
			<td><?php echo $useraccepttime; ?></td>
		</tr>
	<?php
	}
	?>
	</table>
	<?php
	$serialize_user_arr = serialize($user_arr);
	?>
	<textarea name='export_data' style='display: none;'><?php echo $serialize_user_arr; ?></textarea>
	</form>
</div>
<?php
mysqli_close($con);
?>
</body>
<footer>
<a href="../../index.php">Back to Main Page</a>
</footer>
</html>
<?php include_once("../footer.php");?>
