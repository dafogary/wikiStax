<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
    <nav class="navtop">
        <div>
            <h1><a href="welcome.php">MediaWiki Management Interface</a></h1>
            <a href="register.php"><i class="fas fa-users"></i>Create User Account</a>
			<a href="reset-password.php"><i class="fas fa-cog"></i>Change Password</a>
            <a href="profile.php"><i class="fas fa-user-circle"></i><?php echo htmlspecialchars($_SESSION["username"]); ?></a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
        </div>
    </nav>
</body>
