<?php
session_start();
include_once "../funcions.php";
include_once "../utils.php";
/* Dona la benviguda a l’usuari mostrant un missatge de “Benvingut al MiniTwitter NOM_USUARI” i 
mostra tots els seus tuits ordenats de més actual a més antic.*/

if (!isset($_SESSION['user'])) {
    //How did u end up here? go back to login
    header("Location: ../formularis/formulari_login.php?error=Forbidden", true, 303);
    exit();
}

if (isset($_SESSION['user']) && isset($_SESSION['userId'])) {
    $user = $_SESSION['user'];
    $userId = $_SESSION['userId'];

    $tweet = obtenirVariablePOST("tweetText");

    if (addMissatge($userId, $tweet)) {
        setcookie("tweetMessage", "Tweet creat correctament");
        header("Location: ../inici.php", true, 302);
        exit();
    } else {
        setcookie("tweetMessage", "Error en crear el tweet");
        header("Location: inici.php", true, 303);
        exit();
    }
}


//How did u end up here? go back to login
header("Location: ../formularis/formulari_login.php?error=Forbidden", true, 303);
