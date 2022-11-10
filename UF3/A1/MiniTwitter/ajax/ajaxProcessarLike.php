<?php
session_start();
include_once "../funcions.php";
include_once("../utils/pintar.php");

if (isset($_POST['self']) && isset($_POST['like']) && isset($_POST['idAux'])) {
    changeAndPrint($_POST['self'], $_POST['idAux'], $_POST['like']);
}else if(isset($_POST['self']) && isset($_POST['dislike']) && isset($_POST['idAux'])){
    changeAndPrint($_POST['self'], $_POST['idAux'], $_POST['dislike']);
}

