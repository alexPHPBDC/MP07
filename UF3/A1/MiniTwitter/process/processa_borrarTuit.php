<?php
session_start();
include_once "../utils.php";
include_once "../funcions.php";
if(isset($_SESSION['userId'])){

if(!empty($_GET)){

    $tweetId = obtenirVariableGet("idTweet");
    $userId = $_SESSION['userId'];
    if(isOwner($tweetId,$userId)){
        deleteTweet($tweetId);
        header("Location: ../inici.php", true, 302);
        exit();
    }else{
        header("Location: ../inici.php?error=notYourTweet", true, 303);
        exit();
    }

}

}

//How did u end up here? go back to inici
header("Location: ../inici.php?error=wtf", true, 303);

?>