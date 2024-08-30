<?php
include 'db_connect.php';
session_start();


if ($_SESSION['role'] !== 'chef de projet') {
    header("Location: login.php");
    exit();
  }


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['action']) && $_POST['action'] === 'create') {
          $id_personne = $_POST['id_personne'];
        $id_departement = $_POST['id_departement'];

        $stmt = $conn->prepare("INSERT INTO Employe (ID_Personne, ID_Departement) VALUES (?, ?)");
       
        $stmt->bind_param("ii", $id_personne, $id_departement);
        $stmt->execute();
        $stmt->close();


    } elseif (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $id_employe = $_POST['id_employe'];
         $stmt = $conn->prepare("DELETE FROM Employe WHERE ID_Employe = ?");


             $stmt->bind_param("i", $id_employe);

        $stmt->execute();
        $stmt->close();
    }

    header("Location: employes.php");
 
    
    exit();
}


$personnes_result = $conn->query("SELECT ID_Personne, Nom, Prenom FROM Personne");
$departements_result = $conn->query("SELECT ID_Departement, Nom_Departement FROM Departement");
$employes_result = $conn->prepare("SELECT e.ID_Employe, p.Nom, p.Prenom, d.Nom_Departement 
                                    FROM Employe e
                                    JOIN Personne p ON e.ID_Personne = p.ID_Personne
                                    JOIN Departement d ON e.ID_Departement = d.ID_Departement");
$employes_result->execute();
$result = $employes_result->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Employés</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Gestion des Employés</h1>

        <form method="POST" action="employes.php" class="employee-form">
            <input type="hidden" name="action" value="create">
            <div class="form-group">
                <label for="id_personne">Sélectionner la personne:</label>
                <select id="id_personne" name="id_personne" required>
                    <?php
                    while ($row = $personnes_result->fetch_assoc()) {
                        echo "<option value='" . $row['ID_Personne'] . "'>" . $row['Nom'] . " " . $row['Prenom'] . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="id_departement">Sélectionner le département:</label>
                <select id="id_departement" name="id_departement" required>
                   <?php
                   while ($row = $departements_result->fetch_assoc()) {
                       echo "<option value='" . $row['ID_Departement'] . "'>" . $row['Nom_Departement'] . "</option>";
                   }
                   ?>
                </select>
            </div>

            <div class="form-group input-submit">
                <input type="submit" value="Ajouter l'employé">
            </div>
        </form>

        <h2>Liste des Employés</h2>
    <table>
    <tr>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Département</th>
        <th style="width: 250px;">Actions</th>
    </tr>
    <?php
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['Nom'] . "</td>";
        echo "<td>" . $row['Prenom'] . "</td>";
        echo "<td>" . $row['Nom_Departement'] . "</td>";
        echo "<td style='display: flex; justify-content: space-between;'>";
        echo "<a href='modifier_employe.php?id=" . $row['ID_Employe'] . "' class='btn btn-edit'>Modifier</a>";
        echo "<form method='POST' action='employes.php' style='display:inline; margin-left: 5px;'>";
        echo "<input type='hidden' name='id_employe' value='" . $row['ID_Employe'] . "'>";
        echo "<input type='hidden' name='action' value='delete'>";
        echo "<button type='submit' class='btn btn-delete'>Supprimer</button>";
        echo "</form>";
        echo "</td>";
        echo "</tr>";
    }
    ?>
    </table>

    </div>
</body>
<body>        
        <ul>
            <li><a href="dashboard.html" style="text-align: left;" class="btn">Retour</a></li>
        </ul>
    
</body>    
</html>

<?php
$conn->close();
?>
