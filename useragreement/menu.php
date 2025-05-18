<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <link href="https://fonts.googleapis.com/css2?family=Nova+Square&display=swap" rel="stylesheet">
    <link href="../src/style.css" rel="stylesheet" type="text/css">
</head>
<body>
    <nav class="navtop">
        <div>
            <h1><a href="../../welcome.php">WikiStax Interface</a></h1>
            <a href="../../register.php"><i class="fas fa-users"></i>Create User Account</a>
			<a href="../../changepassword.php"><i class="fas fa-cog"></i>Change Password</a>
            <a href="../../profile.php"><i class="fas fa-user-circle"></i><?php echo htmlspecialchars($_SESSION["username"]); ?></a>
            <a href="../../logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
        </div>
    </nav>
</body>
