<?php
session_start();
/**
 * Functions that returns today date as an int
 * @return int Today's date as something like 221013
 */
function dataDeAvui(): int
{
    $date = date('Ymd');
    return $date;
}

/**
 * Functions that returns today date as an int
 * @return int|false Today's date as something like 221013 or false, if the date
 * is not valid
 */
function dataCustom(): int|false
{
    $date = $_GET['data'];

    if (validDateTime($date, "Y.m.d")) {
        $date = DateTime::createFromFormat("Y.m.d", $date);
        $date = $date->format("Ymd");
    } else {
        $date = false;
    }

    return $date;
}

/**
 * @param string $dateStr the date string
 * @param string $format format that needs to be required
 * @return bool is a valid Date
 */
function validDateTime(string $dateStr, string $format): bool
{
    $date = DateTime::createFromFormat($format, $dateStr);
    return $date && ($date->format($format) === $dateStr);
}

/**
 * No need to work with functions with more than 7 different chars, they are 
 * not possible to make.
 * @param array $functions array with all PHP functions
 * @param string &$preparedLetters all letters from each phpfunction with less than 7 different chars
 * @return array Double dimension array, position 0 is the function, 1 is its chars as keys.
 */
function treatFunctions(array $functions, &$preparedLetters): array
{
    $treatedFunct = [];
    foreach ($functions as $string) {
        $funcioUnique = count_chars($string, 3);
        if (!isset($funcioUnique[7])) {
            $treatedFunct[] = array($string, array_flip(str_split($funcioUnique)));
            $preparedLetters .= $string;
        }
    }

    return $treatedFunct;
}

/**
 * Fills global $_SESSION with values
 * @param int $date Today's date as an int
 */
function emplenarSessio(int $date)
{
    $time_pre = microtime(true);
    $_SESSION['lletresProvades'] = [];
    $preparedLetters = '';
    $phpfunctions =  treatFunctions(get_defined_functions()["internal"], $preparedLetters);
    //$preparedLetters has been changed at treatFunctions by reference.
    $minimumWords = 10;
    srand($date);
    $randomLetters = getGoodLetters($minimumWords, $phpfunctions, $preparedLetters);
    $_SESSION['letters'] = $randomLetters;
    $_SESSION['correctWords'] = [];
    $_SESSION['phpFunctions'] = $phpfunctions;
    $_SESSION['dia'] = $date;
    $_SESSION['allOk'] = true;

    $exec_time = microtime(true) - $time_pre;
    echo $exec_time;
}

/**
 * Empties global $_SESSION and only preserves an indicator to know
 * an invalid day has been entered.
 */
function diaIncorrecte()
{
    session_destroy();
    session_start();
    $_SESSION['allOk'] = false;
}

/**
 * Returns error depending on the error found at $_GET
 * @return string $error The error, formatted as desired.
 */
function obtenirError(): string
{
    $error = $_GET['error'];

    switch ($error) {
        case "alreadyIn":
            $error = "Ja l'has introduit";
            break;
        case "invalidFunct":
            $error = $_GET['testWord'];
            break;
        case "middleMissing":
            $error = "Falta la lletra del mig";
            break;
    }

    return $error;
}

/**
 * Function to get random letters, using map unique key funcionality to get O(1) speed.
 * You will get better letters due to them being more prevalent on the string.
 * @param string $preparedLetters All the letters found in php functions.
 * @return array Array with random letters 
 */
function getRandomLetters(string &$preparedLetters): array
{
    $hi = [];
    $amount = 2404; //Precomputed preparedLetters length
    $counter = 0;
    do {
        $random = mt_rand(0, $amount);
        $letra = $preparedLetters[$random];
        $hi[$letra] = 1;
        $counter++;
    } while (count($hi) < 7);

    return array_keys($hi);
}

/**
 * Keeps randomizing letters until minimumWords can be formed
 * @param int $minimumWords minimum words to form
 * @param array &$phpfunctions treated functions and their characters.
 * @param string &$preparedLetters all letters of functions combined in one string
 * @return array letters than can form 10 functions.
 */
function getGoodLetters(int $minimumWords, array &$phpfunctions, string &$preparedLetters): array
{
    do {
        $randomLetters = getRandomLetters($preparedLetters);
        $maxFunction = maxFunctionAmount($randomLetters, $minimumWords, $phpfunctions);
    } while ($maxFunction < $minimumWords);

    return $randomLetters;
}

/**
 * Given array of chars, returns amount of functions that can be done with them.
 * Uses isset() to get O(1) speed.
 * @param array $stringArray
 * @param int $minimumWords
 * @param array $phpfunctions functions and their characters.
 * @return int max amount of functions
 */
