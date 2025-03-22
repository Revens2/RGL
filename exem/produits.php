<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom_produit = $_POST['nom_produit'];
    $prix = $_POST['prix'];
    $stock = $_POST['stock'];

    $query = $conn->prepare("INSERT INTO Produit (Nom_Produit, Prix, Stock) VALUES (?, ?, ?)");
    $query->bind_param('sdi', $nom_produit, $prix, $stock);
    $query->execute();
    $query->close();
}

$produits = $conn->query("SELECT * FROM Produit");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestion des Produits</title>
</head>
<body>
    <h1>Produits</h1>
    <form method="POST" action="">
        Nom: <input type="text" name="nom_produit" required><br>
        Prix: <input type="number" step="0.01" name="prix" required><br>
        Stock: <input type="number" name="stock" required><br>
        <input type="submit" value="Ajouter">
    </form>
    
    <h2>Liste des Produits</h2>
    <ul>
        <?php while ($produit = $produits->fetch_assoc()): ?>
            <li><?php echo $produit['Nom_Produit']; ?> - <?php echo $produit['Prix']; ?> - <?php echo $produit['Stock']; ?></li>
        <?php endwhile; ?>
    </ul>
</body>
<body>
     <ul>
            <a href="dashboard.html">Retour</a>
            
     </ul>
</body> 
</html>
