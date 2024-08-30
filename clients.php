<?php
session_start();
include 'db_connect.php';

if ($_SESSION['role'] !== 'chef de projet') {
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
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Clients</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Gestion des Clients</h1>
        
        <form method="POST" action="" class="client-form">
            <div class="form-group">
                <label for="nom_client">Nom:</label>
                <input type="text" name="nom_client" id="nom_client" required>
            </div>
            <div class="form-group">
                <label for="adresse">Adresse:</label>
                <textarea name="adresse" id="adresse" required></textarea>
            </div>
            <div class="form-group">
                <label for="email_client">Email:</label>
                <input type="email" name="email_client" id="email_client" required>
            </div>
            <div class="form-group input-submit">
                <input type="submit" value="Ajouter" class="btn">
            </div>
        </form>

        <h2>Liste des Clients</h2>
        <ul class="client-list">
            <?php while ($client = $clients->fetch_assoc()): ?>
                <li><?php echo $client['Nom_Client']; ?> - <?php echo $client['Adresse']; ?> - <?php echo $client['Email_Client']; ?></li>
            <?php endwhile; ?>
        </ul>
        </div>
</body>
<body>        
        <ul>
            <li><a href="dashboard.html" style="text-align: left;" class="btn">Retour</a></li>
        </ul>
    
</body>
</html>
