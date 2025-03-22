<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}   

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom_client = $_POST['nom_client'];
    $adresse = $_POST['adresse'];
    $email_client = $_POST['email_client'];

    $query = $conn->prepare("INSERT INTO Client (Nom_Client, Adresse, Email_Client) VALUES (?, ?, ?)");
    $query->bind_param('sss', $nom_client, $adresse, $email_client);
    $query->execute();
    $query->close();
}

$clients = $conn->query("SELECT * FROM Client");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestion des Clients</title>
</head>
<body>
    <h1>Clients</h1>
    <form method="POST" action="">
        Nom: <input type="text" name="nom_client" required><br>
        Adresse: <textarea name="adresse" required></textarea><br>
        Email: <input type="email" name="email_client" required><br>
        <input type="submit" value="Ajouter">
    </form>
    
    <h2>Liste des Clients</h2>
    <ul>
        <?php while ($client = $clients->fetch_assoc()): ?>
            <li><?php echo $client['Nom_Client']; ?> - <?php echo $client['Adresse']; ?> - <?php echo $client['Email_Client']; ?></li>
        <?php endwhile; ?>
    </ul>
</body>
<body>
     <ul>
            <a href="dashboard.html">Retour</a>
            
     </ul>
</body>       
</html>
