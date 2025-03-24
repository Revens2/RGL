<?php
session_start();
require_once '../Model/cbdd.php';
require_once '../Model/cUtilisateur.php';
require_once '../Model/cReservation.php';
include '../Model/cGymnase.php';
include '../Model/cSport.php';

$cUtilisateur = new cUtilisateur();
$cReservation = new cReservation();
$cGymnase = new cGymnase();
$cSport = new cSport();

$editGymData = null;
$showEditModal = false;
$allSports = [];
$associatedSports = [];
$gymid = null;
$gymData = null;
$showResaModal = false;
$error = null;

if (isset($_GET['showResaModal']) && $_GET['showResaModal'] == '1') {
    $showResaModal = true;
}
if (!empty($_GET['error'])) {
    $error = $_GET['error'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action == 'edit_gymnase') {
            $cGymnase->SetGymId($_POST['gymid']);
            $result = $cGymnase->GetOneGym();
            if ($result->num_rows > 0) {
                $editGymData = $result->fetch_assoc();
                $showEditModal = true;
            }

            $result = $cSport->GetAllSport();
            while ($row = $result->fetch_assoc()) {
                $allSports[] = $row;
            }

            $result = $cGymnase->GetOneGym_sport();
            while ($row = $result->fetch_assoc()) {
                $associatedSports[] = $row['Id_Sport'];
            }

        } elseif ($action == 'parametre') {
            $cGymnase->setGymId(isset($_POST['paragymid']) ? (int) $_POST['paragymid'] : null);
            $cGymnase->setGymname(isset($_POST['paranom']) ? $_POST['paranom'] : null);
            $cGymnase->setLatitude(isset($_POST['paralatitude']) ? (float) $_POST['paralatitude'] : null);
            $cGymnase->setLongitude(isset($_POST['paralongitude']) ? (float) $_POST['paralongitude'] : null);
            $cGymnase->setAdresse(isset($_POST['tbparaadresse']) ? $_POST['tbparaadresse'] : null);
            $cGymnase->setVille(isset($_POST['paraville']) ? $_POST['paraville'] : null);
            $cGymnase->setZip(isset($_POST['parazip']) ? (int) $_POST['parazip'] : null);

            $result = $cGymnase->MAJParaGym();

            if ($result) {
                $cGymnase->SuppOneGym_sport();
                if (isset($_POST['sports']) && is_array($_POST['sports'])) {
                    foreach ($_POST['sports'] as $sport_id) {
                        $cGymnase->setSportId($sport_id);
                        $cGymnase->AddGym_sport();
                    }
                }

                echo "Le gymnase a bien été mis à jour !";
                header("Location: main.php");
                exit();
            } 

        } elseif ($action == 'add_reservation') {
            $cGymnase->setGymId(isset($_POST['gymeid']) ? (int) $_POST['gymeid'] : null);
            $cGymnase->setUserId(isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null);
            $cGymnase->setSportId(isset($_POST['sport']) ? (int) $_POST['sport'] : null);
            $cGymnase->setDateDebut(isset($_POST['datedebut']) ? $_POST['datedebut'] : null);
            $cGymnase->setDateFin(isset($_POST['datefin']) ? $_POST['datefin'] : null);
            $cGymnase->setCommentaire(isset($_POST['commentaire']) ? $_POST['commentaire'] : '');
            $startTime = strtotime($dateDebut);
            $endTime = strtotime($dateFin);

            if ($startTime === false || $endTime === false) {
                $errorMsg = "Les dates saisies ne sont pas valides.";
                header("Location: main.php?error=" . urlencode($errorMsg) . "&showResaModal=1");
                exit;
            }

            if ($endTime <= $startTime) {
                $errorMsg = "La date de fin doit être strictement postérieure à la date de début.";
                header("Location: main.php?error=" . urlencode($errorMsg) . "&showResaModal=1");
                exit;
            }

            if (strpos($commentaire, '<') !== false || strpos($commentaire, '>') !== false) {
                $errorMsg = "Les chevrons < et > ne sont pas autorisés dans le commentaire.";
                header("Location: main.php?error=" . urlencode($errorMsg) . "&showResaModal=1");
                exit;
            }

            $cReservation->AjoutReservation();

        } elseif ($action == 'add_sport') {
            $cSport->setName(isset($_POST['sport_nom']) ? $_POST['sport_nom'] : null);
            $cSport->setCollec(isset($_POST['collectif']) ? 1 : 0);
            $cSport->AjoutSport();
        }
    }
}

$result = $cGymnase->Getgym();

$gymnases = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $gymId = $row['Id_Gymnase'];
        $gymnases[] = [
            'idgym' => $gymId,
            'name' => $row['Nom'],
            'latitude' => $row['Coordonnees_latitude'],
            'longitude' => $row['Coordonnees_longitude'],
            'address' => $row['Adresse'],
            'Ville' => $row['Ville'],
            'Zip' => $row['Zip'],
            'sports' => []
        ];
    }
}

$result = $cGymnase->GetGym_sport();
$gymnaseSports = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $gymId = $row['Id_Gymnase'];
        $sportId = $row['Id_Sport'];
        $gymnaseSports[$gymId][] = $sportId;
    }
}

foreach ($gymnases as &$gymnase) {
    $gymId = $gymnase['idgym'];
    $gymnase['sports'] = isset($gymnaseSports[$gymId]) ? $gymnaseSports[$gymId] : [];
}

$result = $cSport->GetSport();

$sports = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $sports[$row['Id_Sport']] = $row['Nom_du_sport'];
    }
}

?>