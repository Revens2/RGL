<?php
session_start();
include '../db_connect.php';
include '../Model/cConnected.php';
include '../Model/cReservation.php';
include '../Model/cGymnase.php';
include '../Model/cSport.php';
$connect = new cConnected($conn);
$reserv = new cReservation($conn);
$gym = new cGymnase($conn);
$sport = new cSport($conn);
$editGymData = null;
$selectedGymId = null;
$selectedSportId = null;


$userid = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
$historique = $reserv->getUserReservations($userid);
?>
