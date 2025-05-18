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
$wiki_dir = $url_raw = $subfolder = $db_name = $wiki_name = $wiki_ns = $db_vanilla = $mysql_password = $admin_user = $admin_email ="";
$wiki_dir_err = $url_raw_err = $subfolder_err = $db_name_err = $wiki_name_err = $wiki_ns_err = $db_vanilla_err = $mysql_password_err = $admin_user_err = $admin_email_err = "";

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
	} elseif(!filter_var(trim($_POST["url_raw"]), FILTER_VALIDATE_URL)) {
		$url_raw_err = "Please enter a valid URL.";
	} elseif(!(strpos(trim($_POST["url_raw"]), "http://") === 0 || strpos(trim($_POST["url_raw"]), "https://") === 0 ) ){
		$url_raw_err = "Please enter a url with either http:// or https://";
	} else{
		// Set parameters
		$url_raw = trim($_POST["url_raw"]);
	}
	
	// Validate Subfolder
	$subfolder = trim($_POST["subfolder"]); // No validation is required as it can be blank
	
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
	
	// Validate Wiki Namespace
	if(empty(trim($_POST["wiki_ns"]))){
		$wiki_ns_err = "Please enter the wiki namespace.";
	} else{
		$wiki_ns = trim($_POST["wiki_ns"]);
	}
	
	// Validate Admin User
	if(empty(trim($_POST["admin_user"]))){
		$admin_user_err = "Please enter the admin user.";
	} elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["admin_user"]))){
        $admin_user_err = "Admin user can only contain letters, numbers, and underscores.";
	} else{
		$admin_user = trim($_POST["admin_user"]);
	}
	
	// Validate Admin Email
	if(empty(trim($_POST["admin_email"]))){
		$admin_email_err = "Please enter the admin email.";
	} elseif(!filter_var($_POST["admin_email"], FILTER_VALIDATE_EMAIL)) {
		$email_err = "Invalid admin email format";
	} else{
		$admin_email = trim($_POST["admin_email"]);
	}
	
	// Validate DB_vanilla
	if(empty(trim($_POST["db_vanilla"]))){
		$db_vanilla_err = "Please select and option.";
	} else{
		$db_vanilla = trim($_POST["db_vanilla"]);
	}
	
	//Validate root password only if required
	if($db_vanilla === "true" && empty(trim($_POST["mysql_password"]))){
		$mysql_password_err = "Mysql root password is required if using vanilla database";
	}elseif($db_vanilla === "true" && !empty(trim($_POST["mysql_password"]))){
		define('ROOT_USER', 'root');
		define('ROOT_PASSWORD', trim($_POST["mysqlpassword"]));
		// Attempt DB connection using root credentials
		$linktest = mysqli_connect(DB_SERVER, ROOT_USER, ROOT_PASSWORD, DB_NAME);
		if($linktest === false){
			$mysql_password_err = "Mysql root password is incorrect";
		}else{
			$mysql_password = trim($_POST["mysql_password"]);
		}
	}
	// Check input errors before performing tasks
	if(empty($wiki_dir_err) && empty($url_raw_err) && empty($subfolder_err) && empty($db_name_err) && empty($wiki_name_err) && empty($wiki_ns_err) && empty($db_vanilla_err) && empty($mysql_password_err)){
		// Create required variables
		$wiki_local = "{$wiki_dir}/LocalSettings_{$db_name}.php";
		$semantic = str_replace("http://", "", $url_raw); // Remove "http://" if applicable
		$semantic = str_replace("https://", "", $semantic); // Remove "https://" if applicable
		$semantic = $semantic . $subfolder; // Add the subfolder to the semantic url
		
		// Performing tasks to create wiki
		
		echo shell_exec("mkdir {$wiki_dir}/"); // Making the new directory
		
		// Copying files to the new directory
		echo shell_exec("ln -s {$farm}/* {$wiki_dir}");
			
		// Create cache and images directories
		echo shell_exec("mkdir {$wiki_dir}/cache");
		echo shell_exec("mkdir {$wiki_dir}/images");
		
		//Performing tasks to create the localsettings_local.php file for the wiki
		echo shell_exec("cp config/LocalSettings_local.php {$wiki_local}");
		$local = file_get_contents($wiki_local);
		$local = str_replace("wikidbname", $db_name, $local);
		$local = str_replace("subfolder", $subfolder, $local);
		$local = str_replace("wikiurl", $url_raw, $local);
		$local = str_replace("wikins", $wiki_ns, $local);
		$local = str_replace("wikiname", $wiki_name, $local);
		$local = str_replace("semantichost", $semantic, $local);
		$local = str_replace("image_dir", $wiki_dir, $local);
		file_put_contents($wiki_local, $local);
		
		// Enter code to add wiki to the global LocalSettings.php file
		// Path to the global LocalSettings.php file
		$global_localsettings = "{$farm}/LocalSettings.php";

		// Code to append to the LocalSettings.php file
		$new_wiki_code = "\n//wiki field start {$wiki_name}\n" .
						"} elseif ( strpos( \$callingurl, '/{$subfolder}' ) === 0 ) {\n" .
						"    require_once 'LocalSettings_{$db_name}.php';\n" .
						"//wiki field end\n";

		// Append the new code after the last "//wiki field end"
		$localsettings_content = file_get_contents($global_localsettings);
		$last_wiki_field_end_pos = strrpos($localsettings_content, "//wiki field end");
		if ($last_wiki_field_end_pos !== false) {
			$insert_position = $last_wiki_field_end_pos + strlen("//wiki field end\n");
			$localsettings_content = substr_replace($localsettings_content, $new_wiki_code, $insert_position, 0);
			file_put_contents($global_localsettings, $localsettings_content);
		} else {
			echo "Error: Could not find '//wiki field end' in LocalSettings.php.";
		}

		
		// Upload database for the new wiki
		if($db_vanilla === "true"){
			echo shell_exec("mysql --user='root' --password='{$mysql_password}' --execute='CREATE DATABASE {$db_name};'");
			$grantall = "GRANT ALL ON {$db_name}.* TO 'mediawiki'@'localhost';";
			echo shell_exec("mysql --user='root' --password='{$mysql_password}' --execute='{$grantall}'");
			echo shell_exec("mysql --user='root' --password='{$mysql_password}' --execute='FLUSH PRIVILEGES;'");
			echo shell_exec("mysql --user='root' --password='{$rootpasswd}' {$db_name} < config/VanillaDB.sql");
		}
		
		echo shell_exec("php {$farm}/maintenance/run.php update --conf {$wiki_local}"); // Performs mediawiki update on new wiki
		// Add new wiki to the wiki list
		$sql = "INSERT INTO wikis (wikiname, wikilocal, wikifolder, dbname, admin, adminemail, globallocal) VALUES (?, ?, ?, ?, ?, ?, ?)";
		if($stmt = mysqli_prepare($link, $sql)){
			// Bind variables to the prepared statement as parameters
			mysqli_stmt_bind_param($stmt, "sssssss", $wiki_name, $wiki_local, $wiki_dir, $db_name, $admin_user, $admin_email, $new_wiki_code);
			
			// Attempt to execute the prepared statement
			if(mysqli_stmt_execute($stmt)){
				header("location: welcome.php"); // Redirect back to welcome page once wiki creation is complete.
			} else{
				echo "Oops! The wiki wasn't added to the database.";
			}
			
			// Close statement
			mysqli_close($link);
		}
	}
}
?>
<!DOCTYPE html>
<?php include_once("menu.php");?>
<html lang="en">
<head>
    <title>Create Wiki - WikiStax</title>
