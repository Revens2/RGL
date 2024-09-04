<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($query = $conn->prepare("SELECT Id_Utilisateur, isClient, isAdmin FROM utilisateur WHERE Email = ? AND Mot_de_Passe = ?")) {
        $hashed_password = md5($password); 
        $query->bind_param("ss", $email, $password);
        $query->execute();
        $query->store_result();

        $query->bind_result($id_personne, $isClient, $isAdmin);
        while ($query->fetch()) {
            echo "Id_Utilisateur: $id_personne, isClient: $isClient, isAdmin: $isAdmin";
        }
        
        if ($query->num_rows == 1) {
            $query->bind_result($user_id, $isClient, $isAdmin);
            $query->fetch();
            $_SESSION['user_id'] = $user_id;
            $_SESSION['client'] = $isClient;
            $_SESSION['admin'] = $isAdmin;
            
            header("Location: main.php");
        } else {
            echo "Mauvais nom d'utilisateur ou mot de passe.";
        }

        $query->close();
    } else {
        echo "Error in preparing statement: " . $conn->error;
    }
}
?>

