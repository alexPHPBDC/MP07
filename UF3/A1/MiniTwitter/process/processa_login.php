<?php
session_start();
include_once "../utils.php";
include_once "../funcions.php";

if(!empty($_POST)){
$user = obtenirVariablePOST("user");
$password = obtenirVariablePOST("password");

$usuari = new Usuari();
$usuari->user = $user;
$usuari->password = $password;
if(comprovarUsuari($usuari)){
    $userId = getUserId($usuari->user);
    $usuari->id = $userId;
    $_SESSION['user'] = $usuari->user;
    $_SESSION['userId'] = $usuari->id;
    header("Location: ../inici.php", true, 302);
    exit();
}else{
    header("Location: ../formularis/formulari_login.php?error=wrongCredentials", true, 303);
    exit();
}
   
}

//How did u end up here? go back to index
header("Location: ../formularis/formulari_login.php?error=Forbidden", true, 303);




?>