function maxFunctionAmount(array $stringArray, int $minimumWords, array &$phpfunctions): int
{

    $middleLetter = $stringArray[3];
    $counter = 0;
    $stringArray = array_flip($stringArray);

    foreach ($phpfunctions as $funcio) {
        $lletres = $funcio[1];
        if (isset($lletres[$middleLetter])) {
            $could = true;

            foreach ($lletres as $lletra => $index) {
                if (!isset($stringArray[$lletra])) {
                    $could = false;
                    break;
                }
            }
            if ($could) {
                $counter++;
                $_SESSION['chuleta'][] = $funcio[0];

                if ($counter === $minimumWords) {
                    break;
                }
            }
        }
    }


    if ($counter !== $minimumWords) {
        $_SESSION['chuleta'] = [];
    }

    return $counter;
}



$dataExists = isset($_GET['data']);
$diaExists = isset($_SESSION['dia']);

if ($dataExists) {
    $date = dataCustom();

    if ($date !== false) {

        if ($diaExists) {
            if ($date != $_SESSION['dia']) { //No és el mateix dia? Tornem a generar
                emplenarSessio($date);
            }
        } else {
            emplenarSessio($date);
        }
    } else {
        diaIncorrecte();
    }
} else {
    $date = dataDeAvui();

    if (empty($_SESSION)) {
        emplenarSessio($date);
    }
}

$testWord = "";
if (isset($_POST['testWord'])) {
    $testWord = $_POST['testWord'];
}


$error = null;
if (isset($_GET['error'])) {
    $error = obtenirError();
}

?>

<!DOCTYPE html>
<html lang="ca">

<head>
    <title>PHPògic</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Juga al PHPlògic.">
    <link href="//fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>

