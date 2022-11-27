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
$date = date("Y/m/d");
$sessionID = session_id();
$currentPhase = Phase::getPhaseByDateFromDb($date);

if ($currentPhase) {
    $phaseId = $currentPhase->id;
    $gosVotat = Vote::getVotedDog($sessionID,$phaseId);
    $currentContestants = PhaseContestants::getContestantsByPhaseFromDb($phaseId);
    if ($currentContestants) {
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

        </head>

        <body>
            <div class="wrapper">
                <header>Votació popular del Concurs Internacional de Gossos d'Atura 2023- FASE <span><?= $currentPhase->phaseNumber ?> </span></header>
                <p class="info"> Podeu votar fins el dia <?= $currentPhase->endDate ?></p>
                <p id='MissatgeError' class='error'><?php if($gosVotat){echo "Ja has votat al gos $gosVotat->name .Es modificarà la teva resposta";} ?></p>



                
                <p id="MissatgeSuccess"></p>
                <div class="poll-area">
                    <?php
                   
                   $idGosVotat = "";
                    if($gosVotat){
                        $idGosVotat = $gosVotat->id;
                    }
                    foreach ($currentContestants as $dog) {
                        echo FormVotarGos($sessionID, $phaseId, $dog->id,$idGosVotat);
                    } ?>


                </div>

                <p> Mostra els <a href="resultats.php">resultats</a> de les fases anteriors.</p>
            </div>

        </body>

        </html>
        <script>
            $(document).ready(function() {

                $("label").on('click', function() {
                    
                    //var idLabel = this.id.replace(/opt/, 'label');
                    $("label").removeClass("selected");
                    $("#" + this.id).addClass("selected");
                });
            });


            function ajaxVoteDog(idFormulariDog) {

                var formData = new FormData(document.getElementById(idFormulariDog));
                var actionUrl = "../ajax/ajaxVoteDog.php";
                var missatgesError = document.getElementById("MissatgeError");
                var missatgesSuccess = document.getElementById("MissatgeSuccess");
                var dogVotedName = document.getElementById("dogVotedName");
                $.ajax({
                    url: actionUrl,
                    type: 'POST',
                    data: formData,
                    success: function(data) {
                        response = JSON.parse(data);
                        if (response['success'].length != 0) {
                            response['success'].forEach(success => {
                                missatgesSuccess.innerHTML = success;
                            })

                            if(response['dogName']){
                                MissatgeError.innerHTML = "Ja has votat al gos "+response['dogName']+". Es modificarà la teva resposta";
                            }


                        } else if (response['errors'].length != 0) {
                            response['errors'].forEach(error => {
                                missatgesError.innerHTML = error;
                            })
                        }


                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });
            }
        </script>


<?php
    } else {
        echo "<h1>No hi ha concursants</h1>";
    }
} else {
    echo "<h1>Ja no pots votar!</h1>";
    echo "<p> Mostra els <a href='resultats.php'>resultats</a> de les fases anteriors.</p>";
}
