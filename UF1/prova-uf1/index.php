<?php
session_start();

//Si la sessió està iniciada i dins el temps límit, l'envio a hola.php
if (isset($_SESSION['correu']) && isset($_SESSION['sessionStart'])) {
    header("Location: hola.php?", true, 303);
    exit();
}
    

?>


<!DOCTYPE html>
<html lang="ca">

<head>
    <title>Accés</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">

</head>

<body>

    <?php
    //Tractament d'errors que m'arriben per GET
    if (isset($_GET['error'])) {
        $error = "";
        switch ($_GET['error']) {
            case "usuariJaRegistrat":
                $error = "Usuari ja registrat";
                break;
            case "missingInput":
                $error = "Falten dades per posar";
                break;
            case "logOff":
                $error = "Sessió tancada";
                break;
            case "timeout":
                $error = "Sessió expirada";
                break;
            case "usuariIncorrecte":
                $error = "Usuari Incorrecte";
                break;
            case "contrasenyaIncorrecte":
                $error = "Contrasenya Incorrecte";
                break;
            case "notLogged":
                $error = "No estàs logat";
                break;
        }

        echo "<div>$error</div>";
    }
    ?>
    <div class="container" id="container">
        <div class="form-container sign-up-container">
            <form action="process.php" method="post">
                <h1>Registra't</h1>
                <span>crea un compte d'usuari</span>
                <input type="hidden" name="method" value="signup" />
                <input type="text" name="nom" placeholder="Nom" required />
                <input type="email" name="correu" placeholder="Correu electronic" required />
                <input type="password" name="contrasenya" placeholder="Contrasenya" required />
                <button>Registra't</button>
            </form>
        </div>
        <div class="form-container sign-in-container">
            <form action="process.php" method="post">
                <h1>Inicia la sessió</h1>
                <span>introdueix les teves credencials</span>
                <input type="hidden" name="method" value="signin" />
                <input type="email" name="correuSI" placeholder="Correu electronic" required />
                <input type="password" name="contrasenyaSI" placeholder="Contrasenya" required />
                <button>Inicia la sessió</button>
            </form>
        </div>
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Ja tens un compte?</h1>
                    <p>Introdueix les teves dades per connectar-nos de nou</p>
                    <button class="ghost" id="signIn">Inicia la sessió</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Primera vegada per aquí?</h1>
                    <p>Introdueix les teves dades i crea un nou compte d'usuari</p>
                    <button class="ghost" id="signUp">Registra't</button>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    const signUpButton = document.getElementById('signUp');
    const signInButton = document.getElementById('signIn');
    const container = document.getElementById('container');

    signUpButton.addEventListener('click', () => {
        container.classList.add("right-panel-active");
    });

    signInButton.addEventListener('click', () => {
        container.classList.remove("right-panel-active");
    });
</script>

</html>