<body data-joc="2022-10-07">
    <?php
    $allOk = false;
    if (isset($_SESSION['allOk'])) {
        $allOk = $_SESSION['allOk'];
    }

    if ($allOk && isset($_SESSION['letters'])) {
        if (isset($_GET['forbidden'])) {
            echo "<h1>YOU NEED TO ENABLE COOKIES FOR THIS PAGE TO WORK PROPERLY</h1>";
        }
        if (isset($_SESSION['dia'])) {
            echo "<h1>" . $_SESSION['dia'] . "</h1>";
        }


        if (isset($_GET['sol']) && isset($_SESSION['chuleta'])) {
            echo "<div style='text-align:center'>
            <h1>SOLUCIONS:</h1>";

            foreach ($_SESSION['chuleta'] as $key => $value) {
                echo "<p>Solucio $key -> $value </p>";
            }
            echo "</div>";
        }

        if (isset($_GET['neteja']) && isset($_SESSION['correctWords'])) {
            $_SESSION['correctWords'] = array();
        }

        $randomLetters = $_SESSION['letters'];
    ?>

        <div class="main">
            <h1>
                <a href=""><img src="logo.png" height="54" class="logo" alt="PHPlògic"></a>
            </h1>


            <div class="container-notifications">
                <?php if ($error) {
                    echo "<p class='hide' id='message' >$error</p>";
                } ?>
            </div>
            <form id="meuForm" action="process.php" method="POST">
                <div class="cursor-container">
                    <p id="input-word">
                        <span id="cursor">|</span>
                        <span id="test-word"></span>
                        <input type="hidden" size="1" id="test-worda" name="test-word" value="<?= $testWord ?>">
                    </p>
                </div>

                <div class="container-hexgrid">
                    <ul id="hex-grid">

                        <?php
                        for ($i = 0; $i < 7; $i++) { ?>

                            <li class="hex">
                                <div class="hex-in"><a class="hex-link" data-lletra='<?= $randomLetters[$i] ?>' draggable="false" <?php if ($i === 3) {echo 'id="center-letter"';} ?>>
                                        <p><?= $randomLetters[$i] ?></p>
                                    </a></div>
                            </li>

                        <?php } ?>

                    </ul>
                </div>

                <div class="button-container">
                    <button id="delete-button" type="button" title="Suprimeix l'última lletra" onclick="suprimeix()"> Suprimeix</button>

                    <button type="submit" id="shuffle-button" name="shuffle" value="true" type="button" class="icon" aria-label="Barreja les lletres" title="Barreja les lletres">
                        <svg width="16" aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path fill="currentColor" d="M370.72 133.28C339.458 104.008 298.888 87.962 255.848 88c-77.458.068-144.328 53.178-162.791 126.85-1.344 5.363-6.122 9.15-11.651 9.15H24.103c-7.498 0-13.194-6.807-11.807-14.176C33.933 94.924 134.813 8 256 8c66.448 0 126.791 26.136 171.315 68.685L463.03 40.97C478.149 25.851 504 36.559 504 57.941V192c0 13.255-10.745 24-24 24H345.941c-21.382 0-32.09-25.851-16.971-40.971l41.75-41.749zM32 296h134.059c21.382 0 32.09 25.851 16.971 40.971l-41.75 41.75c31.262 29.273 71.835 45.319 114.876 45.28 77.418-.07 144.315-53.144 162.787-126.849 1.344-5.363 6.122-9.15 11.651-9.15h57.304c7.498 0 13.194 6.807 11.807 14.176C478.067 417.076 377.187 504 256 504c-66.448 0-126.791-26.136-171.315-68.685L48.97 471.03C33.851 486.149 8 475.441 8 454.059V320c0-13.255 10.745-24 24-24z"></path>
                        </svg>
                    </button>
                    <button id="submit-button" type="submit" title="Introdueix la paraula">Introdueix</button>
                </div>


            </form>
            <div class="scoreboard">
                <?php
                $nFuncions = 0;
                if (isset($_SESSION['correctWords'])) {
                    $nFuncions = count($_SESSION['correctWords']);
                }

                ?>

                <div>Has trobat <span id="letters-found"><?= $nFuncions ?></span> <span id="found-suffix"><?php echo $nFuncions > 1 || $nFuncions == 0 ? "funcions" : "funció" ?></span><span id="discovered-text">.</span></div>

                <div>
                    <?php
                    if (isset($_SESSION['correctWords']) && !empty($_SESSION['correctWords'])) {
                        $respostesCorrectes = $_SESSION['correctWords'];
                        echo "Les teves respostes: ( ";
                        foreach ($respostesCorrectes as $index => $word) {

                            if ($index === array_key_last($respostesCorrectes)) {
                                echo $index, ' )';
                            } else {
                                echo $index, " , ";
                            }
                        }
                    }

                    ?>
                </div>
                <div id="score"></div>
                <div id="level"></div>
            </div>
        </div>
        <script>
            function amagaError() {
                if (document.getElementById("message"))
                    document.getElementById("message").style.opacity = "0"
            }

            function afegeixLletra(lletra) {
                document.getElementById("test-word").innerHTML += lletra;
                document.getElementById("test-worda").value += lletra;
            }

            function suprimeix() {
                document.getElementById("test-word").innerHTML = document.getElementById("test-word").innerHTML.slice(0, -1);
                console.log(document.getElementById("test-word").innerHTML);
                document.getElementById("test-worda").value = document.getElementById("test-worda").value.slice(0, -1);
            }

            window.onload = () => {
                // Afegeix funcionalitat al click de les lletres
                Array.from(document.getElementsByClassName("hex-link")).forEach((el) => {
                    el.onclick = () => {
                        afegeixLletra(el.getAttribute("data-lletra"))
                    }
                })

                setTimeout(amagaError, 2000)

                //Anima el cursor
                let estat_cursor = true;
                setInterval(() => {
                    document.getElementById("cursor").style.opacity = estat_cursor ? "1" : "0"
                    estat_cursor = !estat_cursor
                }, 500)
            }

            document.addEventListener('keydown', logKey);

            function logKey(event) {

                if (event.keyCode == 8) {
                    suprimeix();
                } else if (event.keyCode == 13) {
                    document.getElementById("meuForm").submit();
                }

                var lletra = String.fromCharCode(event.keyCode).toLowerCase();
                var palabra = document.getElementById('test-word');
                Array.from(document.getElementsByClassName('hex-link')).forEach(element => {
                    if (event.keyCode != 8 && element.getAttribute('data-lletra') == lletra) {
                        afegeixLletra(lletra);
                    }
                });

            }

            setInterval(function() {
                let nowHour = new Date().getHours()
                let nowMinuts = new Date().getMinutes()
                let nowSecunds = new Date().getSeconds()

                console.log("Hour " + nowHour + " Minutes " + nowMinuts + " Secunds " + nowSecunds)

                if (nowHour == 23 && nowMinuts == 59 && nowSecunds >= 58) {
                    location.reload();
                }
            }, 1000);
        </script>

    <?php
    } else {
        echo "<h1>DIA INCORRECTE 😊</h1><h1>FORMAT: 2022.11.30<h1>";
    }
    ?>
</body>

</html>