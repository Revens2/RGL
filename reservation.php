<?php

session_start();
include 'db_connect.php';
include 'Class/cConnected.php';


$connect = new cConnected($conn);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        if ($action == 'edit_gymnase') {

            $resa = $_POST['Id_reservation'];

            $stmt = $conn->prepare("update reservation set statut = 0 where Id_reservation = ?");
            $stmt->bind_param("i", $resa);
            $stmt->execute();
            $stmt->close();
        }

    }

}


$userid = $_SESSION['user_id'];
$stmt = $conn->prepare("select r.Id_reservation, r.Date_debut, r.Date_fin, s.Nom_du_sport, r.statut, g.nom 
from reservation r
join sport s on s.Id_Sport=r.Id_Sport 
join gymnase g on g.Id_Gymnase=r.Id_Gymnase
where Id_Utilisateur= ? ");

$stmt->bind_param("i", $userid);
$stmt->execute();
$result = $stmt->get_result();
?>


<!DOCTYPE html>
<style>
    form-group2{
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
                <th>Gymanse</th>
                <th>Date de Début</th>
                <th>Date de Fin</th>
                <th>Actions</th>
            </tr>
             <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['statut']); ?></td>
            <td><?php echo htmlspecialchars($row['Nom_du_sport']); ?></td>
            <td><?php echo htmlspecialchars($row['nom']); ?></td>
            <td><?php echo htmlspecialchars($row['Date_debut']); ?></td>
            <td><?php echo htmlspecialchars($row['Date_fin']); ?></td>

            <td>
                
                <form method="POST" action="reservation.php" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette reservation ?');">
                    <input type="hidden" name="action" value="supp">
                    <input type="hidden" name="id_projet" value="<?php echo $row['Id_reservation']; ?>">
                    <input type="submit" class="btn btn-delete" value="Supprimer">
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
        </table>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
