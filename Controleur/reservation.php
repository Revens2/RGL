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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['refresh'])) {
       
        $resaid = isset($_POST['Id_reservation']) ? (int) $_POST['Id_reservation'] : null;
        $selectedGymId = isset($_POST['gym_id']) ? (int) $_POST['gym_id'] : null;
        $editGymData = $reserv->getReservationDetails($resaid);
    } elseif (isset($_POST['action'])) {
        $action = $_POST['action'];
        if ($action == 'supp') {
            $resaid = isset($_POST['Id_reservation']) ? (int) $_POST['Id_reservation'] : null;
            $reservations = $reserv->deleteReservation($resaid);
        } elseif ($action == 'openresaedit') {
            $resaid = isset($_POST['Id_reservation']) ? (int) $_POST['Id_reservation'] : null;
            $editGymData = $reserv->getReservationDetails($resaid);
        } elseif ($action == 'saveedit') {
          
            $resaid = isset($_POST['Id_reservation']) ? (int) $_POST['Id_reservation'] : null;
            $gym_id = isset($_POST['gym_id']) ? (int) $_POST['gym_id'] : null;
            $sport_id = isset($_POST['sport_id']) ? (int) $_POST['sport_id'] : null;
            $datedebut = isset($_POST['datedebut']) ? $_POST['datedebut'] : null;
            $datefin = isset($_POST['datefin']) ? $_POST['datefin'] : null;
            $commentaire = isset($_POST['commentaire']) ? $_POST['commentaire'] : null;

            $reserv->editReservation($resaid, $gym_id, $sport_id, $datedebut, $datefin, $commentaire);

            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } elseif ($action == 'closepopup') {
            $editGymData = null;

            header("Location: ../Vue/reservation.php");

        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['gym_id'])) {
        $selectedGymId = (int) $_GET['gym_id'];
    }
}

$userid = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;


$dt = $reserv->getUserReservations($userid);

$finalRows = [];
while ($row = $dt->fetch_assoc()) {
    if ($row['statut'] == 1) {
        $row['statut'] = "../icons/termine.png";
    } elseif ($row['statut'] == 2) {
        $row['statut'] = "../icons/accepte.png";
    } elseif ($row['statut'] == 3) {
        $row['statut'] = "../icons/attente.png";
    } elseif ($row['statut'] == 4) {
        $row['statut'] = "../icons/annule.png";
    }
    $finalRows[] = $row;
}

return $finalRows;

?>
