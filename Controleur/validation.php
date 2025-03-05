<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include '../db_connect.php';
include '../Model/cConnected.php';
require_once '../Model/cReservation.php';



$connect = new cConnected($conn);
$reserv = new cReservation($conn);
$editGymData = null;


$onpopup = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action == 'resaedit') {
            $resaid = isset($_POST['Id_reservation']) ? (int) $_POST['Id_reservation'] : null;
            $onpopup = 1;
            $editGymData = $reserv->GetValidReservation($resaid);
           
           
        } elseif ($action == 'saveedit') {

            $valid = isset($_POST['ddlvalid']) ? (int) $_POST['ddlvalid'] : null;
            $resaid = isset($_POST['Id_reservation']) ? (int) $_POST['Id_reservation'] : null;
            $reserv->editValidation($valid,$resaid);

            header("Location: " . $_SERVER['PHP_SELF']);
            exit();

        
        } elseif ($action == 'delete') {

            
            $resaid = isset($_POST['Id_reservation']) ? (int) $_POST['Id_reservation'] : null;
            $reserv->cancelReservation($resaid);

            header("Location: " . $_SERVER['PHP_SELF']);
            exit();

        }

        
    }
}

$dt = $reserv->getUserValidation();

$finalRows = [];  
while ($row = $dt->fetch_assoc()) {
    if ($row['statut'] == 1) {
        $row['statut'] = "../icons/termine.png";
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

