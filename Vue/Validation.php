<?php include '../Controleur/validation.php';

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
 <?php include '../Vue/menu.php'; ?>
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
                        <input type="submit" class="btn btn-edit" style="width : 101px;"   value="Modifier">
                    </form>
                    <form method="POST" action="validation.php" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?');" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="Id_reservation" value="<?php echo $row['Id_reservation']; ?>">
                        <input type="submit" class="btn btn-delete" value="Supprimer">
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>


        
    <?php if (!empty($editGymData)): ?>
     
          <span class="close">&times;</span>
          <h2>Modifier la Réservation</h2>
          <form method="POST" action="validation.php">
            <input type="hidden" name="action" value="saveedit">
            <input type="hidden" name="Id_reservation" value="<?= htmlspecialchars($resaid) ?>">

            <label for="ddlvalid">Validation :</label>
            <select id="ddlvalid" name="ddlvalid">
              <option value="1" <?= ($editGymData['statut'] == 1) ? 'selected' : ''; ?>>
                Nouvelle réservation
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
            ><br><br>

            <label for="sport">Sport :</label>
            <input 
              type="text" 
              id="sport" 
              name="sport" 
              value="<?= htmlspecialchars($editGymData['Nom_du_sport'] ?? '') ?>"
            ><br><br>

            <label for="datedebut">Date de début :</label>
            <input 
              type="datetime-local" 
              id="datedebut" 
              name="datedebut"
              value="<?= htmlspecialchars($editGymData['Date_debut'] ?? '') ?>"
            ><br><br>

            <label for="datefin">Date de fin :</label>
            <input 
              type="datetime-local" 
              id="datefin" 
              name="datefin"
              value="<?= htmlspecialchars($editGymData['Date_fin'] ?? '') ?>"
            ><br><br>

            <label for="commentaire">Commentaire :</label>
            <input 
              type="text" 
              id="commentaire" 
              name="commentaire"
              value="<?= htmlspecialchars($editGymData['Commentaire'] ?? '') ?>"
            ><br><br>

            <input type="submit" value="Confirmer la réservation">
          </form>
        </div>
     
    <?php endif; ?>
  
</body>

</html>
