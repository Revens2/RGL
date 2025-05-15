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
            header("Location: ../Vue/main.php");
            exit();
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
                header("Location: ../Vue/main.php");
                exit();
            }

        } elseif ($action == 'add_reservation') {
            $cReservation->setGymId(isset($_POST['gymeid']) ? (int) $_POST['gymeid'] : null);
            $cReservation->setUserId(isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null);
            $cReservation->setSportId(isset($_POST['sport']) ? (int) $_POST['sport'] : null);
            $cReservation->setDateDebut(isset($_POST['datedebut']) ? $_POST['datedebut'] : null);
            $cReservation->setDateFin(isset($_POST['datefin']) ? $_POST['datefin'] : null);
            $cReservation->setCommentaire(isset($_POST['commentaire']) ? $_POST['commentaire'] : '');
            $startTime = strtotime(isset($_POST['datedebut']) ? $_POST['datedebut'] : null);
            $endTime = strtotime(isset($_POST['datefin']) ? $_POST['datefin'] : null);

            if ($startTime === false || $endTime === false) {
                $errorMsg = "Les dates saisies ne sont pas valides.";
                header("Location: ../Vue/main.php?error=" . urlencode($errorMsg));
                exit;
            }

            if ($endTime <= $startTime) {
                $errorMsg = "La date de fin doit être strictement postérieure à la date de début.";
                header("Location: ../Vue/main.php?error=" . urlencode($errorMsg));
                exit;
            }

            if (strpos($commentaire, '<') !== false || strpos($commentaire, '>') !== false) {
                $errorMsg = "Les chevrons < et > ne sont pas autorisés dans le commentaire.";
                header("Location: ../Vue/main.php?error=" . urlencode($errorMsg) );
                exit;
            }

            $row = $cReservation->Verifresaexiste(); 
            

            if ($row['count'] > 0) {
                $errorMsg = "Une réservation existe déjà pour cette période. Veuillez recommencer une reservation";
                header("Location: ../Vue/main.php?error=" . urlencode($errorMsg) );
                exit;
            }else {
                $cReservation->AjoutReservation();
                header("Location: ../Vue/main.php");
                exit();
            }

        } elseif ($action == 'add_sport') {
            $cSport->setName(isset($_POST['sport_nom']) ? $_POST['sport_nom'] : null);
            $cSport->setCollec(isset($_POST['collectif']) ? 1 : 0);
            $cSport->AjoutSport();
            header("Location: ../Vue/main.php");
        } elseif ($action == 'add_gymnase') {
            $cGymnase->setGymname(isset($_POST['nom']) ? $_POST['nom'] : null);
            $cGymnase->setLatitude(isset($_POST['latitude']) ? $_POST['latitude'] : null);
            $cGymnase->setLongitude(isset($_POST['longitude']) ? $_POST['longitude'] : null);
            $cGymnase->setAdresse(isset($_POST['tbadresse']) ? $_POST['tbadresse'] : null);
            $cGymnase->setVille(isset($_POST['ville']) ? $_POST['ville'] : null);
            $cGymnase->setZip(isset($_POST['zip']) ? (int) $_POST['zip'] : null);

            $cGymnase->AjoutGym();
            echo "Le gymnase a bien été ajouté !";
            header("Location: ../Vue/main.php");
            exit();

        } elseif ($action == 'delete') {

            $cGymnase->setGymId(isset($_POST['Id_Gymnase']) ? (int) $_POST['Id_Gymnase'] : null);
            $cGymnase->SuppGym();
            echo "Le gymnase a bien été supprimé !";
            header("Location: ../Vue/main.php");
            exit();
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

require_once '../Vue/main.php';
?>