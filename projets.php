<?php
include 'db_connect.php';
session_start();



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && $_POST['action'] === 'create') {
        $nom_projet = $_POST['nom_projet'];
        $description = $_POST['description'];
        $date_debut = $_POST['date_debut'];
        $date_fin = $_POST['date_fin'];
        $budget = $_POST['budget'];
        $id_chef = $_SESSION['user_id'];

        $stmt = $conn->prepare("INSERT INTO Projet (Nom_Projet, Description, Date_Debut, Date_Fin, Budget, ID_Chef) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssdi", $nom_projet, $description, $date_debut, $date_fin, $budget, $id_chef);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $id_projet = $_POST['id_projet'];
        $stmt = $conn->prepare("DELETE FROM Projet WHERE ID_Projet = ?");
        $stmt->bind_param("i", $id_projet);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: projets.php");
    exit();
}


$stmt = $conn->prepare("SELECT p.ID_Projet, p.Nom_Projet, p.Description, p.Date_Debut, p.Date_Fin, p.Budget, pe.Nom, pe.Prenom 
                        FROM Projet p
                        JOIN Personne pe ON p.ID_Chef = pe.ID_Personne");
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
    <div class="container">
        <h1>Gestion des Projets</h1>

        <form method="POST" action="projets.php" class="project-form">
            <div class="form-group">
                <label for="nom_projet">Nom du projet:</label>
                <input type="text" id="nom_projet" name="nom_projet" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" required></textarea>
            </div>

            <div class="form-group">
                <label for="date_debut">Date de début:</label>
                <input type="date" id="date_debut" name="date_debut" required>
            </div>

            <div class="form-group">
                <label for="date_fin">Date de fin:</label>
                <input type="date" id="date_fin" name="date_fin" required>
            </div>

            <div class="form-group">
                <label for="budget">Budget:</label>
                <input type="number" id="budget" name="budget" step="0.01" required>
            </div>



            <div class="form-group" style="grid-column: span 2; text-align: center;">
                <input type="submit" value="Créer le projet" class="project-submit-btn" style="grid-column: span 2; text-align: center;">
            </div>
        </form>

        <h2>Liste des Projets</h2>
        <table>
            <tr>
                <th>Nom du Projet</th>
                <th>Description</th>
                <th>Date de Début</th>
                <th>Date de Fin</th>
                <th>Budget</th>
                <th>Chef de Projet</th>
                <th>Actions</th>
            </tr>
             <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['Nom_Projet']); ?></td>
            <td><?php echo htmlspecialchars($row['Description']); ?></td>
            <td><?php echo htmlspecialchars($row['Date_Debut']); ?></td>
            <td><?php echo htmlspecialchars($row['Date_Fin']); ?></td>
            <td><?php echo htmlspecialchars($row['Budget']); ?></td>
            <td><?php echo htmlspecialchars($row['Prenom'] . ' ' . $row['Nom']); ?></td>
            <td>

                <form method="POST" action="projets.php" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?');">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id_projet" value="<?php echo $row['ID_Projet']; ?>">
                    <input type="submit" class="btn btn-delete" value="Supprimer">
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
        </table>
    </div>
</body>
<body>        
        <ul>
            <li><a href="dashboard.html" style="text-align: left;" class="btn">Retour</a></li>
        </ul>
    
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
