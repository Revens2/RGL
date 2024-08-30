<?php
include 'db_connect.php';
session_start();

if ($_SESSION['role'] !== 'chef de projet') {
    header("Location: login.php");
    exit();
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && $_POST['action'] === 'create') {
        $date_facture = $_POST['date_facture'];
        $montant_total = $_POST['montant_total'];
        $id_commande = $_POST['id_commande'];

        $stmt = $conn->prepare("INSERT INTO Facture (Date_Facture, Montant_Total, ID_Commande) VALUES (?, ?, ?)");
        $stmt->bind_param("sdi", $date_facture, $montant_total, $id_commande);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $id_facture = $_POST['id_facture'];
        $stmt = $conn->prepare("DELETE FROM Facture WHERE ID_Facture = ?");
        $stmt->bind_param("i", $id_facture);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: factures.php");
    exit();
}

$stmt = $conn->prepare("SELECT f.ID_Facture, f.Date_Facture, f.Montant_Total, c.Date_Commande, cl.Nom_Client 
                        FROM Facture f
                        JOIN Commande c ON f.ID_Commande = c.ID_Commande
                        JOIN Client cl ON c.ID_Client = cl.ID_Client");
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Factures</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Gestion des Factures</h1>


        <form method="POST" action="factures.php" class="facture-form">
            <input type="hidden" name="action" value="create">
            <div class="form-group">
                <label for="date_facture">Date de la facture:</label>
                <input type="date" name="date_facture" id="date_facture" required>
            </div>
            <div class="form-group">
                <label for="montant_total">Montant total:</label>
                <input type="number" name="montant_total" id="montant_total" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="id_commande">Sélectionner la commande:</label>
                <select name="id_commande" id="id_commande" required>
                    <?php
                    $commandes = $conn->query("SELECT ID_Commande, Date_Commande FROM Commande");
                    while ($commande = $commandes->fetch_assoc()):
                        ?>
                    <option value="<?php echo $commande['ID_Commande']; ?>">
                        <?php echo htmlspecialchars($commande['Date_Commande']); ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group input-submit">
                <input type="submit" value="Générer la facture" class="btn">
            </div>
        </form>

        <h2>Liste des Factures</h2>
        <table>
            <thead>
                <tr>
                    <th>Date de la Facture</th>
                    <th>Montant Total</th>
                    <th>Commande</th>
                    <th>Client</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['Date_Facture']); ?></td>
                    <td><?php echo htmlspecialchars($row['Montant_Total']); ?> </td>
                    <td><?php echo htmlspecialchars($row['Date_Commande']); ?></td>
                   <td><?php echo htmlspecialchars($row['Nom_Client']); ?></td>
                    <td>
                      
                        <form method="POST" action="factures.php" style="text-align: center;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette facture ?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id_facture" value="<?php echo $row['ID_Facture']; ?>">
                            <button type="submit" class="btn btn-delete">Supprimer</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        
    </div>
</body>
<body>
    <ul>
            <li><a href="dashboard.html" class="btn">Retour</a></li>
        </ul>
</body>
</html>


<?php
$stmt->close();
$conn->close();
?>
