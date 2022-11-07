<!DOCTYPE html>
<html lang="ca">

<head>
    <meta http-equiv="Content-Type" content="text/html;" charset=UTF-8">
    <link rel="stylesheet" type="text/css" href="style.css" />
    <title>Calculadora</title>
</head>

<?php
/** 
 * ATTENTION: if cursor is moved, it will act as if you want to change something
 * from result, therefore not making any automatic actions  
 * (such as adding brackets, multiply signs, etc.)
 */

$digitExists = isset($_POST['digit']);
$pantallaExists = isset($_POST['pantalla']);
$actionExists = isset($_POST['action']);
$cursorExists = isset($_POST['cursor']);
$cursorPositionExists = isset($_POST['cursorPosition']);
$pressedEqualExists = isset($_POST['hasPressedEqual']);

$shouldClear = $pressedEqualExists && $digitExists && $_POST['hasPressedEqual'] == "1";
$errorShown = $pantallaExists && str_contains($_POST['pantalla'], "ERROR");
$infShown = $pantallaExists && str_contains($_POST['pantalla'], "INF");

if ($shouldClear || $errorShown || $infShown) {
    $_POST['pantalla'] = "";
}

$equalPressed = "0";

if ($pantallaExists) {
    $cursorPosition = 0;
    $resultat = $_POST['pantalla'];
    $cursorMoved = mb_strpos($resultat, '|') != mb_strlen($resultat) - 1;


    if ($digitExists) {
        $resultat = funcionalitat($_POST['digit'], $cursorMoved, $resultat, "/^π|e|\)$/");
    }


    if ($actionExists) {
        $action = $_POST['action'];

        switch ($action) {
            case 'C':
                $resultat = "";
                $cursorPosition = 0;
                break;
            case '=':
                $equalPressed = "1";
                $resultat = domath($resultat);
                break;
            case 'SIN':
            case 'COS':
            case 'TAN':
                $resultat = funcionalitatSinCosTan($resultat, $action, $cursorMoved);
                break;
            case 'π':
            case 'e':
                $resultat = funcionalitat($action, $cursorMoved, $resultat, '/^[0-9]|π|e|\)$/');
                break;
            case '(':
                $resultat = funcionalitat($action, $cursorMoved, $resultat, '/^[0-9]$/');
                break;
            case '-':
                $resultat =  funcionalitatDosNegatius($action, $cursorMoved, $resultat);
                break;
            case 'x²':
                $resultat = powerOf($resultat, "2");
                break;
            case '⌫':
                $resultat = borrarCaracter($resultat, $cursorMoved, $cursorPosition);
                break;
            default:
                if($cursorMoved){
                    $resultat = putOnCursor($resultat,$action);
                }   else{
                    $resultat .= $action;
                } 
           


                break;
        }
    }

    if ($cursorPositionExists) {
        $resultat = preg_replace('!\|!', '', $resultat); // Replace cursor with empty so that cursors dont stack up
        $cursorPosition = $_POST['cursorPosition'];

        $cursorPosition = getCursorPosition($cursorPosition, $cursorExists, $resultat);


        $array = mb_str_split($resultat);
        array_splice($array, $cursorPosition, 0, "|");
        $resultat = implode($array);
    }
} else {
    $cursorPosition = 0;
    $resultat = "";
}

/**
 * Checks cursor "|" position
 * @return int the current cursor position 
 */
function getCursorPosition(int $cursorPosition, bool $cursorExists, string $resultat): int
{

    if (!($cursorExists)) {

        $cursorPosition = mb_strlen($resultat);
    } else {

        $cursor = $_POST['cursor'];

        if ($cursor == '<') {

            if ($cursorPosition > 0) {
                $cursorPosition = $cursorPosition - 1;
            }
        } else if ($cursor == '>') {

            if ($cursorPosition < mb_strlen($resultat)) {
                $cursorPosition = $cursorPosition + 1;
            }
        }
    }

    return $cursorPosition;
}

/**
 * Given a math operation, performs it using eval function,
 * if its not a math operation, throws and returns error.
 * 
 * @param string $resultat
 * @return string $resultat
 */
