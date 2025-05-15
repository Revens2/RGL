<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../Model/cbdd.php';
require_once '../Model/cUtilisateur.php';
require_once '../Model/cReservation.php';

$cReservation = new cReservation();
$editGymData = null;


$onpopup = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action == 'resaedit') {
            $cReservation->setResaid(isset($_POST['Id_reservation']) ? (int) $_POST['Id_reservation'] : null);
            $onpopup = 1;
            $editGymData = $cReservation->GetValidReservation();


        } elseif ($action == 'saveedit') {

            $cReservation->setValid(isset($_POST['ddlvalid']) ? (int) $_POST['ddlvalid'] : null);
            $cReservation->setResaid(isset($_POST['Id_reservation']) ? (int) $_POST['Id_reservation'] : null);
            $cReservation->editValidation();

            header("Location: " . $_SERVER['PHP_SELF']);
            exit();


        } elseif ($action == 'delete') {


            $cReservation->setResaid(isset($_POST['Id_reservation']) ? (int) $_POST['Id_reservation'] : null);
            $cReservation->cancelReservation();

            header("Location: " . $_SERVER['PHP_SELF']);
            exit();

        } elseif ($action == 'closepopup') {
            $editGymData = null;

            header("Location: ../Vue/validation.php");

        }
    }
}

$dt = $cReservation->getUserValidation();

$finalRows = [];  
while ($row = $dt->fetch_assoc()) {
    if ($row['statut'] == 1) {
        $row['statut'] = "../icons/enregistre.png";
    }elseif ($row['statut'] == 2){
        $row['statut'] = "../icons/accepte.png";
    }elseif ($row['statut'] == 3){
        $row['statut'] = "../icons/attente.png";
    }elseif ($row['statut'] == 4){
        $row['statut'] = "../icons/annule.png";
    }
    $finalRows[] = $row;  
}

return $finalRows;



?>

