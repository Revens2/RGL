<?php
session_start();
require_once '../Model/cUtilisateur.php';
require_once '../Model/cReservation.php';
$user = new cUtilisateur();
$reserv = new cReservation($user);

$historique = $reserv->getUserHistorique();
?>
