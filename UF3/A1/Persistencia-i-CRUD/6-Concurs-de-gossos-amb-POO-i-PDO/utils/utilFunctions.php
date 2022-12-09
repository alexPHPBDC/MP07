<?php


function addDogForm()
{
    return '<form id="addDog" enctype="multipart/form-data">
<div class="row">
    <div class="col-2">
        <label class="form-label">Nom
            <input class="form-control" type="text" placeholder="Joselito" name="name">
        </label>
    </div>

    <div class="col-2"> <label class="form-label">Imatge<input type="file" class="form-control" placeholder="Imatge" name="image"></div></label>
    <div class="col-2"> <label class="form-label">Amo<input type="text" class="form-control" placeholder="Amo" name="owner"></div></label>
    <div class="col-2"> <label class="form-label">Raça<input type="text" class="form-control" placeholder="Raça" name="breed"></div></label>
    <div class="col-2"><label class="form-label"><span style="visibility:hidden">Enviar</span><input class="form-control btn btn-danger float-right" type="submit"></label></div>
</div>

</form>';
}

function addUserForm()
{

    return '<form id="addUser">
    <input type="text" placeholder="Nom" name="username">
    <input type="password" placeholder="Contrassenya" name="password">
    <input type="submit" value="Crea usuari">
</form>';
}

function UpdateDogForms(array $dogs)
{
    $allForms = "";
    foreach ($dogs as $dog) {
        $allForms .= "<form id=f_{$dog->id} enctype='multipart/form-data'>
        <div class='row'>                    
        <input type='hidden' name='id' value={$dog->id}>
                            <div class='col-2'>
                            <label class='form-label'>Nom
                            <input class='form-control' type='text' placeholder='Nom' name='name' value={$dog->name}>
                            </label>
                            </div>
                            
                            <div class='col-2'>
                            <label class='form-label'>Imatge
                            <img class='form-control dog' src='{$dog->image}' alt='Dog photo'>
                            <input style='display:none' type='file' placeholder='Imatge' name='image' value=''>
                            </label>
                            </div>
                            <div class='col-2'>
                            <label class='form-label'>Amo
                            <input class='form-control' type='text' placeholder='Amo' name='owner' value={$dog->owner}>
                            </label>
                            </div>
                            <div class='col-2'>
                            <label class='form-label'>Raça
                            <input class='form-control' type='text' placeholder='Raça' name='breed' value={$dog->breed}>
                            </label>
                            </div>
                            <div class='col-2'>
                            <label class='form-label'><span style='visibility:hidden'>Modifica</span>
                            <input class='form-control' type='button' name='f_{$dog->id}' value='Modifica' onclick='ajaxUpdateDog(this.name)'>
                            </label>
                            </div>
                            </div>
                            </form>";
    }

    return $allForms;
}

function FormVotarGos($sessionID, $phaseId, $dogId, $idGosVotat = "")
{
    $isSelected = $idGosVotat == $dogId ? "selected" : "";
    $dog = Dog::getDogFromDB($dogId);
    $formulari = "";
    if ($dog) {
        $formulari .= "<form id={$dog->id}>
    <input type='hidden' name='phaseId' value='$phaseId'>
    <input type='hidden' name='sessionId' value='$sessionID'>
    <input type='hidden' name='dogId' value='$dogId'>
    <input type='hidden' name='dogName' value='$dog->name'>
        <button type='button' name='$dogId' id='opt-$dogId' onclick='ajaxVoteDog(this.name)'></button>
        <label id='label-$dogId' for='opt-$dogId' class='opt-$dogId $isSelected'>
            <div class='row'>
                <div class='column'>
                    <div class='right'>
                        <span class='circle'></span>
                        <span class='text'>$dog->name</span>
                    </div>
                    <img class='dog' alt='$dog->name' src='$dog->image'>
                </div>
            </div>
        </label>
        </form>";
    }
    return $formulari;
}

