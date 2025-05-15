<?php
session_start();
require_once '../Model/cUtilisateur.php';
require_once '../Model/cReservation.php';
$cUtilisateur = new cUtilisateur();
$cReservation = new cReservation();
$cReservation->SetUserId($cUtilisateur->GetUserId());
$historique = $cReservation->getUserHistorique();
?>
