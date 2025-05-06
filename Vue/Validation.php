<?php require_once '../Controleur/validation.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Gestion des Projets</title>
    <link rel="stylesheet" href="../css/style.css">
       
</head>

<body>
 <?php require_once '../Vue/menu.php'; ?>
    <div class="container">
        <h1>Liste des Validations </h1>

        <table>
            <tr>
                <th>Statut</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Sport</th>
                <th>Gymnase</th>
                <th>Date de Début</th>
                <th>Date de Fin</th>
                <th style="width: 228px;">Actions</th>
            </tr>
            <?php foreach ($finalRows as $row): ?>
            <tr>
                 <td><img src="<?= htmlspecialchars($row['statut']) ?>" alt="Icône de statut" /> </td>
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
                        <input type="submit" class="btn btn-edit" style="width : 101px;"   value="Modifier">
                    </form>
                    <form method="POST" action="validation.php" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?');" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="Id_reservation" value="<?php echo $row['Id_reservation']; ?>">
                        <input type="submit" class="btn btn-delete" value="Supprimer">
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>


        
    <?php if (!empty($editGymData)): ?>
     <div id="paraModal" class="modal" style="display: block;">
        <div class="modal-content">
        <form method="POST" action="../Controleur/validation.php" style="float:right;">
      <input type="hidden" name="action" value="closepopup">
      <button type="submit" class="close2">&times;</button>
    </form>

          <h2>Modifier la Réservation</h2>
          <form method="POST" action="validation.php">
            <input type="hidden" name="action" value="saveedit">
            <input type="hidden" name="Id_reservation" value="<?= ($_POST['Id_reservation'] ?? $editGymData['Id_reservation'] ?? '') ?>">

            <label for="ddlvalid">Validation :</label>
            <select id="ddlvalid" name="ddlvalid">
              <option value="1" <?= ($editGymData['statut'] == 1) ? 'selected' : ''; ?>>
                Sélectionner un statut
              </option>
              <option value="2" <?= ($editGymData['statut'] == 2) ? 'selected' : ''; ?>>
                Valider
              </option>
              <option value="3" <?= ($editGymData['statut'] == 3) ? 'selected' : ''; ?>>
                En attente d'action
              </option>
              <option value="4" <?= ($editGymData['statut'] == 4) ? 'selected' : ''; ?>>
                Refuser
              </option>
            </select>

            <label for="gymNameField">Gymnase :</label>
            <input 
              type="text" 
              id="gymNameField" 
              name="gymname" 
              value="<?= htmlspecialchars($editGymData['Nom'] ?? '') ?>"
              readonly="readonly"
            ><br><br>

            <label for="sport">Sport :</label>
            <input 
              type="text" 
              id="sport" 
              name="sport" 
              value="<?= htmlspecialchars($editGymData['Nom_du_sport'] ?? '') ?>"
              readonly="readonly"
            ><br><br>

            <label for="datedebut">Date de début :</label>
            <input 
              type="datetime-local" 
              id="datedebut" 
              name="datedebut"
              value="<?= htmlspecialchars($editGymData['Date_debut'] ?? '') ?>"
              readonly="readonly"
            ><br><br>

            <label for="datefin">Date de fin :</label>
            <input 
              type="datetime-local" 
              id="datefin" 
              name="datefin"
              value="<?= htmlspecialchars($editGymData['Date_fin'] ?? '') ?>"
              readonly="readonly"
            ><br><br>

            <label for="commentaire">Commentaire :</label>
            <input 
              type="text" 
              id="commentaire" 
              name="commentaire"
              value="<?= htmlspecialchars($editGymData['Commentaire'] ?? '') ?>"
              readonly="readonly"
            ><br><br>

            <input type="submit" value="Confirmer la réservation">
          </form>
        </div>
      </div>
    </div>
    <?php endif; ?>

   <?php require_once '../Vue/footer.php'; ?>

</body>
</html>