function uploadImage($image, $target_file)
{
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $errors = [];

    $check = getimagesize($image["tmp_name"]);
    if ($check !== false) {

        $uploadOk = 1;
    } else {
        $errors[] = "El fitxer no és una imatge";
    }




    // Check file size
    if ($image["size"] > 500000) {
        $errors[] = "Imatge massa grossa :)";
    }

    // Allow certain file formats
    $acceptedFormats = ["jpg" => true, "png" => true, "jpeg" => true, "gif" => true];
    if (!isset($acceptedFormats[$imageFileType])) {
        $errors[] = "Només admeto JPG, JPEG, PNG I GIF";
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $errors[] = "El teu fitxer no s'ha penjat :(";
        // if everything is ok, try to upload file
    } else {
        $errors[] = $image["tmp_name"];
        $errors[] = $target_file;
        if (move_uploaded_file($image["tmp_name"], $target_file)) {
        } else {
            $errors[] = "Hi ha hagut un error penjant l'imatge.";
        }
    }

    return ["errors" => $errors];
}

function random_color_part()
{
    return str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
}

function random_color()
{
    return random_color_part() . random_color_part() . random_color_part();
}

function getAndSetCurrentDate()
{

    if (isset($_GET['data'])) {
        $data = $_GET['data'];
        $data = formatDate($data);

        if ($data) {
            $_SESSION['date'] = $data;
            return $data;
        } else {
            return false;
        }
    } else if (!isset($_SESSION['date'])) {
        $data = date("Y/m/d");
        $_SESSION['date'] = $data;
    } else {
        $data = $_SESSION['date'];
    }

    if (isset($data)) {
        return $data;
    } else {
        return false;
    }
}

function formatDate($date)
{
    $date = str_replace(".", "/", $date);

    $dateToTime = strtotime($date);
    if ($dateToTime) {

        return date("Y/m/d", $dateToTime);
    } else {
        return false;
    }
}

