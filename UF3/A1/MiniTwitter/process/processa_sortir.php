<?php
session_start();
if(isset($_POST['tancarSessio'])){
    session_destroy();
    header("Location: ../formularis/formulari_login.php", true, 302);
}

//How did u end up here? go back to index
header("Location: ../formularis/formulari_login.php?error=Forbidden", true, 303);