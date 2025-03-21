<?php
session_start();
require_once '../Model/cbdd.php';
include '../Model/cUtilisateur.php';
$connect = new cUtilisateur();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $connect->setMail($_POST['email']) ;
    $connect->setMdp($_POST['password']);

    if ($connect->login()) {
        header("Location: ../Vue/main.php");
    } else {
        echo "Mauvais nom d'utilisateur ou mot de passe.";
    }

 }


    

?>
