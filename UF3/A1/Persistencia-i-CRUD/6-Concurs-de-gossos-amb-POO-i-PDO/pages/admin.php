<?php
session_start();

if (!isset($_SESSION['currentUser'])) {
    header("Location: login.php?", true, 303);
    exit();
}

require_once("../classes/Database.php");
require_once("../classes/Dog.php");
require_once("../classes/Phase.php");
require_once("../classes/User.php");
require_once("../utils/utilFunctions.php");
require_once("../classes/Vote.php");
require_once("../classes/PhaseContestants.php");

$date = getAndSetCurrentDate();
calcularResultatGossos($date);

if(Database::getInstance()->getConnection()){

?>
<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN - Concurs Internacional de Gossos d'Atura</title>
    <link rel="stylesheet" href="style.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="scriptAdmin.js"></script>
</head>

<body>
    <div class="wrapper medium">
        <header>ADMINISTRADOR - Concurs Internacional de Gossos d'Atura</header>
        <div class="admin">
            <?php echo h2DataActual($date);
            if(Phase::dateIsBeforePhase($date,1)){echo "<h6 style='text-align:center'>Encara no ha començat el concurs! Pots afegir gossos</h6>";}
            ?>
            <div class="admin-row votsParcials">
                <?php
                echo divVotsParcials($date);
                ?>
            </div>

            <div class="admin-row">
                <h1> Nou usuari: </h1>
                <div id="addUserResponse" class="toast-container"></div>
                <div id="formulariUser">
                    <?php
                    echo addUserForm();
                    ?>
                </div>
            </div>
            <div class="admin-row">
                <span id="MissatgeErrorPhase"></span>
                <span id="MissatgeSuccessPhase"></span>
                <div id="phases">
                    <?php echo getStringTotesLesPhases($date) ?>
                </div>
            </div>

            <div class="admin-row">
                <h1> Modificar concursants: </h1>
                <div id="updateDogResponse" class="toast-container"></div>
                <div id="concursants">
                    <?php
                    $dogs = Dog::getDogsFromDB();
                    if ($dogs) {
                        echo UpdateDogForms($dogs);
                    } else {
                        echo "<h1>No hi ha gossos :)</h1>";
                    }
                    ?>
                </div>

                <?php
                if (Phase::dateIsBeforePhase($date, 1)) {
                ?>
                    <h1> Crear concursants: </h1>
                    <div id="addDogResponse" class="toast-container"></div>
                    <div id="formulariAddDog">
                        <?php
                        echo addDogForm();
                        ?>
                    </div>
                <?php
                }
                ?>
            </div>

            <div class="admin-row">
                <h1> Altres operacions: </h1>
                <?php
                $phases = Phase::getAllPhases();
                if ($phases) { ?>
                    <div id="missatgeEsborrarVotsPhase"></div>
                    <div><span>Esborra els vots de la fase</span>
                        <form id="esborrarVotsFaseEspecifica">
                            <select id="selectVotsFases" name="phaseInfo">
                                <?php

                                foreach ($phases as $phase) {
                                ?>
                                    <option value='{"id":"<?= $phase->id ?>","phaseNumber":"<?= $phase->phaseNumber ?>"}'><?= $phase->phaseNumber ?></option>
                                <?php
                                } ?>
                            </select>

                            <input type="submit" value="Esborra">
                        </form>
                    </div>
                <?php } else {
                    echo "No hi ha fases :)";
                } ?>

                <div id="missatgeEsborrarTotsVots"></div>
                <form id="esborrarTotsVots">
                    Esborra tots els vots
                    <input type="hidden" name="delete" value="true">
                    <input type="submit" value="Esborra">
                </form>

            </div>
        </div>
    </div>

</body>

</html>

<?php

}else{
    echo "<h1>La connexió a la base de dades no ha pogut ser establerta, comprova si has posat les dades bé!</h1>";
    echo "<img src=../assets/gifs/databaseOffline.gif>";

}