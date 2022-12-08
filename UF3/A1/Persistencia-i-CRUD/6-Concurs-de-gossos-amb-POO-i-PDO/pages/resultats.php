<?php
session_start();
require_once("../classes/Database.php");
require_once("../classes/Phase.php");
require_once("../classes/PhaseContestants.php");
require_once("../classes/Dog.php");
require_once("../utils/utilFunctions.php");
require_once("../classes/Vote.php");

$date = getAndSetCurrentDate();
calcularResultatGossos($date);

if(Database::getInstance()->getConnection()){
?>


<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultat votació popular Concurs Internacional de Gossos d'Atura</title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="wrapper large">
        <header>Resultat de la votació popular del Concurs Internacional de Gossos d'Atura 2023</header>
        <?= h2DataActual($date)?>
        <div class="results">
            <?php
            $resultats = PhaseContestants::getPhaseContestantsUntilToday($date);
            if ($resultats) {
                foreach ($resultats as $phaseNumber => $resultat) {

                    echo "<h1><b>Resultat fase $phaseNumber</b></h1>";
                    echo "<div class='resultatFase'>";
                    if ($phaseNumber == 1) {
                        $dogEliminat = PhaseContestants::getDeletedDogFromFirstPhase();
                    } else {
                        $dogEliminat = PhaseContestants::getDeletedDog($phaseNumber);
                    }

                    foreach ($resultat as $gos) {
                        $dogName = $gos['dogName'];
                        $percentage = $gos['votePercentage'] * 100;

                        $dogImage = $gos['dogImage'];


            ?>

                        <div class="dogInPhase">
                            <h6><?= $dogName ?></h6>
                            <img class='dog' alt='<?= $dogName ?>' title='<?= $dogName ?> <?= $percentage ?>%' src='<?= $dogImage ?>'>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: 100%; background-color: #<?= random_color() ?>" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"><?= $percentage ?>%</div>
                            </div>

                        </div>
                    <?php
                    }
                    ?>
                    <div class="dogInPhase eliminat">
                        <h6><?= $dogEliminat->name ?></h6>
                        <img class='dog' alt='<?= $dogEliminat->name ?>' title='<?= $dogEliminat->name ?>' src='<?= $dogEliminat->image ?>'>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: 100%; background-color: grey" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">ELIMINAT</div>
                        </div>

                    </div>

            <?php

                    echo "</div>";//Tengo una fila (Per exemple, fase 1)
                }
            } else {
                echo "Encara no tenim guanyadors de fases :)";
            }
            ?>

        </div>

</body>

</html>

<?php

}else{
    echo "<h1>Degut a problemes tècnics, la pàgina web no està disponible (ERROR 53321)</h1>";
    echo "<img src=../assets/gifs/databaseOffline.gif>";
}


?>