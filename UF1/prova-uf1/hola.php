<?php
session_start();

//No està logat? El faig fora.
if(!isset($_SESSION['correu'])){
        header("Location: index.php?error=notLogged", true, 303);
        exit(); 
}

//Comprovo si han passat 60 segons des que es va iniciar la sessió.
if(isset ($_SESSION['sessionStart'] )){

        $since_start = time() - $_SESSION['sessionStart'];

        if($since_start > 7){
            session_destroy();
            header("Location: index.php?error=timeout", true, 303);
            exit();
        }
    
}

//Botó tancar sessió clickat
if (isset($_POST['tancarSessio'])) {
    session_destroy();
    header("Location: index.php?error=logOff", true, 303);
}

$nom = "";
if (isset($_SESSION['nom'])) {
    $nom = $_SESSION['nom'];
}

if (isset($_SESSION['correu'])) {
?>

    <!DOCTYPE html>
    <html lang="ca">

    <head>
        <title>Benvingut</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="style.css" rel="stylesheet">

    </head>

    <body>
        <div class="container noheight" id="container">
            <div class="welcome-container">
                <h1>Benvingut!</h1>
                <div>Hola <?= $nom ?>, les teves darreres connexions són:</div>
                <?php
                //Si hi han connexions, són de status success, i són de l'usuari de la sessió actual, les imprimeixo.
                if (llegeix("connexions.json")) {
                    foreach (llegeix("connexions.json") as $connexio) {
                        $goodConnection = false;
                        if ($connexio['status'] == "signin_success" || $connexio['status'] == "signup_success") {
                            $goodConnection = true;
                        }
                        if ($connexio['user'] == $_SESSION['correu'] && $goodConnection) {
                            echo "<br>Connexió des de " . $connexio['ip'] . "amb data " . $connexio['time'];
                        }
                    }
                }

                ?>

                <form action="#" method="POST">
                    <button type="submit" name="tancarSessio">Tanca la sessió</button>
                </form>
            </div>
        </div>
    </body>

    </html>



<?php
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


?>