<?php
session_start();
require_once("../classes/Database.php");
require_once("../classes/User.php");

if (isset($_POST['username']) && isset($_POST['password'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];
    $usuari = new User($username, $password);
    $usuariBDD = User::getUserFromDb($username);

    if ($usuariBDD) {

        if ($usuari->password == $usuariBDD->password && $usuari->username == $usuariBDD->username) {
            $_SESSION['currentUser'] = $usuari->username;
            header("Location: ../pages/admin.php", true, 302);
        } else {
            header("Location: ../pages/login.php?error=WrongCredentials", true, 303);
        }
    } else {
        if (Database::getInstance()->getConnection()) {
            header("Location: ../pages/login.php?error=WrongCredentials", true, 303);
        } else {
            header("Location: ../pages/login.php?error=BDDOffline", true, 303);
        }
    }
} else {
    //How did we end up here?
    header("Location: ../pages/login.php?", true, 303);
}
