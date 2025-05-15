<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un compte utilisateur</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <img src="/icons/RGL.png" alt="Logo">
            <h1>Créer un compte utilisateur</h1>
        </header>

        <form method="POST" action="../Controleur/traitement_inscription.php">
          <input type="text" name="name" placeholder="Nom complet" required><br>
            <input type="text" name="prenom" placeholder="Prenom" required><br>
            <input type="date" name="birth" placeholder="Date de naissance" required><br>
            <input type="number"  maxlength="10" name="tel" placeholder="Numero de téléphone" required><br>
            <input type="text" name="adresse" placeholder="Adresse" required><br>
            <input type="text" name="Ville" placeholder="Ville" required><br>
            <input type="number" name="zip" maxlength="5" placeholder="Code postale" required><br>
             <input type="email" name="email" placeholder="Adresse email" required><br>
            <input type="password" name="mdp" placeholder="Mot de passe" required><br>

            <input type="submit" value="Créer le compte">
        </form>
       <ul>
            <li><a href="../Vue/login.php" style="text-align: left;display:flex; width:90px;" class="btn">Retour</a></li>
      </ul>
         <?php require_once '../Vue/footer.php'; ?>
    </div>
     
</body>
</html>
