<?php
require_once '../Model/cbdd.php';
require_once '../Model/cUtilisateur.php';
$connect = new cUtilisateur();
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $connect->setNom($_POST['name']);
    $connect->setPrenom($_POST['prenom']);
    $connect->setBirth($_POST['birth']);
    $connect->setTel($_POST['tel']);
    $connect->setAdresse($_POST['adresse']);
    $connect->setVille($_POST['Ville']);
    $connect->setZip($_POST['zip']);
    $connect->setMail($_POST['email']);
    $connect->setMdp($_POST['mdp']);

    $query = $connect->VerifAccount();
    if ($query->num_rows==0){
        $connect->AJoutAccount();
        header("Location: ../Vue/login.php");
    }else{
        echo "Email déjà existant.";
    }
    

}
?>
