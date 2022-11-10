<?php
$numberArray = array(1, 9, 3, 4, -5, 6);


/**
 * Funcio que retorna fals en cas que hi entre una array amb algun no-enter,
 * primer comprova si hi ha algun no-enter a l'array, tot seguit fa el factorial de
 * totes les posicions (Primer comprovo per no haver de fer molts de factorials i 
 * retornar false després d'haver consumit servidor)
 */
function factorialArray(array $numberArray):bool|array
{
    for ($i = 0; $i < count($numberArray); $i++) {
        if (!is_int($numberArray[$i])) {
            return false;
        }
    }

    for ($i = 0; $i < count($numberArray); $i++) {
        $numberArray[$i] = factorialNumber($numberArray[$i]);
    }
    
    return $numberArray;
}
function factorialNumber(int $number):int
{
    if ($number == 1) {
        return $number;
    } else if ($number == 0) {
        return 1;
    }
    if ($number > 1) {
        return $number * (factorialNumber($number - 1));
    } else if ($number < 1) {
        return $number * (factorialNumber($number + 1));
    }
}

print_r($numberArray);
echo "Array original<br>";
var_dump(factorialArray($numberArray));
echo "Array factorial";
