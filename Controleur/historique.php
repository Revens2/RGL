<?php
session_start();
require_once '../Model/cUtilisateur.php';
require_once '../Model/cReservation.php';
$user = new cUtilisateur();
$cReservation = new cReservation();

$historique = $cReservation->getUserHistorique();
?>