function h2DataActual($date)
{
    return "<h2 style='text-align:center'>Data actual: $date</h2>";
}
srand(63636661);
function calcularResultatGossos($date)
{
    $currentPhase = null;
    $i = 1;

    for (; $i <= 8 && Phase::dateIsAfterPhase($date, $i); $i++) {

        $currentPhase = Phase::getPhaseByPhaseNumber($i);

        if ($currentPhase) {
            $phaseId = $currentPhase->id;

            $currentPlayers = PhaseContestants::getWinnersByPhaseFromDb($phaseId);

            if (!$currentPlayers) { //Si en aquesta fase no hi ha guanyadors, els he de generar
                $winners = array();
                $dogVotes = PhaseContestants::getVotedDogsOfPhase($i); //Pillo els gossos amb els seus vots.
                $nWinners = 9 - $i;

                if ($dogVotes) {

                    $vots = array();
                    $empat = false;
                    $valueVotsEmpata = null;
                    foreach ($dogVotes as $voteValue) {
                        if (isset($vots[$voteValue['votes']])) {
                            $empat = true;
                            $valueVotsEmpata = $voteValue['votes'];
                            break;
                        } else {
                            $vots[$voteValue['votes']] = true;
                        }
                    }

                    if ($empat) {

                        //Si hi ha un empat, em quedo amb els 8 - $phaseNumber que tinguin més vots totals
                        $mostVotedDogs = PhaseContestants::getMostVotedDogs($i - 1);
                        if ($i == 1) {
                            $mostVotedDogs = PhaseContestants::getMostVotedDogsOfFirstPhase();
                        }


                        //Si no tinc prous winners, miro els que tenen més vots d'entre els participants de la fase
                        if ($mostVotedDogs) {

                            if (isset($mostVotedDogs[count($mostVotedDogs) - 2]['vote']) && $mostVotedDogs[count($mostVotedDogs) - 1]['vote'] != $mostVotedDogs[count($mostVotedDogs) - 2]['vote']) { //Si l'últim i el penúltim no tenen el mateix número de vots, trec l'últim (el menys votat) i jasta
                                for ($j = 0; $j < 8 - $i; $j++) {
                                    $winners[$mostVotedDogs[$j]['id']] = new Dog($mostVotedDogs[$j]['id'], $mostVotedDogs[$j]['name'], $mostVotedDogs[$j]['image']);
                                }
                            } else {
                                // echo "<h1>A COMPETIL</h1>";
                                //He de fer que els que tenen aquest numero de vots competeixin, i se'n va un random

                                //$nVotesRepes = $mostVotedDogs[count($mostVotedDogs) - 1]; //Per exemple, 0 vots
                                $desempatadors = array();
                                $noBarallen = array();

                                foreach ($mostVotedDogs as $dog) {
                                    if ($dog['votes'] == $valueVotsEmpata) {
                                        $desempatadors[] = $dog;
                                    } else {
                                        $noBarallen[] = $dog;
                                    }
                                }

                                $numeroRandom = rand(0, count($desempatadors) - 1);
                                array_splice($desempatadors, $numeroRandom, 1);

                                foreach ($noBarallen as $dog) {
                                    $winners[$dog['id']] = new Dog($dog['id'], $dog['name'], $dog['image']);
                                }

                                foreach ($desempatadors as $dog) {
                                    $winners[$dog['id']] = new Dog($dog['id'], $dog['name'], $dog['image']);
                                }
                            }
                        }
                    } else {
                        //Altrament afegeixo 8 - $phaseNumber(i) gossos (em venen ordenats de més votat a menys)
                        $counter = 0;
                        foreach ($dogVotes as $dog) {
                            if ($counter >= (8 - $i)) {
                                break;
                            }
                            $winner = new Dog($dog['id'], $dog['name'], $dog['image'], "", "");
                            $winners[$dog['id']] = $winner;
                            $counter++;
                        }
                    }


                    if (count($winners) != $nWinners) { //Si no tinc prous winners encara, els pillo random. Aqui no hauria d'entrar mai.

                        if ($i == 1) { //Si he de pillar randoms a la primera fase, els agafo de tots els gossos
                            $randomDogs = Dog::getDogsFromDB();
                        } else { //Altrament agafo els que van guanyar anteriorment(Osigui els participants actuals)
                            $randomDogs = PhaseContestants::getWinnersByPhaseFromDb($i - 1);
                        }


                        while (count($winners) < $nWinners) { //Anem afegint gossos random
                            //echo "<h3>".count($winners)."</h3>";
                            $randomNumber = rand(0, count($randomDogs) - 1);

                            if (count($winners) >= $nWinners) {
                                break;
                            }

                            $randomDog = $randomDogs[$randomNumber];
                            $winners[$randomDog->id] = new Dog($randomDog->id, $randomDog->name, $randomDog->image);
                        }
                    }


                    foreach ($winners as $winner) {
                        PhaseContestants::insertDogToPhase($winner->id, $i);
                    }
                }
            }
        }
    }

    $currentPhase = Phase::getPhaseByPhaseNumber($i);
    if ($currentPhase) {
        $currentPlayers = PhaseContestants::getWinnersByPhaseFromDb($currentPhase->id - 1);
    }
    if ($i == 9) {
        $currentPlayers = PhaseContestants::getWinnersByPhaseFromDb(8);
        $currentPhase = false;
    } else if ($i == 1) {
        $currentPlayers = Dog::getDogsFromDB();
    }



    return ["currentPlayers" => $currentPlayers, "currentPhase" => $currentPhase];
}

