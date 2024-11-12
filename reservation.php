<?php
session_start();
include 'db_connect.php';
include 'Class/cConnected.php';
include 'Class/cReservation.php';
include 'Class/cGymnase.php';
include 'Class/cSport.php';

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
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['gym_id'])) {
        $selectedGymId = (int) $_GET['gym_id'];
    }
}

$userid = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
$reservations = $reserv->getUserReservations($userid);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Gestion des Projets</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php include 'menu.php'; ?>
    <div class="container">
        <h1>Mes Réservations</h1>

        <h2>Liste de mes Réservations</h2>
        <table>
            <tr>
                <th>Statut</th>
                <th>Sport</th>
                <th>Gymnase</th>
                <th>Date de Début</th>
                <th>Date de Fin</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($reservations as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['statut']); ?></td>
                <td><?php echo htmlspecialchars($row['Nom_du_sport']); ?></td>
                <td><?php echo htmlspecialchars($row['nom']); ?></td>
                <td><?php echo htmlspecialchars($row['Date_debut']); ?></td>
                <td><?php echo htmlspecialchars($row['Date_fin']); ?></td>
                <td>
                    <form method="POST" action="reservation.php" style="display:inline;">
                        <input type="hidden" name="action" value="openresaedit">
                        <input type="hidden" name="Id_reservation" value="<?php echo $row['Id_reservation']; ?>">
                        <input type="submit" class="btn btn-edit" value="Modifier">
                    </form>
                    <form method="POST" action="reservation.php" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette réservation ?');">
                        <input type="hidden" name="action" value="supp">
                        <input type="hidden" name="Id_reservation" value="<?php echo $row['Id_reservation']; ?>">
                        <input type="submit" class="btn btn-delete" value="Supprimer">
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

        <?php if ($editGymData): ?>
        <h2>Modifier la Réservation</h2>
        <form method="POST" action="reservation.php">
         
            <input type="hidden" name="action" value="saveedit">
            <input type="hidden" name="Id_reservation" value="<?php echo htmlspecialchars($resaid); ?>">

           
            <label for="gymSelect">Gymnase :</label>
            <?php
            $currentGymId = isset($selectedGymId) ? $selectedGymId : $editGymData['Id_Gymnase'];
            echo $gym->getddlgym($currentGymId);
            ?>

            <input type="submit" name="refresh" value="Valider le nouveau gymnase">
            <br />
             <br />
             <br />
       
            <label for="SportSelect">Sport :</label>
            <?php
            if (isset($_POST['sport_id'])) {
                $selectedSportId = (int) $_POST['sport_id'];
            } elseif (isset($editGymData['Id_Sport'])) {
                $selectedSportId = $editGymData['Id_Sport'];
            } else {
                $selectedSportId = null;
            }
            echo $sport->getddlsport($currentGymId, $selectedSportId);
            ?>

           
            <label for="datedebut">Date de début :</label>
            <input type="datetime-local" id="datedebut" name="datedebut" value="<?php echo htmlspecialchars($_POST['datedebut'] ?? $editGymData['Date_debut'] ?? ''); ?>"><br><br>

            <label for="datefin">Date de fin :</label>
            <input type="datetime-local" id="datefin" name="datefin" value="<?php echo htmlspecialchars($_POST['datefin'] ?? $editGymData['Date_fin'] ?? ''); ?>"><br><br>

            <label for="commentaire">Commentaire :</label>
            <input type="text" id="commentaire" name="commentaire" value="<?php echo htmlspecialchars($_POST['commentaire'] ?? $editGymData['Commentaire'] ?? ''); ?>"><br><br>

          
            <input type="submit" name="saveedit" value="Confirmer la réservation">
        </form>
        <?php endif; ?>
    </div>
</body>

</html>

<?php
$conn->close();
?>
