<?php
session_start();
include 'db_connect.php';
include 'Class/cConnected.php';
$connect = new cConnected($conn);

// Préparation de la requête principale pour afficher les réservations
$stmt = $conn->prepare("SELECT r.Id_reservation, r.Date_debut, r.Date_fin, s.Nom_du_sport, r.statut, g.nom, u.Nom, u.Prenom
FROM reservation r
JOIN sport s ON s.Id_Sport = r.Id_Sport
JOIN gymnase g ON g.Id_Gymnase = r.Id_Gymnase
JOIN utilisateur u ON u.Id_Utilisateur = r.Id_Utilisateur");
$stmt->execute();
$result = $stmt->get_result();
// Gestion de l'action de modification
$editGymData = null;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action == 'resaedit') {
            // Récupérer les informations pour pré-remplir le formulaire de modification
            $resaid = $_POST['Id_reservation'];

            $stmt = $conn->prepare("SELECT r.Date_debut, r.Date_fin, r.Commentaire, g.Nom, s.Nom_du_sport
                                    FROM reservation r
                                    JOIN gymnase g ON g.Id_Gymnase = r.Id_Gymnase
                                    JOIN sport s ON s.Id_Sport = r.Id_Sport
                                    WHERE Id_reservation = ?");
            $stmt->bind_param("i", $resaid);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $editGymData = $result->fetch_assoc();
            }
            $stmt->close();
        } elseif ($action == 'saveedit') {
            // Sauvegarder les modifications
            $resaid = $_POST['Id_reservation'];
            $datedebut = $_POST['datedebut'];
            $datefin = $_POST['datefin'];
            $commentaire = $_POST['commentaire'];

            $updateStmt = $conn->prepare("UPDATE reservation SET Date_debut = ?, Date_fin = ?, Commentaire = ? WHERE Id_reservation = ?");
            $updateStmt->bind_param("sssi", $datedebut, $datefin, $commentaire, $resaid);
            $updateStmt->execute();
            $updateStmt->close();

            exit();
        }
    }
}


?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Gestion des Projets</title>
    <link rel="stylesheet" href="css/style.css">
        <style>
        .modal {
            display: flex;
            align-items: center;
            justify-content: center;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
        }

        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 400px;
            max-width: 90%;
        }

        .close {
            color: #333;
            float: right;
            font-size: 24px;
            cursor: pointer;
        }

        .modal-content h2 {
            margin-top: 0;
            font-size: 24px;
            color: #333;
        }

        .modal-content form label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
        }

        .modal-content form input[type="text"],
        .modal-content form input[type="datetime-local"] {
            width: calc(100% - 20px);
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .modal-content form input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .modal-content form input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
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
                <th>Prénom</th>
                <th>Sport</th>
                <th>Gymnase</th>
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
                    <form method="POST" action="validation.php" style="display:inline;">
                        <input type="hidden" name="action" value="resaedit">
                        <input type="hidden" name="Id_reservation" value="<?php echo $row['Id_reservation']; ?>">
                        <input type="submit" class="btn btn-edit" value="Modifier">
                    </form>
                    <form method="POST" action="validation.php" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?');" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id_projet" value="<?php echo $row['Id_reservation']; ?>">
                        <input type="submit" class="btn btn-delete" value="Supprimer">
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>

        <?php if ($editGymData): ?>
        <h2>Modifier la Réservation</h2>
        <form method="POST" action="projets.php">
            <input type="hidden" name="action" value="saveedit">
            <input type="hidden" name="Id_reservation" value="<?php echo htmlspecialchars($resaid); ?>">

            <label for="gymNameField">Gymnase :</label>
            <input type="text" id="gymNameField" name="gymname" value="<?php echo htmlspecialchars($editGymData['Nom']); ?>" readonly><br><br>

            <label for="datedebut">Date de début :</label>
            <input type="datetime-local" id="datedebut" name="datedebut" value="<?php echo htmlspecialchars($editGymData['Date_debut']); ?>" required><br><br>

            <label for="datefin">Date de fin :</label>
            <input type="datetime-local" id="datefin" name="datefin" value="<?php echo htmlspecialchars($editGymData['Date_fin']); ?>" required><br><br>

            <label for="commentaire">Commentaire :</label>
            <input type="text" id="commentaire" name="commentaire" value="<?php echo htmlspecialchars($editGymData['Commentaire']); ?>" required><br><br>

            <input type="submit" value="Confirmer la réservation">
        </form>
        <?php endif; ?>
    </div>
</body>

</html>

<?php
$stmt->close();
$conn->close();
?>
