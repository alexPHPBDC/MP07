<?php
include_once("../funcions.php");
include_once("../utils/pintar.php");

if (isset($_POST['self']) && isset($_POST['idUsuari']) && $_POST['idUsuari'] != "selectBuit") {
    $usuariId = $_POST['idUsuari'];
    $usuariAux = getUser($usuariId);
    $usuari = getUser($_POST['self']);
    pintarMenuSeguir($usuari, $usuariAux);
    $tweets = getMissatgesOrderByDate($usuariAux);
    $frase = "<p>Aquest usuari encara no té cap tweet, anima'l a crear-ne un 😊";
    pintarTweets($usuari, $tweets, $frase,$usuariAux);
}









?>