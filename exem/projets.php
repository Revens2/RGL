<?php
session_start();
include 'db_connect.php';

if ($_SESSION['role'] != 'chef de projet') {
    header("Location: login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $budget = $_POST['budget'];

    $query = $conn->prepare("INSERT INTO Projet (Nom_Projet, Description, Date_Debut, Date_Fin, Budget, ID_Chef) VALUES (?, ?, ?, ?, ?, ?)");
    $query->bind_param('ssssdi', $nom, $description, $date_debut, $date_fin, $budget, $_SESSION['user_id']);
    $query->execute();
}

$projets = $conn->query("SELECT * FROM Projet WHERE ID_Chef = ".$_SESSION['user_id']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestion des Projets</title>
</head>
<body>
    <h1>Projets</h1>
    <form method="POST" action="">
        Nom: <input type="text" name="nom" required><br>
        Description: <textarea name="description" required></textarea><br>
        Date Début: <input type="date" name="date_debut" required><br>
        Date Fin: <input type="date" name="date_fin" required><br>
        Budget: <input type="number" name="budget" required><br>
        <input type="submit" value="Ajouter">
    </form>
    
    <h2>Liste des Projets</h2>
    <ul>
        <?php while ($projet = $projets->fetch_assoc()): ?>
            <li><?php echo $projet['Nom_Projet']; ?> - <?php echo $projet['Description']; ?></li>
        <?php endwhile; ?>
    </ul>
</body>
<body>
     <ul>
            <a href="dashboard.html">Retour</a>
            
     </ul>
</body> 
</html>
