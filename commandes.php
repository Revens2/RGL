<?php
include 'db_connect.php';
session_start();

if ($_SESSION['role'] !== 'chef de projet') {
    header("Location: login.php");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && $_POST['action'] === 'create') {
        $date_commande = $_POST['date_commande'];
        $id_client = $_POST['id_client'];

        $stmt = $conn->prepare("INSERT INTO Commande (Date_Commande, ID_Client) VALUES (?, ?)");
        $stmt->bind_param("si", $date_commande, $id_client);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $id_commande = $_POST['id_commande'];
        $stmt = $conn->prepare("DELETE FROM Commande WHERE ID_Commande = ?");
        $stmt->bind_param("i", $id_commande);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: commandes.php");
    exit();
}


$stmt = $conn->prepare("SELECT c.ID_Commande, c.Date_Commande, cl.Nom_Client 
                        FROM Commande c
                        JOIN Client cl ON c.ID_Client = cl.ID_Client");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Commandes</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Gestion des Commandes</h1>

        <form method="POST" action="commandes.php" class="commande-form">
            <input type="hidden" name="action" value="create">
            <div class="form-group">
                <label for="date_commande">Date de la commande:</label>
                <input type="date" name="date_commande" id="date_commande" required>
            </div>
            <div class="form-group">
                <label for="id_client">Sélectionner le client:</label>
                <select name="id_client" id="id_client" required>
                    <?php
                    $clients = $conn->query("SELECT ID_Client, Nom_Client FROM Client");
                    while ($client = $clients->fetch_assoc()):
                        ?>
                    <option value="<?php echo $client['ID_Client']; ?>">
                        <?php echo htmlspecialchars($client['Nom_Client']); ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group input-submit">
                <input type="submit" value="Ajouter la commande" class="btn">
            </div>
        </form>

        <h2>Liste des Commandes</h2>
        <table>
            <thead>
                <tr>
                    <th>Date de la Commande</th>
                    <th>Client</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['Date_Commande']); ?></td>
                    <td><?php echo htmlspecialchars($row['Nom_Client']); ?></td>
                    <td>
                        <form  style="text-align: center;" method="POST" action="commandes.php" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette commande ?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id_commande" value="<?php echo $row['ID_Commande']; ?>">
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
