<?php
include_once("../funcions.php");
include_once("../utils/pintar.php");

if (isset($_POST['idUsuariToFollow']) && isset($_POST['self'])) {
    followUser($_POST['self'], $_POST['idUsuariToFollow']);
    pintarTot($_POST['self'], $_POST['idUsuariToFollow']);
}else if(isset($_POST['idUsuariToUnfollow']) && isset($_POST['self'])){
    unfollowUser($_POST['self'], $_POST['idUsuariToUnfollow']);
    pintarTot($_POST['self'], $_POST['idUsuariToUnfollow']);
}


