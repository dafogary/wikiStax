<?php
// This page allows users to change their user information.
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Include config file
require_once "config.php";

?>

<!DOCTYPE html>
<?php include_once("menu.php"); ?>
<html lang="en">
<head>
	<title>WikiStax Thank You</title>
</head>
<body>
	<div class="content">
	<h1 class="my-5">WikiStax Thank You.</h1>
		Thank you for using WikiStax!<br><br>
		If you have found WikiStax useful, please consider supporting us.<br><br>
		We appreciate your feedback and support.<br><br>
		<form action="https://www.paypal.com/donate" method="post" target="_top">
<input type="hidden" name="hosted_button_id" value="EMP2RCPMMGUGQ" />
<input type="image" src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_donateCC_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
<img alt="" border="0" src="https://www.paypal.com/en_GB/i/scr/pixel.gif" width="1" height="1" />
</form><br><br>
		WikiStax is a free and open-source project, and we rely on the support of our users to keep it running.<br><br>
		Your donations help us to cover the costs of hosting, development, and maintenance.<br><br>
		If you have any questions or need assistance, please head over to <a href="https://wikistax.org" target="_blank">WikiStax.org</a>.<br><br>
		We are here to help you!<br><br>
		Again. thank you for your support!<br><br>
		Best regards,<br>The WikiStax Team
	</div>
</body>
</html>
<?php include_once("footer.php");?>