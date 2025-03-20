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
            $gymid = $_POST['gymid'];

            $result = $gym->GetOneGym($gymid);
            if ($result->num_rows > 0) {
                $editGymData = $result->fetch_assoc();
                $showEditModal = true;
            }

            $result = $sport->GetAllSport();
            while ($row = $result->fetch_assoc()) {
                $allSports[] = $row;
            }

            $result = $gym->GetOneGym_sport($gymid);
            while ($row = $result->fetch_assoc()) {
                $associatedSports[] = $row['Id_Sport'];
            }

        } elseif ($action == 'parametre') {
            $gymId = isset($_POST['paragymid']) ? (int) $_POST['paragymid'] : null;
            $gymname = isset($_POST['paranom']) ? $_POST['paranom'] : null;
            $latitude = isset($_POST['paralatitude']) ? (float) $_POST['paralatitude'] : null;
            $longitude = isset($_POST['paralongitude']) ? (float) $_POST['paralongitude'] : null;
            $adresse = isset($_POST['tbparaadresse']) ? $_POST['tbparaadresse'] : null;
            $ville = isset($_POST['paraville']) ? $_POST['paraville'] : null;
            $zip = isset($_POST['parazip']) ? (int) $_POST['parazip'] : null;

            $result = $gym->UpdateParaGym($gymId, $gymname, $latitude, $longitude, $adresse, $ville, $zip);

            if ($result) {
                $gym->DelOneGym_sport($gymId);
                if (isset($_POST['sports']) && is_array($_POST['sports'])) {
                    foreach ($_POST['sports'] as $sport_id) {
                        $gym->InsertGym_sport($gymId, $sport_id);
                    }
                }

                echo "Le gymnase a bien été mis à jour !";
                header("Location: main.php");
                exit();
            } 

        } elseif ($action == 'add_reservation') {
            $gymId = isset($_POST['gymeid']) ? (int) $_POST['gymeid'] : null;
            $userId = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
            $sportId = isset($_POST['sport']) ? (int) $_POST['sport'] : null;
            $dateDebut = isset($_POST['datedebut']) ? $_POST['datedebut'] : null;
            $dateFin = isset($_POST['datefin']) ? $_POST['datefin'] : null;
            $commentaire = isset($_POST['commentaire']) ? $_POST['commentaire'] : '';

            $startTime = strtotime($dateDebut);
            $endTime = strtotime($dateFin);

            // Vérif : dates valides ?
            if ($startTime === false || $endTime === false) {
                $errorMsg = "Les dates saisies ne sont pas valides.";
                // On redirige en ré‐ouvrant la popup
                header("Location: main.php?error=" . urlencode($errorMsg) . "&showResaModal=1");
                exit;
            }
            // Vérif : date fin > date début ?
            if ($endTime <= $startTime) {
                $errorMsg = "La date de fin doit être strictement postérieure à la date de début.";
                header("Location: main.php?error=" . urlencode($errorMsg) . "&showResaModal=1");
                exit;
            }
            // Vérif : pas de < ni >
            if (strpos($commentaire, '<') !== false || strpos($commentaire, '>') !== false) {
                $errorMsg = "Les chevrons < et > ne sont pas autorisés dans le commentaire.";
                header("Location: main.php?error=" . urlencode($errorMsg) . "&showResaModal=1");
                exit;
            }

            $reserv->addReservation($gymId, $userId, $sportId, $dateDebut, $dateFin, $commentaire);

        } elseif ($action == 'add_sport') {
            $name = isset($_POST['sport_nom']) ? $_POST['sport_nom'] : null;
            $collec = isset($_POST['collectif']) ? 1 : 0;

            $sport->AddSport($name, $collec);
        }
    }
}

$result = $gym->Getgym();

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

$result = $gym->GetGym_sport();
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

$result = $sport->GetSport();

$sports = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $sports[$row['Id_Sport']] = $row['Nom_du_sport'];
    }
}

$conn->close();
include '../Vue/main.php';
?>