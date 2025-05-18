<?php
// Initialize the session
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Include config file
require_once "config.php";

// Initialize variables
$fields = [
    'db_name' => '',
    'wikiurl' => '',
    'wiki_name' => '',
    'selectedcat' => '',
    'archivedname' => '',
    'archivetitle' => '',
    'coverpage' => '',
    'periodicity' => '',
    'startmonth' => '',
    'archiveday' => '',
    'cron_hour' => '',
    'cron_minute' => ''
];

$errors = array_fill_keys(array_keys($fields), '');

// Define custom labels for fields
$field_labels = [
    'db_name' => 'Database Name (e.g., wiki_database)',
    'wikiurl' => 'Wiki URL (the URL of the Wiki, don\'t include http:// or https://)',
    'wiki_name' => 'Wiki Name',
    'selectedcat' => 'Selected Category (detail which category is to be archived)',
    'archivedname' => 'Archived Name (this will be the name of the archives PDF filename, do not include .pdf)',
    'archivetitle' => 'Archive Title (this will be the title of the archives PDF)',
    'coverpage' => 'Cover Page (optional, upload a cover page for the PDF, only JPG and PNG files are allowed)',
    'periodicity' => 'Periodicity (e.g., monthly, six monthly, yearly)',
    'startmonth' => 'Start Month (e.g., January, February)',
    'archiveday' => 'Archive Day (e.g., 1, 15)',
    'cron_hour' => 'Cron Hour (0-23)',
    'cron_minute' => 'Cron Minute (0-59)'
];

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate fields
    foreach ($fields as $field => &$value) {
        if ($field === 'coverpage') {
            // Check if a file was uploaded for coverpage
            if ($_FILES['coverpage']['error'] === UPLOAD_ERR_NO_FILE) {
                $errors[$field] = "Please upload a cover page.";
            } else {
                $value = $_FILES['coverpage']['name']; // Store the filename for later use
            }
        } else {
            // Validate other fields
            if (!isset($_POST[$field]) || strlen(trim($_POST[$field])) == 0) {
                $errors[$field] = "Please enter a valid value for $field.";
            } else {
                $value = strtolower(trim($_POST[$field]));
                if (in_array($field, ['db_name', 'wikiurl', 'archivedname', 'coverpage', 'periodicity', 'startmonth']) && strpos($value, ' ') !== false) {
                    $errors[$field] = ucfirst($field) . " cannot contain spaces.";
                }
            }
        }
    }
    // Additional validation for numeric fields
    if (!is_numeric($fields['cron_hour']) || $fields['cron_hour'] < 0 || $fields['cron_hour'] > 23) {
        $errors['cron_hour'] = "Hour must be a number between 0 and 23.";
    }
    if (!is_numeric($fields['cron_minute']) || $fields['cron_minute'] < 0 || $fields['cron_minute'] > 59) {
        $errors['cron_minute'] = "Minute must be a number between 0 and 59.";
    }

    // If no errors, proceed with processing
    if (!array_filter($errors)) {
        $wikiarchivedir = "$mwadmin/archiving/pdf/{$fields['db_name']}/";
        $coverpagedir = "$wikiarchivedir/coverpage/";

        // Create directories if they don't exist
        foreach ([$wikiarchivedir, $coverpagedir] as $dir) {
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
        }

        // Check input errors before performing tasks
        //if(empty($db_name_err) && empty($wikiurl_err) && empty($wiki_name_err) && empty($selectedcat_err) && empty($archivedname_err) && empty($archivetitle_err) && empty($coverpage_err) && empty($periodicity_err) && empty($startmonth_err) && empty($archiveday_err) && empty($cron_hour_err) && empty($cron_minute_err)){
        
        // Create required variables
        
        $db_name = $fields['db_name'];
        $wikiurl = $fields['wikiurl'];
        $wiki_name = $fields['wiki_name'];
        $selectedcat = $fields['selectedcat'];
        $archivedname = $fields['archivedname'];
        $archivetitle = $fields['archivetitle'];
        $cron_hour = $fields['cron_hour'];
        $cron_minute = $fields['cron_minute'];
        $startmonth = $fields['startmonth'];
        $archiveday = $fields['archiveday'];
        $periodicity = $fields['periodicity'];
        $coverpage = $fields['coverpage'];
        $wikiarchivedir = "$mwadmin/archiving/pdf/{$db_name}";
        $wikiarchivescripts = "$wikiarchivedir/scripts";
        $wikiarchiveprocessing = "$wikiarchivedir/processing";
        $wikiarchivefile = "$wikiarchivescripts/wikiArchive.sh";
        $getURLs = "$wikiarchivescripts/getURLS.py";
        //$removeLinksExternal = "$wikiarchivescripts/removeLinksExternal.sh";
        $removeRedirects = "$wikiarchivescripts/removeRedirects.py";
        $watermarkPDF = "$wikiarchivescripts/watermarkPDF.py";
        $coverpagetemplate = "$wikiarchivescripts/coverpage/index.html";
        $wikiarchivehtml = "$wikiarchiveprocessing/html";
        $wikiarchivemd = "$wikiarchivehtml/md/tmp";
        $wikiarchivehtmlurl = "$wikiarchivehtml/{$wikiurl}";
        $wikiarchivehtmlcombined = "$wikiarchiveprocessing/htmlcombined";
        $wikiarchiveurls = "$wikiarchiveprocessing/urls";
        $wikiarchivepdf = "$wikiarchiveprocessing/pdf";
        $geturls = "$wikiarchivescripts/getURLs.py";
        $watermarkpdf = "$wikiarchivescripts/watermarkPDF.py";
        $coverpagedir = "$wikiarchivescripts/coverpage/";
        //$coverpage = "$wikiarchivescripts/{$coverpage}";
        $coverpagehtml = "$coverpagedir/index.html";
        $wiki_url = "{$wikiurl}";
        $archivedname = "{$archivedname}.pdf";
        $archivetitle = "{$archivetitle}";
        $selectedcat = "{$selectedcat}";
        $periodicity = "{$periodicity}";
        $startmonth = "{$startmonth}";
        $archiveday = "{$archiveday}";
        $cron_hour = "{$cron_hour}";
        $cron_minute = "{$cron_minute}";
        
        //Set up PDF archive
        echo shell_exec("mkdir {$wikiarchivedir}"); // Making the new directory
        //echo shell_exec("mkdir {$coverpagedir}"); // Making the coverpage templates directory
        shell_exec("cp -R config/archiving/pdf {$wikiarchivescripts}/");
        
        /*
        echo shell_exec("cp config/archiving/pdf/getURLS.py {$getURLs}");
        echo shell_exec("cp config/archiving/pdf/watermarkPDF.py {$watermarkPDF}");
        echo shell_exec("cp config/archiving/pdf/removeRedirects.py {$removeRedirects}");
        echo shell_exec("cp config/archiving/pdf/templates/index.html {$coverpagetemplate}");
        */
        //echo shell_exec("chown -R www-data:www-data {$wikiarchivedir}"); // Changing ownership of the new directory
        //echo shell_exec("chmod -R 755 {$wikiarchivedir}"); // Changing permissions of the new directory
        
        if(isset($_FILES["coverpage"]) && $_FILES["coverpage"]["error"] == 0){
            $target_dir = "$coverpagedir/"; // Corrected target directory
            if (!is_dir($target_dir)) { // Check if the directory exists
                mkdir($target_dir, 0755, true); // Create the directory if it doesn't
            }
            $target_file = $target_dir . basename($_FILES["coverpage"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        //Perform tasks to create the Wiki PDF archive
        $wikiarchiveScript = $wikiarchivefile;
        $wikiarchive = file_get_contents($wikiarchiveScript);
        $wikiarchive = str_replace("wikiarchiveprocessing", "{$wikiarchiveprocessing}", $wikiarchive);
        $wikiarchive = str_replace("wikiarchivehtml", "{$wikiarchivehtml}", $wikiarchive);
        $wikiarchive = str_replace("wikiarchivemd", "{$wikiarchivemd}", $wikiarchive);
        $wikiarchive = str_replace("wikiarchivepdf", "{$wikiarchivepdf}", $wikiarchive);
        $wikiarchive = str_replace("wikiarchiveurls", "{$wikiarchiveurls}", $wikiarchive);
        $wikiarchive = str_replace("wikiarchivedir", "{$wikiarchivedir}", $wikiarchive);
        $wikiarchive = str_replace("wikiarchivehtmlcombined", "{$wikiarchivehtmlcombined}", $wikiarchive);
        $wikiarchive = str_replace("coverpagedir", "{$coverpagedir}", $wikiarchive);
        $wikiarchive = str_replace("coverpagehtml", "{$coverpagehtml}", $wikiarchive);
        file_put_contents($wikiarchiveScript, $wikiarchive);

        //Perform tasks to create the Wiki URLs for archivin
        $getURLsScript = $getURLs;
        $getURLs = file_get_contents($getURLsScript);
        $getURLs = str_replace("wikiurl", "{$wiki_url}", $getURLs);
        $getURLs = str_replace("selectedcat", "{$selectedcat}", $getURLs);
        $getURLs = str_replace("wikiarchiveurls", "{$wikiarchiveurls}", $getURLs);
        file_put_contents($getURLsScript, $getURLs);

        //Perform tasks to create the removeRedirects script
        $removeRedirectsScript = $removeRedirects;
        $removeRedirects = file_get_contents($removeRedirectsScript);
        $removeRedirects = str_replace("wikiarchivehtml", "{$wikiarchivehtml}", $removeRedirects);
        file_put_contents($removeRedirectsScript, $removeRedirects);

        //Perform tasks to create the watermarkPDF script
        $watermarkPDFScript = $watermarkPDF;
        $watermarkPDF = file_get_contents($watermarkPDFScript);
        $watermarkPDF = str_replace("wikiarchivehtml", "{$wikiarchivehtml}", $watermarkPDF);
        $watermarkPDF = str_replace("archive_title", "{$archivetitle}", $watermarkPDF);
        file_put_contents($watermarkPDFScript, $watermarkPDF);

        /*
        // Only uncomment this if you want to remove external links
        // Perform tasks to create the removeLinksExternal script
        $removeLinksExternal = file_get_contents($removeLinksExternal);
        $removeLinksExternal = str_replace("wikiarchivehtml", $wikiarchivehtml, $removeLinksExternal);
        file_put_contents($removeLinksExternal, $removeLinksExternal);
        */

        // Generate crontab entry
        $cron_minute = $fields['cron_minute'];
        $cron_hour = $fields['cron_hour'];
        $start_month = ucfirst($fields['startmonth']); // e.g., "January"
        $periodicity = $fields['periodicity']; // e.g., "monthly", "six_monthly", "yearly"
        $archiveday = $fields['archiveday']; // e.g., "First Monday"

        // Map months to numbers
        $month_map = [
            'January' => 1, 'February' => 2, 'March' => 3, 'April' => 4,
            'May' => 5, 'June' => 6, 'July' => 7, 'August' => 8,
            'September' => 9, 'October' => 10, 'November' => 11, 'December' => 12
        ];

        // Validate and parse archiveday
        if (preg_match('/^(First|Second|Third|Fourth|Fifth) (Monday|Tuesday|Wednesday|Thursday|Friday|Saturday|Sunday)$/i', $archiveday, $matches)) {
            $week_map = ['First' => 1, 'Second' => 2, 'Third' => 3, 'Fourth' => 4, 'Fifth' => 5];
            $day_map = ['Sunday' => 0, 'Monday' => 1, 'Tuesday' => 2, 'Wednesday' => 3, 'Thursday' => 4, 'Friday' => 5, 'Saturday' => 6];

            $week_number = $week_map[ucfirst($matches[1])]; // e.g., 1 for "First"
            $day_number = $day_map[ucfirst($matches[2])];   // e.g., 1 for "Monday"

            // Determine the months based on periodicity
            $months = [];
            if ($periodicity === 'monthly') {
                // Start from the selected month and include all subsequent months
                $start_month_num = $month_map[$start_month];
                $months = range($start_month_num, 12); // From start month to December
            } elseif ($periodicity === 'six_monthly') {
                $start_month_num = $month_map[$start_month];
                $months = [$start_month_num, ($start_month_num + 6 - 1) % 12 + 1]; // Every six months
            } elseif ($periodicity === 'yearly') {
                $months = [$month_map[$start_month]]; // Only the start month
            }

            // Generate the crontab entry
            $cron_months = implode(',', $months);
            $cron_entry = sprintf(
                "%d %d * %s %d#%d %s",
                intval($cron_minute),         // Minute
                intval($cron_hour),           // Hour
                $cron_months,                 // Months
                $day_number,                  // Day of the week (0 = Sunday, 1 = Monday, ...)
                $week_number,                 // Nth occurrence of the day
                escapeshellarg("$wikiarchivescripts/wikiArchive.sh") // Script path
            );

            // Ensure script path is valid
            $allowed_script = realpath($wikiarchivescripts . '/wikiArchive.sh');
            if ($allowed_script === false || strpos($allowed_script, realpath($mwadmin)) !== 0) {
                die("Invalid script path.");
            }

            // Read the current crontab
            $current_crontab = shell_exec('crontab -l 2>/dev/null');

            // Check if the entry already exists to avoid duplicates
            if (strpos($current_crontab, $cron_entry) === false) {
                // Append the new entry
                $new_crontab = $current_crontab . PHP_EOL . $cron_entry . PHP_EOL;
                // Write the new crontab
                $tmpfile = tempnam(sys_get_temp_dir(), 'cron');
                file_put_contents($tmpfile, $new_crontab);
                shell_exec("crontab $tmpfile");
                unlink($tmpfile);
            }
        } else {
            die("Invalid 'archiveday' format.");
        }

        // Build the sudo command
        $sudo_command = 'sudo crontab -e ' . escapeshellarg($tmpfile);

        // Execute the command
        shell_exec($sudo_command);

        // Save to database
        $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        if ($link === false) {
            die("ERROR: Could not connect. " . mysqli_connect_error());
        }

        $sql = "INSERT INTO pdfarchives (archivedname, wikiarchivedir, crontab, wikiurl, dbname) VALUES (?, ?, ?, ?, ?)";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "sssss", $fields['archivedname'], $wikiarchivedir, $cron_entry, $fields['wikiurl'], $fields['db_name']);

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
}
?>

<!DOCTYPE html>
<?php include_once("menu.php");?>
<html lang="en">
<head>
    <title>Create PDF Archive - WikiStax</title>
</head>
<body>
    <div class="content">
        <h2>Create a Wiki PDF archive</h2>
        <p>Please fill this form to create a new PDF archive.</p>
        <div class="wrapper">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <?php foreach ($fields as $field => $value): ?>
                <div class="form-group">
                    <label><?php echo $field_labels[$field]; ?></label>
                    <?php if ($field === 'periodicity'): ?>
                        <!-- Dropdown for periodicity -->
                        <select name="periodicity" class="form-control <?php echo (!empty($errors[$field])) ? 'is-invalid' : ''; ?>">
                            <option value="">Select Periodicity</option>
                            <option value="monthly" <?php echo ($value === 'monthly') ? 'selected' : ''; ?>>Monthly</option>
                            <option value="six_monthly" <?php echo ($value === 'six_monthly') ? 'selected' : ''; ?>>Six Monthly</option>
                            <option value="yearly" <?php echo ($value === 'yearly') ? 'selected' : ''; ?>>Yearly</option>
                        </select>
                    <?php elseif ($field === 'startmonth'): ?>
                        <!-- Dropdown for start month -->
                        <select name="startmonth" class="form-control <?php echo (!empty($errors[$field])) ? 'is-invalid' : ''; ?>">
                            <option value="">Select Start Month</option>
                            <option value="january" <?php echo ($value === 'january') ? 'selected' : ''; ?>>January</option>
                            <option value="february" <?php echo ($value === 'february') ? 'selected' : ''; ?>>February</option>
                            <option value="march" <?php echo ($value === 'march') ? 'selected' : ''; ?>>March</option>
                            <option value="april" <?php echo ($value === 'april') ? 'selected' : ''; ?>>April</option>
                            <option value="may" <?php echo ($value === 'may') ? 'selected' : ''; ?>>May</option>
                            <option value="june" <?php echo ($value === 'june') ? 'selected' : ''; ?>>June</option>
                            <option value="july" <?php echo ($value === 'july') ? 'selected' : ''; ?>>July</option>
                            <option value="august" <?php echo ($value === 'august') ? 'selected' : ''; ?>>August</option>
                            <option value="september" <?php echo ($value === 'september') ? 'selected' : ''; ?>>September</option>
                            <option value="october" <?php echo ($value === 'october') ? 'selected' : ''; ?>>October</option>
                            <option value="november" <?php echo ($value === 'november') ? 'selected' : ''; ?>>November</option>
                            <option value="december" <?php echo ($value === 'december') ? 'selected' : ''; ?>>December</option>
                        </select>
                    <?php elseif ($field === 'archiveday'): ?>
                        <select name="archiveday" class="form-control <?php echo (!empty($errors['archiveday'])) ? 'is-invalid' : ''; ?>">
                            <option value="">Select Archive Day</option>
                            <?php
                            $weeks = ['First', 'Second', 'Third', 'Fourth', 'Fifth'];
                            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                            foreach ($weeks as $week) {
                                foreach ($days as $day) {
                                    $value = strtolower($week . ' ' . $day);
                                    $selected = ($value === $fields['archiveday']) ? 'selected' : '';
                                    echo "<option value=\"$value\" $selected>$week $day</option>";
                                }
                            }
                            ?>
                        </select>
                        <span class="invalid-feedback"><?php echo $errors['archiveday']; ?></span>
                    <?php elseif ($field === 'coverpage'): ?>
                    <input type="file" name="coverpage" class="form-control <?php echo (!empty($errors['coverpage'])) ? 'is-invalid' : ''; ?>">
                    <?php elseif (in_array($field, ['cron_hour', 'cron_minute'])): ?>
                        <input type="number" name="<?php echo $field; ?>" class="form-control <?php echo (!empty($errors[$field])) ? 'is-invalid' : ''; ?>" value="<?php echo $value; ?>" min="0" max="<?php echo $field === 'cron_hour' ? '23' : '59'; ?>">
                    <?php else: ?>
                        <input type="text" name="<?php echo $field; ?>" class="form-control <?php echo (!empty($errors[$field])) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($value); ?>">
                    <?php endif; ?>
                    <span class="invalid-feedback"><?php echo $errors[$field]; ?></span>
                </div>
            <?php endforeach; ?>
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