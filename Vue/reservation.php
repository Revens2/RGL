<?php include '../Controleur/reservation.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Projets</title>
    <link rel="stylesheet" href="../css/style.css">
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
                    <td><?= htmlspecialchars($row['statut']); ?></td>
                    <td><?= htmlspecialchars($row['Nom_du_sport']); ?></td>
                    <td><?= htmlspecialchars($row['nom']); ?></td>
                    <td><?= htmlspecialchars($row['Date_debut']); ?></td>
                    <td><?= htmlspecialchars($row['Date_fin']); ?></td>
                    <td>
                        <form method="POST" action="reservation.php">
                            <input type="hidden" name="action" value="openresaedit">
                            <input type="hidden" name="Id_reservation" value="<?= $row['Id_reservation']; ?>">
                            <button  style="background : green;" type="submit">Modifier</button>
                        </form>
                        <form method="POST" action="reservation.php" onsubmit="return confirm('Confirmer la suppression ?');">
                            <input type="hidden" name="action" value="supp">
                            <input type="hidden" name="Id_reservation" value="<?= $row['Id_reservation']; ?>">
                            <button style="background : red;" type="submit">Supprimer</button>
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

                <!-- GYM -->
                <label for="gymSelect">Gymnase :</label>
                <?php
                $currentGymId = isset($selectedGymId) ? $selectedGymId : $editGymData['Id_Gymnase'];
                echo $gym->getddlgym($currentGymId);
                ?>
                <input type="submit" name="refresh" value="Valider le nouveau gymnase">
                <br><br><br>

                <!-- SPORT -->
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

                <!-- DATE DE DÉBUT -->
                <label for="datedebut">Date de début :</label>
                <input
                    type="datetime-local"
                    id="datedebut"
                    name="datedebut"
                    required
                    value="<?php echo htmlspecialchars($_POST['datedebut'] ?? $editGymData['Date_debut'] ?? ''); ?>"
                >
                <br><br>

                <!-- DATE DE FIN -->
                <label for="datefin">Date de fin :</label>
                <input
                    type="datetime-local"
                    id="datefin"
                    name="datefin"
                    required
                    value="<?php echo htmlspecialchars($_POST['datefin'] ?? $editGymData['Date_fin'] ?? ''); ?>"
                >
                <br><br>

                <!-- COMMENTAIRE (optionnel) -->
                <label for="commentaire">Commentaire :</label>
                <input
                    type="text"
                    id="commentaire"
                    name="commentaire"
                    value="<?php echo htmlspecialchars($_POST['commentaire'] ?? $editGymData['Commentaire'] ?? ''); ?>"
                    pattern="[^<>]*"
                    title="Les chevrons < et > ne sont pas autorisés."
                >
                <br><br>

                <input type="submit" name="saveedit" value="Confirmer la réservation">
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
