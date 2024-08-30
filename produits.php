<?php
include 'db_connect.php';
session_start();

if ($_SESSION['role'] !== 'chef de projet') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && $_POST['action'] === 'create_product') {
        $nom_produit = $_POST['nom_produit'];
        $description_produit = $_POST['description_produit'];
        $prix = $_POST['prix'];

        $stmt = $conn->prepare("INSERT INTO Produit (Nom_Produit, Description_Produit, Prix) VALUES (?, ?, ?)");
        $stmt->bind_param("ssd", $nom_produit, $description_produit, $prix);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['action']) && $_POST['action'] === 'create_category') {
        $nom_categorie = $_POST['nom_categorie'];

        $stmt = $conn->prepare("INSERT INTO Categorie (Nom_Categorie) VALUES (?)");
        $stmt->bind_param("s", $nom_categorie);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['action']) && $_POST['action'] === 'delete_product') {
        $id_produit = $_POST['id_produit'];
        $stmt = $conn->prepare("DELETE FROM Produit WHERE ID_Produit = ?");
        $stmt->bind_param("i", $id_produit);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['action']) && $_POST['action'] === 'delete_category') {
        $id_categorie = $_POST['id_categorie'];
        $stmt = $conn->prepare("DELETE FROM Categorie WHERE ID_Categorie = ?");
        $stmt->bind_param("i", $id_categorie);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: produits.php");
    exit();
}

$stmt_produits = $conn->prepare("SELECT ID_Produit, Nom_Produit, Description_Produit, Prix FROM Produit");
$stmt_produits->execute();
$result_produits = $stmt_produits->get_result();

$stmt_categories = $conn->prepare("SELECT ID_Categorie, Nom_Categorie FROM Categorie");
$stmt_categories->execute();
$result_categories = $stmt_categories->get_result();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Produits et Catégories</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Gestion des Produits</h1>
        <form method="POST" action="produits.php" class="produit-form">
            <input type="hidden" name="action" value="create_product">
            <div class="form-group">
                <label for="nom_produit">Nom du produit:</label>
                <input type="text" name="nom_produit" id="nom_produit" required>
            </div>
            <div class="form-group">
                <label for="description_produit">Description:</label>
                <textarea name="description_produit" id="description_produit" required></textarea>
            </div>
            <div class="form-group">
                <label for="prix">Prix:</label>
                <input type="number" name="prix" id="prix" step="0.01" required>
            </div>
            <div class="form-group input-submit">
                <input type="submit" value="Ajouter le produit" class="btn">
            </div>
        </form>

        <h2>Liste des Produits</h2>
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Prix</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result_produits->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['Nom_Produit']); ?></td>
                    <td><?php echo htmlspecialchars($row['Description_Produit']); ?></td>
                    <td><?php echo htmlspecialchars($row['Prix']); ?></td>
                    <td>
                        <form method="POST" style="text-align: center;" action="produits.php" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');">
                            <input type="hidden" name="action" value="delete_product">
                            <input type="hidden" name="id_produit" value="<?php echo $row['ID_Produit']; ?>">
                            <button type="submit" class="btn btn-delete">Supprimer</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <h1>Gestion des Catégories</h1>

        <form method="POST" action="produits.php" class="categorie-form">
            <input type="hidden" name="action" value="create_category">
            <div class="form-group">
                <label for="nom_categorie">Nom de la catégorie:</label>
                <input type="text" name="nom_categorie" id="nom_categorie" required>
            </div>
            <div class="form-group input-submit">
                <input type="submit" value="Ajouter la catégorie" class="btn">
            </div>
        </form>

        <h2>Liste des Catégories</h2>
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result_categories->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['Nom_Categorie']); ?></td>
                    <td>
                        <form method="POST" style="text-align: center;" action="produits.php" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?');">
                            <input type="hidden" name="action" value="delete_category">
                            <input type="hidden" name="id_categorie" value="<?php echo $row['ID_Categorie']; ?>">
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
$stmt_produits->close();
$stmt_categories->close();
$conn->close();
?>
