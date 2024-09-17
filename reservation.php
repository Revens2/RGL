<?php
include 'db_connect.php';
session_start();



$stmt = $conn->prepare("SELECT FROM Projet p
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
