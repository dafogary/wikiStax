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

// Define variables and initialize with empty values
$wiki_dir = $url_raw = $subfolder = $db_name = $wiki_name = $wiki_ns = "";
$wiki_dir_err = $url_raw_err = $subfolder_err = $db_name_err = $wiki_name_err = $wiki_ns_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
	
	// Validate Wiki dir
	if(empty(trim($_POST["wiki_dir"]))){
		$wiki_dir_err = "Please enter the Wiki directory.";
	} else{
		// Set parameters
		$wiki_dir = trim($_POST["wiki_dir"]);
	}
	
	// Validate URL domain
	if(empty(trim($_POST["url_raw"]))){
		$url_raw_err = "Please enter the URL domain.";
	} else{
		// Set parameters
		$url_raw = trim($_POST["url_raw"]);
	}
	
	// Validate Subfolder
	$subfolder = trim($_POST["subfolder"]);
	
	// Validate Database name
	if(empty(trim($_POST["db_name"]))){
		$db_name_err = "Please enter a Database Name.";
	} else{
		$db_name = trim($_POST["db_name"]);
	}
	
	// Validate Wiki Name
	if(empty(trim($_POST["wiki_name"]))){
		$wiki_name_err = "Please enter a wiki name.";
	} else{
		$wiki_name = trim($_POST["wiki_name"]);
	}
	
	// Validate Wiki Namespace
	if(empty(trim($_POST["wiki_ns"]))){
		$wiki_ns_err = "Please enter the wiki namespace.";
	} else{
		$wiki_ns = trim($_POST["wiki_ns"]);
	}
	
	// Check input errors before performing tasks
	if(empty($wiki_dir_err) && empty($url_raw_err) && empty($subfolder_err) && empty($db_name_err) && empty($wiki_name_err) && empty($wiki_ns_err)){
		// Performing tasks to create wiki
		echo shell_exec("mkdir {$wiki_dir}/");
		echo shell_exec("ln -s "$farm"/api.php "$wiki_dir"/api.php");
		echo shell_exec("ln -s "$farm"/autoload.php "$wiki_dir"/autoload.php");
		echo shell_exec("ln -s "$farm"/CODE_OF_CONDUCT.md "$wiki_dir"/CODE_OF_CONDUCT.md");
		echo shell_exec("ln -s "$farm"/composer.json "$wiki_dir"/composer.json");
		echo shell_exec("ln -s "$farm"/composer.lock "$wiki_dir"/composer.lock");
		echo shell_exec("ln -s "$farm"/COPYING "$wiki_dir"/COPYING");
		echo shell_exec("ln -s "$farm"/CREDITS "$wiki_dir"/CREDITS");
		echo shell_exec("ln -s "$farm"/docs/ "$wiki_dir"/docs");
		echo shell_exec("ln -s "$farm"/extensions/ "$wiki_dir"/extensions");
		echo shell_exec("ln -s "$farm"/FAQ "$wiki_dir"/FAQ");
		echo shell_exec("ln -s "$farm"/HISTORY "$wiki_dir"/HISTORY");
		echo shell_exec("ln -s "$farm"/img_auth.php "$wiki_dir"/img_auth.php");
		echo shell_exec("ln -s "$farm"/includes/ "$wiki_dir"/includes");
		echo shell_exec("ln -s "$farm"/index.php "$wiki_dir"/index.php");
		echo shell_exec("ln -s "$farm"/INSTALL "$wiki_dir"/INSTALL");
		echo shell_exec("ln -s "$farm"/jsduck.json "$wiki_dir"/jsduck.json");
		echo shell_exec("ln -s "$farm"/languages/ "$wiki_dir"/languages");
		echo shell_exec("ln -s "$farm"/load.php "$wiki_dir"/load.php");
		echo shell_exec("ln -s "$farm"/LocalSettings.php "$wiki_dir"/LocalSettings.php");
		echo shell_exec("ln -s "$farm"/maintenance/ "$wiki_dir"/maintenance");
		echo shell_exec("ln -s "$farm"/mw-config/ "$wiki_dir"/mw-config");
		echo shell_exec("ln -s "$farm"/opensearch_desc.php "$wiki_dir"/opensearch_desc.php");
		echo shell_exec("ln -s "$farm"/README.md "$wiki_dir"/README.md");
		echo shell_exec("ln -s "$farm"/RELEASE-NOTES-1.35 "$wiki_dir"/RELEASE-NOTES-1.35");
		echo shell_exec("ln -s "$farm"/resources/ "$wiki_dir"/resources");
		echo shell_exec("ln -s "$farm"/rest.php "$wiki_dir"/rest.php");
		echo shell_exec("ln -s "$farm"/SECURITY "$wiki_dir"/SECURITY");
		echo shell_exec("ln -s "$farm"/skins/ "$wiki_dir"/skins");
		echo shell_exec("ln -s "$farm"/tests/ "$wiki_dir"/tests");
		echo shell_exec("ln -s "$farm"/thumb_handler.php "$wiki_dir"/thumb_handler.php");
		echo shell_exec("ln -s "$farm"/thumb.php "$wiki_dir"/thumb.php");
		echo shell_exec("ln -s "$farm"/UPGRADE "$wiki_dir"/UPGRADE");
		echo shell_exec("ln -s "$farm"/vendor/ "$wiki_dir"/vendor");
		echo shell_exec("mkdir "$wiki_dir"/cache");
		//echo shell_exec("cp images/ "$wiki_dir" -r
		
		// Enter code to edit the LocalSettings.php
	}
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
					<input type="text" name="db_name" class="form-control <?php echo (!empty($db_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $db_name; ?>">
					<span class="invalid-feedback"><?php echo $db_name_err; ?></span>
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