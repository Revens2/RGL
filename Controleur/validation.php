<?php
session_start();
include '../db_connect.php';
include '../Model/cConnected.php';
include '../Model/cReservation.php';



$connect = new cConnected($conn);
$reserv = new cReservation($conn);

$result = $reserv->getUserValidation();


$editGymData = null;


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action == 'resaedit') {
            $resaid = isset($_POST['Id_reservation']) ? (int) $_POST['Id_reservation'] : null;
            $editGymData = $reserv->getReservationDetails($resaid);
            

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


?>

