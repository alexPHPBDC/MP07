<?php
session_start();
include_once "../funcions.php";

$frase = "<p>Aquest usuari encara no té cap tweet, anima'l a crear-ne un 😊";

if (isset($_POST['self']) && isset($_POST['like']) && isset($_POST['idAux'])) {
    likeTweet($_POST['self'], $_POST['like']);
    $usuariAux = getUser($_POST['idAux']);
    $usuari = getUser($_POST['self']);
    $tweets = getMissatgesOrderByDate($usuariAux);
    pintarTweets($usuari, $tweets, $frase);
    
}else if(isset($_POST['self']) && isset($_POST['dislike']) && isset($_POST['idAux'])){
    likeTweet($_POST['self'], $_POST['dislike']);
    $usuariAux = getUser($_POST['idAux']);
    $usuari = getUser($_POST['self']);
    $tweets = getMissatgesOrderByDate($usuariAux);
    pintarTweets($usuari, $tweets, $frase);


}