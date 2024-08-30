<?php
session_start();
include 'db_connect.php';

if ($_SESSION['role'] != 'chef de projet') {
    header("Location: login.php");
    exit();
}

?>

<!--<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h1>Welcome to the Dashboard</h1>
    <p>You are logged in as <?php echo $_SESSION['role']; ?>.</p>
</body>
</html>-->