function divVotsParcials($date)
{
    $stringDiv = "";
    $faseActual = Phase::getPhaseByDateFromDb($date);
    if ($faseActual) {
        $votsParcials = Vote::getPartialVotes($faseActual->phaseNumber);
        $stringDiv .= "<h1>Resultat parcial: Fase $faseActual->phaseNumber</h1>";
        $stringDiv .= "<div class='resultatFase partial'>";

        foreach ($votsParcials as $gos) {
            $dogName = $gos['dogName'];
            $percentage = $gos['votePercentage'] * 100;
            $dogImage = $gos['dogImage'];

            $stringDiv .=  "
            <div class='dogInPhase'>
            <h6>$dogName</h6>
            <img class='dog' alt='$dogName' title='$dogName $percentage%' src='$dogImage'>
            <div class='progress'>
                <div class='progress-bar' role='progressbar' style='width: 100%; background-color: #" . random_color() . " ' aria-valuenow='100' aria-valuemin='0' aria-valuemax='100'>$percentage%</div>
            </div>

        </div>";
        }
        $stringDiv .= "</div>";
    } else {
        $stringDiv .= "<h6>No hi han resultats parcials a mostrar</h6>";
    }
    return $stringDiv;
}

function getStringTotesLesPhases($date)
{
    $string = "<h1> Fases: </h1>";




    $phases = Phase::getAllPhases();
    $counter = 0;
    if ($phases) {
        foreach ($phases as $phase) {

            if (Phase::dateIsAfterPhase($date, $phase->phaseNumber)) {
                $string .= "<form class='fase-row'>
            <input type='hidden' name='date' value='$date'>
            <input type='hidden' name='phaseNumber' value='$phase->phaseNumber'>
            Fase <input disabled type='text' value='$phase->phaseNumber' style='width: 3em'>
            del <input disabled type='date' placeholder='Inici' value='$phase->startDate'>
            al <input disabled type='date' placeholder='Fi' value='$phase->endDate'>
            
            </form>";
            } else if (Phase::hasStarted($date, $phase->phaseNumber)) {
                $dataIniciStatus = "enabled";
                $inputAmagat = "";
                if ($counter == 0) {
                    $counter++;
                    $dataIniciStatus = "disabled";
                    $inputAmagat = "<input type='hidden' name='startDate' placeholder='Inici' value='$phase->startDate'>";
                }

                $string .= "<form id='fphase-$phase->phaseNumber' class='fase-row'>
            $inputAmagat
            <input type='hidden' name='date' value='$date'>
            <input type='hidden' name='phaseNumber' value='$phase->phaseNumber'>
        Fase <input type='text' name='phaseNumber' value='$phase->phaseNumber' disabled style='width: 3em'>
        del <input $dataIniciStatus type='date' name='startDate' placeholder='Inici' value='$phase->startDate'>
        al <input type='date' placeholder='Fi' name='endDate' value='$phase->endDate'>
        <input type='button' name='fphase-$phase->phaseNumber' value='Modifica' onclick='ajaxModifyPhaseDate(this.name)'>
        </form>";
            } else {
                $string .= "<form id='fphase-$phase->phaseNumber' class='fase-row'>
            <input type='hidden' name='date' value='$date'>
            <input type='hidden' name='phaseNumber' value='$phase->phaseNumber'>
        Fase <input type='text' name='phaseNumber' value='$phase->phaseNumber' disabled style='width: 3em'>
        del <input type='date' name='startDate' placeholder='Inici' value='$phase->startDate'>
        al <input type='date' placeholder='Fi' name='endDate' value='$phase->endDate'>
        <input type='button' name='fphase-$phase->phaseNumber' value='Modifica' onclick='ajaxModifyPhaseDate(this.name)'>
        </form>";
            }
        }
    } else {
        $string .= "Error en la base de dades";
    }

    return $string;
}


function paginaFiConcurs()
{
    echo `
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
            <header>Votació popular del Concurs Internacional de Gossos d'Atura 2023- FINALITZAT </span></header>
            <p> Mostra els <a href="resultats.php">resultats</a> de les fases anteriors.</p>
        </div>

    </body>

    </html>

`;
}
