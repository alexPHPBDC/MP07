<?php
session_start();
include_once("../utils.php");
include_once("../funcions.php");

if(!empty($_POST)){
$user = obtenirVariablePOST("user");
$password = obtenirVariablePOST("password");
$name = obtenirVariablePOST("name");

if(!usuariExists($user,$name)){
    $usuari = new Usuari();
    $usuari->user = $user;
    $usuari->name = $name;
    $usuari->password = $password;
    $usuari->id = addUsuari($usuari);
    $_SESSION['user'] = $usuari->user;
    $_SESSION['userId'] = $usuari->id;
    header("Location: ../inici.php", true, 302);
    exit();
}else{
    header("Location: ../formularis/formulari_registre.php?error=AlreadyRegistered", true, 303);
    exit();
}

}

//How did u end up here? go back to signup
header("Location: ../formularis/formulari_registre.php?error=wtf", true, 303);



?>