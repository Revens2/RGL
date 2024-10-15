<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date_commande = $_POST['date_commande'];
    $id_client = $_POST['id_client'];

    $query = $conn->prepare("INSERT INTO Commande (Date_Commande, ID_Client) VALUES (?, ?)");
    $query->bind_param('si', $date_commande, $id_client);
    $query->execute();
    $query->close();
}

$commandes = $conn->query("SELECT Commande.ID_Commande, Commande.Date_Commande, Client.Nom_Client FROM Commande INNER JOIN Client ON Commande.ID_Client = Client.ID_Client");
$clients = $conn->query("SELECT * FROM Client");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestion des Commandes</title>
</head>
<body>
    <h1>Commandes</h1>
    <form method="POST" action="">
        Date: <input type="date" name="date_commande" required><br>
        Client: 
        <select name="id_client" required>
            <?php while ($client = $clients->fetch_assoc()): ?>
                <option value="<?php echo $client['ID_Client']; ?>"><?php echo $client['Nom_Client']; ?></option>
            <?php endwhile; ?>
        </select><br>
        <input type="submit" value="Ajouter">
    </form>
    
    <h2>Liste des Commandes</h2>
    <ul>
        <?php while ($commande = $commandes->fetch_assoc()): ?>
            <li><?php echo $commande['Date_Commande']; ?> - <?php echo $commande['Nom_Client']; ?></li>
        <?php endwhile; ?>
    </ul>
</body>
<body>
     <ul>
            <a href="dashboard.html">Retour</a>
            
     </ul>
</body> 
</html>
