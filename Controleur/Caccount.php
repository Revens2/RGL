<?php
session_start();
include '../db_connect.php';
include '../Model/cConnected.php';
$connect = new cConnected($conn);


$userData = $connect->account();

?>


<?php
$conn->close();
?>
