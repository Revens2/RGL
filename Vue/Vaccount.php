<?php
require '../Controleur/Caccount.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Compte</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
</head>
<body>
    <?php require_once '../Vue/menu.php'; ?>

    <div class="account-container">
        <h2>Mon Compte</h2>
        <form method="POST" action="../Controleur/Caccount.php">
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

    <?php require_once '../Vue/footer.php'; ?>

</body>
</html>
