<?php
session_start();
include '../db_connect.php';
include '../Model/cConnected.php';


$auth = new cConnected();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($auth->login($email, $password)) {
        header("Location: ../Controleur/main.php");
    } else {
        echo "Mauvais nom d'utilisateur ou mot de passe.";
    }
}


$auth->closeConnection();
?>
