<?php
session_start();
require_once '../Model/cbdd.php';
include '../Model/cUtilisateur.php';
$connect = new cUtilisateur();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $connect->setNom($_POST['nom']);
    $connect->setPrenom($_POST['prenom']);
    $connect->setBirth($_POST['date_naissance']);
    $connect->setTel($_POST['telephone']);
    $connect->setAdresse($_POST['adresse']);
    $connect->setVille($_POST['ville']);
    $connect->setZip($_POST['zip']);
    $connect->setMail($_POST['email']);


    $connect->ModifAccount();
}
$userid = $_SESSION['user_id'];
 $connect->SetUserId($userid);
 $userData = $connect->account();

require_once '../Vue/Vaccount.php';
?>
