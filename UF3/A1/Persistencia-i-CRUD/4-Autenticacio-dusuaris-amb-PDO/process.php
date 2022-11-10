<?php
session_start();
$ip = $_SERVER['REMOTE_ADDR'];

include_once("usuari.php");
include_once("connection.php");

if (isset($_POST['method'])) {
    $accio = $_POST['method'];
    if ($accio == 'signup') { //Registrar-se

        if (isset($_POST['nom']) && isset($_POST['correu']) && isset($_POST['contrasenya'])) {

            $nom = $_POST['nom'];
            $email = $_POST['correu'];
            $contrasenya = $_POST['contrasenya'];
            $connection = new Connection($ip, $email);
            $user = new Usuari($email, $contrasenya, $nom);
            if ($user->getUserFromDb($email) != null) {
                $connection->status = "signup_exist_error";
                $connection->addConnectionToDB();
                header("Location: index.php?error=usuariJaRegistrat", true, 303);
            } else {
                $user->addUserToDB();
                $connection->status = "signup_success";
                $connection->addConnectionToDB();
                emplenarSessio($email, $nom);
                header("Location: hola.php?", true, 302);
            }
        } else {
            $connection = new Connection($ip);
            $connection->status = "signup_missingInput";
            $connection->addConnectionToDB();
            header("Location: index.php?error=missingInput", true, 303);
        }
    } else if ($accio == 'signin') { //Iniciar sessió

        if (isset($_POST['correuSI']) && isset($_POST['contrasenyaSI'])) {
            
            $email = $_POST['correuSI'];
            $contrasenya = hashPassword($_POST['contrasenyaSI']);
            $usuari = new Usuari();
            $usuari = $usuari->getUserFromDb($email);
            $connection = new Connection($ip, $email);
            if ($usuari === null) {
                $connection->status = "signin_email_error";
                $connection->addConnectionToDB();
                header("Location: index.php?error=usuariIncorrecte", true, 303);
            } else if ($usuari->email == $email && $usuari->password == $contrasenya) {
                $connection->status = "signin_success";
                $connection->addConnectionToDB();
                emplenarSessio($usuari->email, $usuari->name);
                header("Location: hola.php?", true, 302);
            } else {
                $connection->status = "signin_password_error";
                $connection->addConnectionToDB();
                header("Location: index.php?error=contrasenyaIncorrecte", true, 303);
            }
        } else {
            $connection = new Connection($ip);
            $connection->status = "signup_missingInput";
            $connection->addConnectionToDB();
            header("Location: index.php?error=missingInput", true, 303);
        }
    } else {
        //How did we end up here?
        header("Location: index.php?error=notLogged", true, 303);
    }
} else {
    //How did we end up here?
    header("Location: index.php?error=notLogged", true, 303);
}


function emplenarSessio($email, $name)
{
    $_SESSION['nom'] = $name;
    $_SESSION['correu'] = $email;
    $_SESSION['sessionStart'] = time();
}
