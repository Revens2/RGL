<?php
session_start();
include 'db_connect.php';
include 'Class/cConnected.php';
include 'Class/cReservation.php';

$connect = new cConnected($conn);
$reserv = new cReservation($conn);

$result = $reserv->getUserValidation();


$editGymData = null;


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action == 'resaedit') {
            $resaid = isset($_POST['Id_reservation']) ? (int) $_POST['Id_reservation'] : null;
            $editGymData = $reserv->getReservationDetails($resaid); 
        

        } elseif ($action == 'saveedit') {

            $valid = isset($_POST['ddlvalid']) ? (int) $_POST['ddlvalid'] : null;
            $resaid = isset($_POST['Id_reservation']) ? (int) $_POST['Id_reservation'] : null;
            $reserv->editReservation($valid,$resaid);

            header("Location: " . $_SERVER['PHP_SELF']);
            exit();

        
        } elseif ($action == 'delete') {

            
            $resaid = isset($_POST['Id_reservation']) ? (int) $_POST['Id_reservation'] : null;
            $reserv->cancelReservation($resaid);

            header("Location: " . $_SERVER['PHP_SELF']);
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
                        <input type="hidden" name="Id_reservation" value="<?php echo $row['Id_reservation']; ?>">
                        <input type="submit" class="btn btn-delete" value="Supprimer">
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>

      <?php if ($editGymData): ?>
    <h2>Modifier la Réservation</h2>
    <form method="POST" action="validation.php">
        <input type="hidden" name="action" value="saveedit">
        <input type="hidden" name="Id_reservation" value="<?php echo htmlspecialchars($resaid); ?>">
        <label for="ddlvalid">Validation :</label>
        
        <select id="ddlvalid" name="ddlvalid">
            <option value="2" <?php echo ($editGymData['statut'] == 2) ? 'selected' : ''; ?>>Valider</option>
            <option value="3" <?php echo ($editGymData['statut'] == 3) ? 'selected' : ''; ?>>En attente</option>
            <option value="4" <?php echo ($editGymData['statut'] == 4) ? 'selected' : ''; ?>>Refuser</option>
        </select>

        <label for="gymNameField">Gymnase :</label>
        <input type="text" id="gymNameField" name="gymname" value="<?php echo htmlspecialchars($editGymData['Nom']); ?>" readonly><br><br>

        <label for="sport">Sport :</label>
        <input type="text" id="sport" name="sport" value="<?php echo htmlspecialchars($editGymData['Nom_du_sport']); ?>" readonly><br><br>

        <label for="datedebut">Date de début :</label>
        <input type="datetime-local" id="datedebut" name="datedebut" value="<?php echo htmlspecialchars($editGymData['Date_debut']); ?>" readonly><br><br>

        <label for="datefin">Date de fin :</label>
        <input type="datetime-local" id="datefin" name="datefin" value="<?php echo htmlspecialchars($editGymData['Date_fin']); ?>" readonly><br><br>

        <label for="commentaire">Commentaire :</label>
        <input type="text" id="commentaire" name="commentaire" value="<?php echo htmlspecialchars($editGymData['Commentaire']); ?>" readonly><br><br>

        <input type="submit" value="Confirmer la réservation">
    </form>
<?php endif; ?>

    </div>
</body>

</html>
