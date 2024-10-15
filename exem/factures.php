<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date_facture = $_POST['date_facture'];
    $montant = $_POST['montant'];
    $id_commande = $_POST['id_commande'];

    $query = $conn->prepare("INSERT INTO Facture (Date_Facture, Montant, ID_Commande) VALUES (?, ?, ?)");
    $query->bind_param('sdi', $date_facture, $montant, $id_commande);
    $query->execute();
    $query->close();
}

$factures = $conn->query("SELECT Facture.ID_Facture, Facture.Date_Facture, Facture.Montant, Commande.Date_Commande, Client.Nom_Client FROM Facture INNER JOIN Commande ON Facture.ID_Commande = Commande.ID_Commande INNER JOIN Client ON Commande.ID_Client = Client.ID_Client");
$commandes = $conn->query("SELECT Commande.ID_Commande, Commande.Date_Commande, Client.Nom_Client FROM Commande INNER JOIN Client ON Commande.ID_Client = Client.ID_Client");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestion des Factures</title>
</head>
<body>
    <h1>Factures</h1>
    <form method="POST" action="">
        Date: <input type="date" name="date_facture" required><br>
        Montant: <input type="number" step="0.01" name="montant" required><br>
        Commande: 
        <select name="id_commande" required>
            <?php while ($commande = $commandes->fetch_assoc()): ?>
                <option value="<?php echo $commande['ID_Commande']; ?>"><?php echo $commande['Date_Commande']; ?> - <?php echo $commande['Nom_Client']; ?></option>
            <?php endwhile; ?>
        </select><br>
        <input type="submit" value="Ajouter">
    </form>
    
    <h2>Liste des Factures</h2>
    <ul>
        <?php while ($facture = $factures->fetch_assoc()): ?>
            <li><?php echo $facture['Date_Facture']; ?> - <?php echo $facture['Montant']; ?> - <?php echo $facture['Nom_Client']; ?></li>
        <?php endwhile; ?>
    </ul>
</body>
<body>
     <ul>
            <a href="dashboard.html">Retour</a>
            
     </ul>
</body> 
</html>
