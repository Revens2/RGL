<?php
session_start();
include '../db_connect.php';
include '../Model/cConnected.php';


$auth = new cConnected();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $auth->setMail($_POST['email']) ;
    $auth->setmdp($_POST['password']) ;

    if ($auth->login()) {
        header("Location: ../Controleur/main.php");
    } else {
        echo "Mauvais nom d'utilisateur ou mot de passe.";
    }

 }


    

$auth->closeConnection();
?>
