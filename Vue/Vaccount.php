<?php
include '../Controleur/Caccount.php';

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Mon Compte</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .account-container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .account-container h2 {
            text-align: center;
            color: #333;
        }

        .account-container label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
        }

        .account-container input[type="text"],
        .account-container input[type="email"],
        .account-container input[type="tel"],
        .account-container input[type="date"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .account-container input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            display: block;
            margin: 0 auto;
        }

        .account-container input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
<?php include '../Vue/menu.php'; ?>
    <div class="account-container">
        <h2>Mon Compte</h2>
        <form method="POST" action="../Controleur/traitement_inscription.php">
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($userData['Nom'] ?? '') ?>" required>
            <label for="prenom">Prénom :</label>
            <input type="text" id="prenom" name="prenom" value="<?php echo isset($userData['Prenom']) ? $userData['Prenom'] : ''; ?>" required>

            <label for="date_naissance">Date de naissance :</label>
            <input type="date" id="date_naissance" name="date_naissance" value="<?php echo isset($userData['Date_de_naissance']) ? $userData['Date_de_naissance'] : ''; ?>" required>

            <label for="telephone">Numéro de téléphone :</label>
            <input type="tel" id="telephone" name="telephone" value="<?php echo isset($userData['Numero_de_telephone']) ? $userData['Numero_de_telephone'] : ''; ?>" required>

            <label for="email">Email :</label>
            <input type="email" id="email" name="email" value="<?php echo isset($userData['Email']) ? $userData['Email'] : ''; ?>" required>

            <label for="adresse">Adresse :</label>
            <input type="text" id="adresse" name="adresse" value="<?php echo isset($userData['Adresse']) ? $userData['Adresse'] : ''; ?>" required>

            <label for="zip">Code Postal :</label>
            <input type="text" id="zip" name="zip" value="<?php echo isset($userData['Zip']) ? $userData['Zip'] : ''; ?>" required>

            <label for="ville">Ville :</label>
            <input type="text" id="ville" name="ville" value="<?php echo isset($userData['Ville']) ? $userData['Ville'] : ''; ?>" required>

            <input type="submit" value="Mettre à jour">
        </form>
    </div>
</body>

</html>
