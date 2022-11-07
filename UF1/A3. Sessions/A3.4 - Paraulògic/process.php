<?php
session_start();

if (isset($_POST['shuffle']) && isset($_SESSION['letters'])) {
    $lletres = $_SESSION['letters'];
    $centralKey = $lletres[3];
    unset($lletres[3]); 
    $lletres = array_values($lletres);
    shuffle($lletres);
    array_splice($lletres, 3, 0, $centralKey);

    $_SESSION['letters'] = $lletres;
    header("Location: index.php?", true, 302);
    exit();
}

if (isset($_POST['test-word']) && isset($_SESSION['letters'])) {

    $testWord = $_POST['test-word'];

    $correctWord = isWordCorrect($testWord);

    $lletres = $_SESSION['letters'];
    $centralKey = $lletres[3];

    //Only if strpos not found, simple !strpos wont work due to pos 0.
    if (strpos($testWord, $centralKey)===false) {
        header("Location: index.php?error=middleMissing", true, 303);
        exit();
    }

    if ($correctWord) {
        if (isset($_SESSION['correctWords']) && !isset($_SESSION['correctWords'][$testWord])) {
            $_SESSION['correctWords'][$testWord] = ":)";
            header("Location: index.php?", true, 302);
            exit();
        } else {
            header("Location: index.php?error=alreadyIn&testWord=$testWord", true, 303);
            exit();
        }
    } else {
        header("Location: index.php?error=invalidFunct&testWord=$testWord", true, 303);
        exit();
    }
}

/**
 * Checks if word is inside possible correct words
 * @param string $testWord the word to test
 * @return bool 
 */
function isWordCorrect(string $testWord): bool
{
    if(isset($_SESSION['chuleta'])){
        $functions = $_SESSION['chuleta'];
        $testWord = strtolower($testWord);
        return in_array($testWord, $functions);
    }
    
    return false;
}


//How did we end up here? Control it nonetheless
header("Location: index.php?forbidden=EnableCookiesPlease");
exit();