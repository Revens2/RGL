<?php

session_start();
include 'db_connect.php';
include 'Class/cConnected.php';
$connect = new cConnected($conn);


$stmt = $conn->prepare("select r.Id_reservation, r.Date_debut, r.Date_fin, s.Nom_du_sport, r.statut, g.nom, u.Nom,Prenom
from reservation r
join sport s on s.Id_Sport=r.Id_Sport 
join gymnase g on g.Id_Gymnase=r.Id_Gymnase
join utilisateur u on u.Id_Utilisateur=r.Id_Utilisateur");

$resaid = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action == 'resaedit') {
            $resaid=  $_POST['Id_reservation'];
            
            $stmt = $conn->prepare("select r.Date_debut, r.Date_fin, r.Commentaire, g.Nom, s.Nom_du_sport
            from reservation r
            join gymnase g on g.Id_Gymnase=r.Id_Gymnase
            join sport s on s.Id_Sport=r.Id_Sport
            where Id_reservation= ?");

            $stmt->bind_param("i", $resaid);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $editGymData = $result->fetch_assoc();
                $showEditModal = true;
            }
            $stmt->close();
        }

    }

}


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



        <h2>Liste de Réservations</h2>
        <table>
            <tr>
                <th>Statut</th>
                <th>Nom</th>
                <th>Prenom</th>
                <th>Sport</th>
                <th>Gymanse</th>
                <th>Date de Début</th>
                <th>Date de Fin</th>
                <th>Actions</th>
            </tr>
             <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['statut']); ?></td>
            <td><?php echo htmlspecialchars($row['Nom']); ?></td>
            <td><?php echo htmlspecialchars($row['Prenom']); ?></td>
            <td><?php echo htmlspecialchars($row['Nom_du_sport']); ?></td>
            <td><?php echo htmlspecialchars($row['nom']); ?></td>
            <td><?php echo htmlspecialchars($row['Date_debut']); ?></td>
            <td><?php echo htmlspecialchars($row['Date_fin']); ?></td>
            <td>

                <form method="POST" action="projets.php" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?');">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id_projet" value="<?php echo $row['Id_reservation']; ?>">
                    <input type="submit" class="btn btn-delete" value="Supprimer">
                </form>
                <form method="POST">
                    <button id="btnOpensportModal" class="btn btn-edit">Modifier</button>
                    <input type="hidden" name="id_projet" value="<?php echo $row['Id_reservation']; ?>">
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
        </table>
    </div>
      <div id="resaModal" class="modal">
        <div class="modal-content">
            <span id="closeResaModal" class="close">&times;</span>
            <h2>Réserver le gymnase</h2>
            <form method="POST" action="main.php">
                <input type="hidden" name="action" value="resaedit">
                <input type="hidden" id="gymeidField" name="gymeid">
                <label for="gymNameField">Gymnase :</label>
                <input type="text" id="gymNameField" name="gymname" readonly><br><br>

                <label for="datedebut">Date de début :</label>
                <input type="datetime-local" id="datedebut" name="datedebut" required><br><br>

                <label for="datefin">Date de fin :</label>
                <input type="datetime-local" id="datefin" name="datefin" required><br><br>

                <label for="sports">Sports disponibles :</label><br>
                <div id="sportsContainer"></div>

                  <label for="commentaire">Commentaire :</label>
                <input type="text" id="commentaire" name="commentaire" required><br><br>

                <input type="submit" value="Confirmer la réservation">
            </form>
        </div> 
    </div>
    <script>
        var btnOpensportModal = document.getElementById("btnOpensportModal");

            btnOpensportModal.onclick = function() {
                sportModal.style.display = "block";
            }
    </script>
</body>
</html>


<?php
$stmt->close();
$conn->close();
?>
