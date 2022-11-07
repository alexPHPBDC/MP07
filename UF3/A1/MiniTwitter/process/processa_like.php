<?php
session_start();
include_once "../funcions.php";

if (isset($_POST['idUsuari'])) {

    if ($_POST['idUsuari'] == $_SESSION['userId']) {
        if (isset($_POST['dislike'])) {
            dislikeTweet($_POST['idUsuari'], $_POST['dislike']);
            header("Location: ../inici.php", true, 302);
            exit();
        } else if (isset($_POST['like'])) {
            likeTweet($_POST['idUsuari'], $_POST['like']);
            header("Location: ../inici.php", true, 302);
            exit();
        }
    }else{
        header("Location: ../inici.php?error=notYou", true, 303);
    }
}
//How did u end up here? go back to inici
header("Location: ../inici.php?error=wtf", true, 303);
