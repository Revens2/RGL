<?php
include 'db_connect.php'; 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['name'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $mdp = $_POST['mdp'];
    $role = $_POST['role'];

       $hashed_mdp = password_hash($mdp, PASSWORD_DEFAULT);

     $stmt = $conn->prepare("INSERT INTO Personne (Nom, Prenom, Email, Mot_de_Passe, Role) VALUES (?, ?, ?, ?, ?)");



     $stmt->bind_param("sssss", $nom, $prenom, $email, $hashed_mdp, $role);

       if ($stmt->execute()) {
            header("Location: login.html");
        exit();
    } else {
        echo "Erreur : " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}
?>
