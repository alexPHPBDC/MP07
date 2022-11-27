<?php
require_once("../classes/Database.php");
require_once("../classes/Phase.php");
require_once("../classes/PhaseContestants.php");
require_once("../classes/Dog.php");
require_once("../utils/utilFunctions.php");
require_once("../classes/Vote.php");
?>


<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultat votació popular Concurs Internacional de Gossos d'Atura</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</head>

<body>
    <div class="wrapper large">
        <header>Resultat de la votació popular del Concurs Internacional de Gossos d'Atura 2023</header>
        <div class="results">
            <?php
            $date = date("Y/m/d");

            $resultats = PhaseContestants::getPhaseContestantsUntilToday($date);
            foreach ($resultats as $phaseNumber => $resultat) {

                echo "<h1><b>Resultat fase 1</b></h1>";
                echo "<div class='resultatFase'>";
                foreach ($resultat as $gos) {
                    $dogName = $gos['dogName'];
                    $percentage = $gos['votePercentage'] * 100;
                   
                    $dogImage = $gos['dogImage'];
                    

?>

<div class="dogInPhase" style="width: <?=$percentage?>%;">
<h6><?=$dogName?></h4>
<img class='dog' alt='<?=$dogName?>' title='<?=$dogName?> <?=$percentage?>%' src='<?=$dogImage?>'>
<div class="progress">
  <div class="progress-bar" role="progressbar" style="width: 100%; background-color: #<?=random_color()?>" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"><?=$percentage?>%</div>
</div>

</div>
<?php                    
                }

                echo "</div>";
            }
            ?>

        </div>

</body>

</html>

<?php

function random_color_part() {
    return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
}

function random_color() {
    return random_color_part() . random_color_part() . random_color_part();
}