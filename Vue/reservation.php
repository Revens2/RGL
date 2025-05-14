<?php require_once '../Controleur/reservation.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gestion des Projets</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php require_once 'menu.php'; ?>
   

    <div class="container">
        <h1>Mes Réservations</h1>
         
        <?php if (!empty($error)): ?>
                    <p style="color:red;">
                        <?php echo htmlspecialchars($error); ?>
                    </p>
                <?php endif; ?>
        <table>
            <tr>
                <th>Statut</th>
                <th>Sport</th>
                <th>Gymnase</th>
                <th>Date de Début</th>
                <th>Date de Fin</th>
                <th style="width: 228px;">Actions</th>
            </tr>
            <?php foreach ($finalRows as $row): ?>
                <tr>
                    <td><img src="<?= htmlspecialchars($row['statut']) ?>" alt="Icône de statut" /> </td>
                    <td><?= htmlspecialchars($row['Nom_du_sport']); ?></td>
                    <td><?= htmlspecialchars($row['nom']); ?></td>
                    <td><?= htmlspecialchars($row['Date_debut']); ?></td>
                    <td><?= htmlspecialchars($row['Date_fin']); ?></td>
                    <td>
                        <form method="POST" action="reservation.php"  style="display:inline;">
                            <input type="hidden" name="action" value="openresaedit">
                            <input type="hidden" name="Id_reservation" value="<?= $row['Id_reservation']; ?>">
                            <input type="submit" class="btn btn-edit" style="width : 101px;"   value="Modifier">
                        </form>
                        <form method="POST" action="reservation.php" onsubmit="return confirm('Confirmer la suppression ?');"  style="display:inline;">
                            <input type="hidden" name="action" value="supp">
                            <input type="hidden" name="Id_reservation" value="<?= $row['Id_reservation']; ?>">
                            <input type="submit" class="btn btn-delete" value="Supprimer">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <?php if ($editGymData): ?>
         <div id="paraModal" class="modal" style="display: block;">
        <div class="modal-content">
        <form method="POST" action="../Controleur/reservation.php" style="float:right;">
      <input type="hidden" name="action" value="closepopup">
      <button type="submit" class="close2">&times;</button>
    </form>
            <h2>Modifier la Réservation</h2>
            <form method="POST" action="reservation.php">
                <input type="hidden" name="action" value="saveedit">
                <input type="hidden" name="Id_reservation" value="<?php echo htmlspecialchars($_POST['Id_reservation'] ?? $editGymData['Id_reservation'] ?? ''); ?>">

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

                <input type="submit" name="saveedit" value="Confirmer la modification">
            </form>
            </div>
      </div>
    </div>
        <?php endif; ?>
    
      <?php require_once '../Vue/footer.php'; ?>

</body>
</html>
