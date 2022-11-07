<?php

function decrypt($string)
{
    $abecedari = "abcdefghijklmnopqrstuvwxyz";
    $stringSplitted = spliceAndInvert($string);
    $stringSplitted = implode($stringSplitted);
    $stringSplitted = str_split($stringSplitted);
    foreach ($stringSplitted as $index => $letter) {

        if (stripos($abecedari, $stringSplitted[$index])) {
            $indexInitial = stripos($abecedari, $stringSplitted[$index]);
            $indexZ = stripos($abecedari, "Z");
            $stringSplitted[$index] = $abecedari[$indexZ - $indexInitial];
        }
    }
    return implode($stringSplitted);
}

function spliceAndInvert($string)
{
    $array = str_split($string, 3);
    //Ara tens un array tal que Array[0]="hol" Array[1]="a b" etc

    foreach ($array as $clau => $valor) {
        $array[$clau] = strrev($valor);
    }

    return $array;
}

$sp = "kfhxivrozziuortghrvxrrkcrozxlwflrh";
$mr = " hv ovxozwozv vj o vfrfjvivfj h vmzvlo e hrxvhlmov oz ozx.vw z xve hv loqvn il hv lmnlg izxvwrhrvml ,hv b lh mv,rhhv mf w zrxvlrh.m";


echo decrypt($sp);
echo "<br>";
echo decrypt($mr);
