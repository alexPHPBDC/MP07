<?php
include_once("../funcions.php");
include_once("../utils/pintar.php");

if (isset($_POST['idUsuariToFollow']) && isset($_POST['self'])) {
    $usuariAux = getUser($_POST['idUsuariToFollow']);
    $usuari = getUser($_POST['self']);
    followUser($_POST['self'], $_POST['idUsuariToFollow']);
    $usuariAux = getUser($_POST['idUsuariToFollow']);
    $usuari = getUser($_POST['self']);
    pintarMenuSeguir($usuari, $usuariAux);
    $tweets = getMissatgesOrderByDate($usuariAux);
    $frase = "<p>Aquest usuari encara no té cap tweet, anima'l a crear-ne un 😊";
    pintarTweets($usuari, $tweets, $frase);
}else if(isset($_POST['idUsuariToUnfollow']) && isset($_POST['self'])){
    $usuariAux = getUser($_POST['idUsuariToUnfollow']);
    $usuari = getUser($_POST['self']);
    unfollowUser($_POST['self'], $_POST['idUsuariToUnfollow']);
    $usuariAux = getUser($_POST['idUsuariToUnfollow']);
    $usuari = getUser($_POST['self']);
    pintarMenuSeguir($usuari, $usuariAux);
    $tweets = getMissatgesOrderByDate($usuariAux);
    $frase = "<p>Aquest usuari encara no té cap tweet, anima'l a crear-ne un 😊";
    pintarTweets($usuari, $tweets, $frase);
}


?>