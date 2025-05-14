<?php
session_start();
require_once '../Model/cUtilisateur.php';
require_once '../Model/cReservation.php';
require_once '../Model/cGymnase.php';
require_once '../Model/cSport.php';
$cUtilisateur = new cUtilisateur();
$cReservation = new cReservation();
$gym = new cGymnase();
$sport = new cSport();
$editGymData = null;
$selectedGymId = null;
$selectedSportId = null;
$resaid = null;
$error = null;




if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['refresh'])) {

        $cReservation->setResaid(isset($_POST['Id_reservation']) ? (int) $_POST['Id_reservation'] : null);
        $selectedGymId = isset($_POST['gym_id']) ? (int) $_POST['gym_id'] : null;
        $editGymData = $cReservation->getReservationDetails();
    } elseif (isset($_POST['action'])) {
        $action = $_POST['action'];
        if ($action == 'supp') {
            $cReservation->setResaid(isset($_POST['Id_reservation']) ? (int) $_POST['Id_reservation'] : null);
            $cReservation->cancelReservation();
        } elseif ($action == 'openresaedit') {
            $cReservation->setResaid(isset($_POST['Id_reservation']) ? (int) $_POST['Id_reservation'] : null);
            $resaid = isset($_POST['Id_reservation']) ? (int) $_POST['Id_reservation'] : null;
            $editGymData = $cReservation->getReservationDetails();
        } elseif ($action == 'saveedit') {
            $cReservation->SetResaid(isset($_POST['Id_reservation']) ? (int) $_POST['Id_reservation']: null);
            $cReservation->setGymId(isset($_POST['gym_id']) ? (int) $_POST['gym_id'] : null);
            $cReservation->setSportId(isset($_POST['sport_id']) ? (int) $_POST['sport_id'] : null);
            $cReservation->setDateDebut(isset($_POST['datedebut']) ? $_POST['datedebut'] : null);
            $cReservation->setDateFin(isset($_POST['datefin']) ? $_POST['datefin'] : null);
            $cReservation->setCommentaire(isset($_POST['commentaire']) ? $_POST['commentaire'] : null);
            $startTime = strtotime(isset($_POST['datedebut']) ? $_POST['datedebut'] : null);
            $endTime = strtotime(isset($_POST['datefin']) ? $_POST['datefin'] : null);

            if ($startTime === false || $endTime === false) {
                $errorMsg = "Les dates saisies ne sont pas valides.";
                header("Location: ../Vue/reservation.php?error=" . urlencode($errorMsg));
            }

            if ($endTime <= $startTime) {
                $errorMsg = "La date de fin doit etre strictement posterieure a la date de debut.";
                header("Location: ../Vue/reservation.php?error=" . urlencode($errorMsg));
            }

            if (strpos($commentaire, '<') !== false || strpos($commentaire, '>') !== false) {
                $errorMsg = "Les chevrons < et > ne sont pas autorises dans le commentaire.";
                header("Location: ../Vue/reservation.php?error=" . urlencode($errorMsg));
            }

            $row = $cReservation->Verifresaexiste();


            if ($row['count'] > 0) {
                $errorMsg = "Une reservation existe deja pour cette periode. Veuillez recommencer une reservation";
                header("Location: ../Vue/reservation.php?error=" . urlencode($errorMsg));
                
            } else {
                $cReservation->editReservation();
            }
            
        } elseif ($action == 'closepopup') {
            $editGymData = null;

            header("Location: ../Vue/reservation.php");

        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['gym_id'])) {
        $selectedGymId = (int) $_GET['gym_id'];
    }
    if (!empty($_GET['error'])) {
        $error = $_GET['error'];
    }

}


$cReservation->SetUserId($cUtilisateur->GetUserId());
$dt = $cReservation->getUserReservations();

$finalRows = [];
while ($row = $dt->fetch_assoc()) {
    if ($row['statut'] == 1) {
        $row['statut'] = "../icons/enregistre.png";
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
require_once '../Vue/reservation.php';

?>
