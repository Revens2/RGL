<?php
session_start();
require_once 'db_connect.php';

if ($_SESSION['role'] != 'chef de projet') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];
    $departement = $_POST['departement'];

    $query = $conn->prepare("INSERT INTO Personne (Nom, Prenom, Email, Mot_de_Passe, Role) VALUES (?, ?, ?, MD5(?), 'employé')");
    $query->bind_param('ssss', $nom, $prenom, $email, $mot_de_passe);
    $query->execute();
    $personne_id = $query->insert_id;

    $query = $conn->prepare("INSERT INTO Employe (ID_Personne, ID_Departement) VALUES (?, ?)");
    $query->bind_param('ii', $personne_id, $departement);
    $query->execute();

    $query->close();
}

$employes = $conn->query("SELECT Employe.ID_Employe, Personne.Nom, Personne.Prenom, Departement.Nom_Departement FROM Employe INNER JOIN Personne ON Employe.ID_Personne = Personne.ID_Personne INNER JOIN Departement ON Employe.ID_Departement = Departement.ID_Departement");
$departements = $conn->query("SELECT * FROM Departement");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestion des Employés</title>
</head>
<body>
    <h1>Employés</h1>
    <form method="POST" action="">
        Nom: <input type="text" name="nom" required><br>
        Prénom: <input type="text" name="prenom" required><br>
        Email: <input type="email" name="email" required><br>
        Mot de passe: <input type="password" name="mot_de_passe" required><br>
        Département: 
        <select name="departement" required>
            <?php while ($departement = $departements->fetch_assoc()): ?>
                <option value="<?php echo $departement['ID_Departement']; ?>"><?php echo $departement['Nom_Departement']; ?></option>
            <?php endwhile; ?>
        </select><br>
        <input type="submit" value="Ajouter">
    </form>
    
    <h2>Liste des Employés</h2>
    <ul>
        <?php while ($employe = $employes->fetch_assoc()): ?>
            <li><?php echo $employe['Nom']; ?> <?php echo $employe['Prenom']; ?> - <?php echo $employe['Nom_Departement']; ?></li>
        <?php endwhile; ?>
    </ul>
</body>
<body>
     <ul>
            <a href="dashboard.html">Retour</a>
            
     </ul>
</body> 
</html>
