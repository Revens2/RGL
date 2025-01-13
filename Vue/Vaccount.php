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
            <!-- CHAMP NOM -->
            <label for="nom">Nom :</label>
            <input
                type="text"
                id="nom"
                name="nom"
                value="<?= htmlspecialchars($userData['Nom'] ?? '') ?>"
                required
                pattern="[A-Za-zÀ-ÿ\s'’\-]{2,50}"
                title="2 à 50 caractères : lettres (accents autorisés), espaces, apostrophes, tirets."
            >

            <!-- CHAMP PRÉNOM -->
            <label for="prenom">Prénom :</label>
            <input
                type="text"
                id="prenom"
                name="prenom"
                value="<?= htmlspecialchars($userData['Prenom'] ?? '') ?>"
                required
                pattern="[A-Za-zÀ-ÿ\s'’\-]{2,50}"
                title="2 à 50 caractères : lettres (accents autorisés), espaces, apostrophes, tirets."
            >

            <!-- DATE DE NAISSANCE -->
            <label for="date_naissance">Date de naissance :</label>
            <input
                type="date"
                id="date_naissance"
                name="date_naissance"
                value="<?= htmlspecialchars($userData['Date_de_naissance'] ?? '') ?>"

            >

            <!-- TÉLÉPHONE -->
            <label for="telephone">Numéro de téléphone :</label>
            <input
                type="tel"
                id="telephone"
                name="telephone"
                value="<?= htmlspecialchars($userData['Numero_de_telephone'] ?? '') ?>"
           
                pattern="^\+?[0-9]{10,14}$"
                title="10 à 14 chiffres (optionnellement précédés d’un +)."
            >

            <!-- EMAIL -->
            <label for="email">Email :</label>
            <input
                type="email"
                id="email"
                name="email"
                value="<?= htmlspecialchars($userData['Email'] ?? '') ?>"
                required
            >

            <!-- ADRESSE -->
            <label for="adresse">Adresse :</label>
            <input
                type="text"
                id="adresse"
                name="adresse"
                value="<?= htmlspecialchars($userData['Adresse'] ?? '') ?>"
                required
                pattern="[^<>]{5,100}"
                title="5 à 100 caractères, sans chevrons < ni >."
            >

            <!-- CODE POSTAL -->
            <label for="zip">Code Postal :</label>
            <input
                type="text"
                id="zip"
                name="zip"
                value="<?= htmlspecialchars($userData['Zip'] ?? '') ?>"
                required
                pattern="[0-9]{4,5}"
                title="4 ou 5 chiffres."
            >

            <!-- VILLE -->
            <label for="ville">Ville :</label>
            <input
                type="text"
                id="ville"
                name="ville"
                value="<?= htmlspecialchars($userData['Ville'] ?? '') ?>"
                required
                pattern="[A-Za-zÀ-ÿ\s'’\-]{2,50}"
                title="2 à 50 caractères : lettres (accents autorisés), espaces, tirets, apostrophes."
            >

            <input type="submit" value="Mettre à jour">
        </form>
    </div>
</body>
</html>