</head>
<body>
	<div class="content">
		<h2>Create a wiki below</h2>
		<p><span class="warning">This page is to be only used by Wiki SysAdmins.</span></p>		
		<p>Please fill this form to create a new wiki.</p>
		<div class="wrapper">
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
				<div class="form-group">
					<label>Wiki Directory e.g. "/var/www/html/wiki"</label>
					<input type="text" name="wiki_dir" class="form-control <?php echo (!empty($wiki_dir_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $wiki_dir; ?>">
					<span class="invalid-feedback"><?php echo $wiki_dir_err; ?></span>
				</div>
				<div class="form-group">
					<label>URL Domain e.g. "https://wiki.test.com" - note, this shall not end with a /</label>
					<input type="text" name="url_raw" class="form-control <?php echo (!empty($url_raw_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $url_raw; ?>">
					<span class="invalid-feedback"><?php echo $url_raw_err; ?></span>
				</div>
				<div class="form-group">
					<label>Subfolder e.g. "wiki" - note, this needs to start with a /</label>
					<input type="text" name="subfolder" class="form-control <?php echo (!empty($subfolder_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $subfolder; ?>">
					<span class="invalid-feedback"><?php echo $subfolder_err; ?></span>
				</div>
				<div class="form-group">
					<label>Database Name</label>
					<input type="text" name="db_name" class="form-control <?php echo (!empty($db_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $db_name; ?>">
					<span class="invalid-feedback"><?php echo $db_name_err; ?></span>
				</div>
				<div class="form-group">
					<input type="radio" name="db_vanilla" value="true" id="vanilla_yes" />
					<label for="vanilla_yes">Vanilla DB</label>
					<input type="radio" name="db_vanilla" value="false" id="vanilla_no" />
					<label for="vanilla_no">Upload DB</label>
					<span class="invalid-feedback"><?php echo $db_vanilla_err; ?></span>
				</div>
				<div class="form-group">
					<label>Mysql Root Password (Only require if vanilla db is selected)</label>
					<input type="password" name="mysql_password" class="form-control <?php echo (!empty($mysql_password_err)) ? 'is-invalid' : '';?>" value="<?php echo $mysql_password; ?>">
					<span class="invalid-feedback"><?php echo $mysql_password_err; ?></span>
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
					<label>Admin User</label>
					<input type="text" name="admin_user" class="form-control <?php echo (!empty($admin_user_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $admin_user; ?>">
					<span class="invalid-feedback"><?php echo $admin_user_err; ?></span>
				</div>
				<div class="form-group">
					<label>Admin Email</label>
					<input type="text" name="admin_email" class="form-control <?php echo (!empty($admin_email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $admin_email; ?>">
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
