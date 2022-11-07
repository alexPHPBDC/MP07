<?php
session_start();
$ip = $_SERVER['REMOTE_ADDR'];
$connexions = llegeix("connexions.json");

/**
 * @param string $ip L'ip de l'usuari
 * @param string $correu El correu de l'usuari
 * @param array $connexions Les connexions que he registrat fins ara
 * @param string $status L'estatus de la connexió
 * @return array L'array amb la connexió afegida.
 */
function afegirConnexio(string $ip, string $correu, array $connexions, string $status): array
{
    $dades = array();
    $dades['ip'] = $ip;
    $dades['user'] = $correu;
    $dades['time'] = date("Y-m-d H:i:s");
    $dades['status'] = $status;
    $connexions[] = $dades;
    return $connexions;
}

if (isset($_POST['method'])) {

    if ($_POST['method'] == 'signup') { //Registrar-se
        $nomExists = isset($_POST['nom']);
        $correuExists = isset($_POST['correu']);
        $contrasenyaExists = isset($_POST['contrasenya']);
        $dadesOK = $nomExists && $correuExists;

        if ($dadesOK) {
            $nom = "";
            if ($nomExists) {
                $nom = $_POST['nom'];
            }
            $correu = "";
            if ($correuExists) {
                $correu = $_POST['correu'];
            }
            $contrasenya = "";
            if ($contrasenyaExists) {
                $contrasenya = $_POST['contrasenya'];
            }

            $usuari = array();
            $usuari[$correu] = array();
            $usuari[$correu]["email"] = $correu;
            $usuari[$correu]["password"] = $contrasenya;
            $usuari[$correu]["name"] = $nom;

            $usuaris = llegeix("users.json");

            for ($i = 0; $i < count($usuaris); $i++) {
                if (array_key_exists($correu, $usuaris[$i])) {
                    $connexions = afegirConnexio($ip, $correu, $connexions, "signup_exist_error");
                    escriu($connexions, "connexions.json");//Millorable, però se m'acaba el temps.
                    header("Location: index.php?error=usuariJaRegistrat", true, 303);
                    exit();
                }
            }

            $usuaris[] = $usuari;
            escriu($usuaris, "users.json");
            $_SESSION['nom'] = $nom;
            $connexions = afegirConnexio($ip, $correu, $connexions, "signup_success");
            escriu($connexions, "connexions.json");
            $_SESSION['correu'] = $correu;
            $_SESSION['sessionStart'] = time();
            header("Location: hola.php?", true, 302);
            exit();
        } else {
            $connexions = afegirConnexio($ip, $correu, $connexions, "signup_missingInput");
            escriu($connexions, "connexions.json");
            header("Location: index.php?error=missingInput", true, 303);
            exit();
        }
    } else if ($_POST['method'] == 'signin') { //Iniciar sessió

        $correuExists = isset($_POST['correuSI']);
        $contrasenyaExists = isset($_POST['contrasenyaSI']) ; 
        $dadesOK = $correuExists && $contrasenyaExists;



        if ($dadesOK) {
            $usuaris = llegeix("users.json");
            $contrasenya = $_POST['contrasenyaSI'];
            $correu = $_POST['correuSI'];

            $usuariCorrecte = false;
            $contrasenyaCorrecte = false;
            for ($i = 0; $i < count($usuaris); $i++) {
                if (array_key_exists($correu, $usuaris[$i])) {
                    $usuariCorrecte = true;
                    if ($usuaris[$i][$correu]['password'] == $contrasenya) {
                        $contrasenyaCorrecte = true;
                        $_SESSION['nom'] = $usuaris[$i][$correu]['name'];
                    }
                }
            }

            if ($usuariCorrecte && $contrasenyaCorrecte) {
                $connexions = afegirConnexio($ip, $correu, $connexions, "signin_success");
                escriu($connexions, "connexions.json");
                $_SESSION['correu'] = $correu;
                $_SESSION['sessionStart'] = time();
                header("Location: hola.php?", true, 302);
            } else if (!$usuariCorrecte) {
                $connexions = afegirConnexio($ip, $correu, $connexions, "signin_email_error");
                escriu($connexions, "connexions.json");
                header("Location: index.php?error=usuariIncorrecte", true, 303);
            } else if (!$contrasenyaCorrecte) {
                $connexions = afegirConnexio($ip, $correu, $connexions, "signin_password_error");
                escriu($connexions, "connexions.json");
                header("Location: index.php?error=contrasenyaIncorrecte", true, 303);
            }
        } else {
            $connexions = afegirConnexio($ip, $correu, $connexions, "signin_missingInput");
            escriu($connexions, "connexions.json");
            header("Location: index.php?error=missingInput", true, 303);

        }
    }



   
}



/**
 * Llegeix les dades del fitxer. Si el document no existeix torna un array buit.
 *
 * @param string $file
 * @return array
 */
function llegeix(string $file): array
{
    $var = [];
    if (file_exists($file)) {
        $var = json_decode(file_get_contents($file), true);
    }
    return $var;
}

/**
 * Guarda les dades a un fitxer
 *
 * @param array $dades
 * @param string $file
 */
function escriu(array $dades, string $file): void
{
    file_put_contents($file, json_encode($dades, JSON_PRETTY_PRINT));
}
