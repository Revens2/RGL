<?php
include 'db_connect.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['name'];
    $prenom = $_POST['prenom'];
    $birth = $_POST['birth'];
    $tel = $_POST['tel'];
    $adress = $_POST['adresse'];
    $ville = $_POST['Ville'];
    $zip = $_POST['zip'];
    $email = $_POST['email'];
    $mdp = $_POST['mdp'];
    $hashed = password_hash($mdp, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO `Utilisateur` (`Nom`, `Prenom`, `Date_de_naissance`, `Numero_de_telephone`, `Adresse`, `isClient`, `isAdmin`, `Email`, `Ville`, `Zip`, `Mot_de_Passe`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if ($stmt === false) {
        die('Erreur dans la préparation de la requête : ' . $conn->error);
    }

    $client = "1";
    $admin = "0";

    $stmt->bind_param("sssssssssss", $nom, $prenom, $birth, $tel, $adress, $client, $admin, $email, $ville, $zip, $hashed);

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
