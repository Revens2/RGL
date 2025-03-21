<?php
session_start();
require_once '../Model/cbdd.php';
include '../Model/cUtilisateur.php';
include '../Model/cReservation.php';
include '../Model/cGymnase.php';
include '../Model/cSport.php';
$conn = new cbdd();
$connect = new cUtilisateur();
$reserv = new cReservation($conn);
$gym = new cGymnase($conn);
$sport = new cSport($conn);
$editGymData = null;
$selectedGymId = null;
$selectedSportId = null;


$userid = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
$historique = $reserv->getUserHistorique($userid);
?>
