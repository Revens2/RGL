<?php require_once '../Controleur/login.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <img src="/icons/RGL.png" alt="Logo">
            <h1>Connexion</h1>
        </header>
        <?php if ($errormsg):?>
        <div class="error-message">
            <p>Erreur de connexion, veuillez vérifier vos identifiants.</p>
        </div>
         <?php endif; ?>
            <form method="POST" action="../Controleur/login.php">
                <input type="email" name="email" placeholder="Adresse email" required><br>
                <input type="password" name="password" placeholder="Mot de passe" required><br>
                <input type="submit" value="Se connecter">
            </form>

            <a class="create-account" href="signup.php">Creer un compte utilisateur</a><?php require_once '../Vue/footer.php'; ?>
        </div>
</body>
</html>
