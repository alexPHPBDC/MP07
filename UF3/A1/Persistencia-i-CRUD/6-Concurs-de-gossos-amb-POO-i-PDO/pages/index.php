<?php
session_start();
require_once("../classes/Database.php");
require_once("../classes/Phase.php");
require_once("../classes/Dog.php");
require_once("../utils/utilFunctions.php");
require_once("../classes/Vote.php");
require_once("../classes/PhaseContestants.php");
?>

<?php



$date = getAndSetCurrentDate();
$sessionID = session_id();

if (Database::getInstance()->getConnection()) {
    if ($date) {
        if (Phase::dateIsBeforePhase($date, 1)) {
            echo "<h1>Encara no pots votar! Parla amb l'administrador per inscriu-re el teu gos :D</h1>";
            echo "<img src=../assets/gifs/beforeEventStart.gif>";

        } else if (Phase::dateIsAfterPhase($date, 8)) {
            echo paginaFiConcurs();
        } else {

            $resultats = calcularResultatGossos($date);
            $currentPlayers = $resultats["currentPlayers"];
            $currentPhase = $resultats["currentPhase"];


            if ($currentPhase) {
                $phaseId = $currentPhase->id;
                $gosVotat = Vote::getVotedDog($sessionID, $phaseId);
                if ($currentPlayers) {
?>
                    <!DOCTYPE html>
                    <html lang="ca">

                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <title>Votació popular Concurs Internacional de Gossos d'Atura 2023</title>
                        <link rel="stylesheet" href="style.css">

                        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
                        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
                        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
                        <script src="scriptIndex.js"></script>
                    </head>

                    <body>
                        <div class="wrapper">
                            <header>Votació popular del Concurs Internacional de Gossos d'Atura 2023- FASE <span><?= $currentPhase->phaseNumber ?> </span></header>
                            <?= h2DataActual($date) ?>
                            <p class="info"> Podeu votar fins el dia <?= $currentPhase->endDate ?></p>
                            <p id='MissatgeError' class='error'>
                                <?php if ($gosVotat) {echo "Ja has votat al gos $gosVotat->name .Es modificarà la teva resposta";} ?></p>
                            <p id="MissatgeSuccess"></p>
                            <div class="poll-area">
                                <?php

                                $idGosVotat = "";
                                if ($gosVotat) {
                                    $idGosVotat = $gosVotat->id;
                                }
                                foreach ($currentPlayers as $dog) {
                                    echo FormVotarGos($sessionID, $phaseId, $dog->id, $idGosVotat);
                                } ?>


                            </div>

                            <p> Mostra els <a href="resultats.php">resultats</a> de les fases anteriors.</p>
                        </div>

                    </body>

                    </html>
                    


<?php
                }else{

                }
            }
        }
    } else {
        echo "<h1>Data incorrecte</h1>";
    }
} else {
    echo "<h1>Degut a problemes tècnics, la pàgina web no està disponible (ERROR 53321)</h1>";
    echo "<img src=../assets/gifs/databaseOffline.gif>";
}
