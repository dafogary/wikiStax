<?php
// Initialize the session
session_start()

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"] || $_SESSION["loggedin"] !== true){
    header("locationL: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include_once("menu.php");?>

