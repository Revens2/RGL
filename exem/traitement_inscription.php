<?php
$user ="root";
$passwd="root";
$machine="localhost";
$connect = mysqli_connect($machine,$user,$passwd)
 or die ('Echec de connexion');

$bd = "gestionprojetsfacturation";
mysqli_select_db($bd,$connect) 
or die('Echec lors de la selection de la base') ;

mysqli_close($connect) ;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nom = htmlspecialchars($_POST['nom']);

    $email = htmlspecialchars($_POST['email']);

    $password = htmlspecialchars($_POST['pass']);

    $confirm_password = htmlspecialchars($_POST['confirm_password']);

    $date_naissance = htmlspecialchars($_POST['date_naissance']);

    $genre = htmlspecialchars($_POST['genre']);
    $ville = htmlspecialchars($_POST['ville']);
    $pays = htmlspecialchars($_POST['pays']);


}