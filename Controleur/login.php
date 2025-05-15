<?php
session_start();
require_once '../Model/cUtilisateur.php';
$connect = new cUtilisateur();
$errormsg = false;



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $connect->setMail($_POST['email']) ;
    $connect->setMdp($_POST['password']);

    if ($connect->login()) {
        $errormsg = false;
        header("Location: ../Vue/main.php");
       
    } else {
        $errormsg = true;
        
    }

 }
require_once '../Vue/login.php';

    

?>
