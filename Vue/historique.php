<?php require_once '../Controleur/historique.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Projets</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php require_once 'menu.php'; ?>

    <div class="container">

        <h1>Historique de vos Réservations</h1>
        <table>
            <tr>
                <th>Sport</th>
                <th>Gymnase</th>
                <th>Commentaire</th>
                <th>Date de Début</th>
                <th>Date de Fin</th>
            </tr>
            <?php foreach ($historique as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['Nom_du_sport']); ?></td>
                    <td><?= htmlspecialchars($row['nom']); ?></td>
                    <td><?= htmlspecialchars($row['Commentaire']); ?></td>
                    <td><?= htmlspecialchars($row['Date_debut']); ?></td>
                    <td><?= htmlspecialchars($row['Date_fin']); ?></td>
                    
                </tr>
            <?php endforeach; ?>
        </table>

     <?php require_once '../Vue/footer.php'; ?>
    </div>
</body>
</html>
