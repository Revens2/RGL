<?php

session_start();
include 'db_connect.php';
include 'Class/cConnected.php';
include 'Class/cReservation.php';

$connect = new cConnected($conn);
$reserv = new cReservation($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        if ($action == 'edit_gymnase') {

            $resa = isset($_POST['Id_reservation']) ? (int) $_POST['Id_reservation'] : null;
            $reservations = $reserv->cancelReservation($resa);
                
        }
    }
}

$userid = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;

$reservations = $reserv->getUserReservations($userid);
?>

<!DOCTYPE html>
<style>
    form-group2 {
        margin-bottom: 40px;
    }
</style>
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
                    <form method="POST" action="reservation.php" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette réservation ?');">
                        <input type="hidden" name="action" value="supp">
                        <input type="hidden" name="id_projet" value="<?php echo $row['Id_reservation']; ?>">
                        <input type="submit" class="btn btn-delete" value="Supprimer">
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>

</html>

<?php
$conn->close();
?>