function doMath(string $resultat): string
{
    $resultat = closeBrackets($resultat);

    try {
        $number = '(\d+(.\d+)?|e|π)'; // Number is 3 and can be 3.5 also can be π or e
        $functions = '(?:sin?|cos?|tan?)'; // Allowed PHP functions
        $operators = '[\.\+\/*-]'; //Allowed operations
        $regexp = '/^(((?5)?' . $number . '|' . $functions . '\((?1)+\)|\((?1)+\))(' . $operators . '(?2)*)*)+$/'; // Final regexp

       //?1 means whole regexp,
       //?2 means regexp before operators, so for example sin(3)
       //?5 is the operators

       $resultat = preg_replace('!\|!', '', $resultat); // Delete cursor
      
        if (preg_match($regexp, $resultat)) {
            $resultat = preg_replace('!π!', 'M_PI', $resultat); // Replace pi with constant
            $resultat = preg_replace('!e!', 'M_E', $resultat); //Replace e with constant
            
            eval('$resultat = ' . $resultat . ';');

            if (is_float($resultat)) {
                $resultat = number_format((float)$resultat, 4, '.', '');
            }
        } else {
            $resultat = "ERROR";
        }
    } catch (DivisionByZeroError $e) {
        $resultat = "INF";
    } catch (Throwable $t) {
        $resultat = "ERROR";
    }
echo $resultat;
    if($resultat == "inf"){return "INF";} //0^-1 can give inf
    return $resultat;
}

/**
 * Replaces the cursor with the desired action / digit
 * @param string $string
 * @param string $whatToPut
 * @return string
 */
function putOnCursor(string $string, string $whatToPut): string
{
    $array = str_split($string);
    array_splice($array, strpos($string, '|'), 0, $whatToPut);
    $string = implode($array);
    return $string;
}


/**
 * Given a string, returns itself enclosed in brackets and
 * raised to the power desired, without mathematically doing it.
 * @param $resultat string to pow
 * @param $toPow the pow to raise
 * @return string
 */
function powerOf(string $resultat, string $toPow): string
{

    $resultat = closeBrackets($resultat);
    $resultat = "($resultat)**$toPow";
    return $resultat;
}


/**
 * Given a string, adds brackets to the end in order to accept
 * not closed brackets. 3*(2 -> 3*(2)
 * 
 * @param string $resultat String to close brackets to
 * @return string
 */
function closeBrackets(string $resultat): string
{
    $openBracketsCount = substr_count($resultat, '(');
    $closedBracketsCount = substr_count($resultat, ')');
    $diff = $openBracketsCount - $closedBracketsCount;

    if ($diff < 0) {
        return $resultat;
    }

    return $resultat . str_repeat(")", $diff);
}

/**
 * Checks if the string should be encapsulated
 * checking its last char, which must be
 * numeric to return true
 * 
 * @param string $resultat String to check in
 * @return bool
 */
function shouldEncapsulate(string $resultat): bool
{
    $resultat = preg_replace('!\|!', '', $resultat);
    $lastChar = mb_substr($resultat, -1);
    $isPiorE = $lastChar == "π" || $lastChar == "e";
    return is_numeric($lastChar) || $lastChar == ')' || $isPiorE;
}


/**
 * Checks if the last character of the string
 * passes the regExp Test. Given π could be passed in 
 * the regexp,multibyte substr is used.
 * 
 * @param string $resultat String to check in
 * @param string $regExp regexp of the last character 
 * @return bool
 */
function endsWith(string $resultat, string $regExp): bool
{

    $resultat = preg_replace('!\|!', '', $resultat); // Replace cursor with empty to prevent weird behaviour

    if (mb_strlen($resultat) == 0) {
        return false;
    }

    return preg_match($regExp, mb_substr($resultat, -1));
}

/**
 * Given a string, if necessary encloses it between php sin() function
 * else just adds it to the end of the string
 * 
 * @return string
 */
function funcionalitatSinCosTan(string $resultat, string $action, bool $cursorMoved): string
{

    if ($cursorMoved) {

        $action == "SIN" ? $resultat = putOnCursor($resultat, "sin(") : ($action == 'COS' ? $resultat = putOnCursor($resultat, "cos(") : $resultat = putOnCursor($resultat, "tan("));
    } else {
        if (shouldEncapsulate($resultat)) {
            $action == "SIN" ? $resultat = "sin(" . $resultat . ")" : ($action == "COS" ? $resultat =  "cos(" . $resultat . ")" : $resultat = "tan(" . $resultat . ")");
        } else {

            $action == "SIN" ? $resultat .= "sin(" : ($action == "COS" ? $resultat .= "cos(" : $resultat .= "tan(");
        }
    }
    return $resultat;
}

/**
 * Checks if last char matches regexp, if it does appends multipy symbol,
 * this is done to make it more easy to use
 * @return string 
 */
function funcionalitat(string $action, bool $cursorMoved, string $resultat, string $regexp): string
{
    if ($cursorMoved) {

        $resultat = putOnCursor($resultat, $action);
    } else {
        if (endsWith($resultat, $regexp)) {
            $resultat .= "*";
        }

        $resultat .= $action;
    }

    return $resultat;
}

/**
 * Checks if two minus signs are placed one after the other,
 * if they do, puts an opening bracket to avoid eval() failure
 * 
 * @param string $action
 * @param boolean $cursorMoved has the cursor moved?
 * @param string $resultat
 * @return string $resultat
 */
function funcionalitatDosNegatius($action, $cursorMoved, $resultat)
{
    if ($cursorMoved) {

        $resultat = putOnCursor($resultat, $action);
    } else {
        if (endsWith($resultat, '/^\-$/')) {
            $resultat .= "(";
        }

        $resultat .= "-";
    }

    return $resultat;
}


/**
 * Deletes char, if $cursorMoved deleted it at cursor's position
 *
 * @return string $resultat The cropped string
 */
function borrarCaracter($resultat, $cursorMoved)
{

    if (mb_strlen($resultat) == 1) {
        return $resultat;
    }

    if ($cursorMoved) {

        $resultat = borrarenCursor($resultat);
    } else {
        $resultat = preg_replace('!\|!', '', $resultat); // Replace cursor with empty to prevent weird behaviour
        $resultat = mb_substr($resultat, 0, -1);
    }

    return $resultat;
}


/**
 * Deletes the char before cursor's index
 * 
 * @return string resultat The cropped string
 */
function borrarenCursor($resultat)
{
    $cursorPos = mb_strpos($resultat, '|');

    if ($cursorPos > 0) {
        $resultat = mb_substr_replace($resultat, '', $cursorPos - 1, 1);
    }

    return $resultat;
}


/**
 * @param string $string
 * @param string $replacement
 * @param int $start
 * @param int $length
 * @return string
 * @author sallaizalan al github
 */
function mb_substr_replace($string, $replacement, $start, $length = 1)
{
    $startString = mb_substr($string, 0, $start, "UTF-8");
    $endString = mb_substr($string, $start + $length, mb_strlen($string), "UTF-8");

    $out = $startString . $replacement . $endString;

    return $out;
}


/*TODO:
make delete char hold the cursor place
*/
?>


<body>
    <div class="container">
        <form action="" name="calc" class="calculator" method="POST">
            <input type="hidden" name="hasPressedEqual" value="<?= $equalPressed ?>">
            <input type="hidden" name="cursorPosition" value="<?= $cursorPosition ?>">
            <input type="text" class="value" readonly name="pantalla" value="<?= $resultat ?>" />

            <span class="num position"><input type="submit" name="cursor" value="<"></span>
            <span class="num position"><input type="submit" name="cursor" value=">"></span>

            <span class="num space"><input type="submit" name="action" value=""></span>

            <span class="num"><input type="submit" name="action" value="⌫"></span>

            <span class="num"><input type="submit" name="action" value="("></span>
            <span class="num"><input type="submit" name="action" value=")"></span>
            <span class="num"><input type="submit" name="action" value="SIN"></span>
            <span class="num"><input type="submit" name="action" value="COS"></span>
            <span class="num"><input type="submit" name="action" value="e"></span>
            <span class="num"><input type="submit" name="action" value="x²"></span>
            <span class="num"><input type="submit" name="action" value="TAN"></span>
            <span class="num"><input type="submit" name="action" value="π"></span>
            <span class="num clear"><input type="submit" name="action" value="C"></span>
            <span class="num"><input type="submit" name="action" value="/"></span>
            <span class="num"><input type="submit" name="action" value="*"></span>
            <span class="num"><input type="submit" name="digit" value="7"></span>
            <span class="num"><input type="submit" name="digit" value="8"></span>
            <span class="num"><input type="submit" name="digit" value="9"></span>
            <span class="num"><input type="submit" name="action" value="-"></span>
            <span class="num"><input type="submit" name="digit" value="4"></span>
            <span class="num"><input type="submit" name="digit" value="5"></span>
            <span class="num"><input type="submit" name="digit" value="6"></span>
            <span class="num plus"><input type="submit" name="action" value="+"></span>
            <span class="num"><input type="submit" name="digit" value="1"></span>
            <span class="num"><input type="submit" name="digit" value="2"></span>
            <span class="num"><input type="submit" name="digit" value="3"></span>
            <span class="num"><input type="submit" name="digit" value="0"></span>
            <span class="num"><input type="submit" name="digit" value="00"></span>
            <span class="num"><input type="submit" name="action" value="."></span>
            <span class="num equal"><input type="submit" name="action" value="="></span>
        </form>
    </div>
</body>

