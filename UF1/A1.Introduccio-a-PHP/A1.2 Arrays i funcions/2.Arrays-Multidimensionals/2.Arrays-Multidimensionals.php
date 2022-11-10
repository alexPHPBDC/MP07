<?php

function creaMatriu($n)
{
    $array = array();

    for ($row = 0; $row < $n; $row++) {

        for ($col = 0; $col < $n; $col++) {

            if ($col == $row) {
                $array[$row][$col] = "*";
            } else if ($col < $row) {
                $array[$row][$col] = rand(10, 20);
            } else {
                $array[$row][$col] = $row + $col;
            }
        }
    }

    return $array;
}


function mostraMatriu($matriu)
{
    $string = "<table>";
    foreach ($matriu as $key => $value) {
        $string .= "<tr>";
        foreach ($value as $clau => $valor) {
            $string .= "<td>" . $valor . "</td>";
        }
        $string .= "</tr>";
    }
    $string .= "</table>";
    return $string;
}


function transposaMatriu($matriu)
{
    $trasposedArray = array();
    $nRows = count($matriu);

    $matriu = fillWithNulls($matriu);


    for ($row = 0; $row < $nRows; $row++) {
        $nCols = count($matriu[$row]);
        for ($col = 0; $col < $nCols; $col++) {

            $trasposedArray[$col][$row] = $matriu[$row][$col];
        }
    }

    return $trasposedArray;
}

/**
 * Funció necessaria perquè al girar una array anormal es conservin els espais :)
 * També podria agafar el max d'aqui, fer el doble for amb el max i substituir els 
 * llocs undefined i fer $table[$key] ?? null;
 */
function fillWithNulls($matriu)
{

    $max = 0;

    for ($i = 0; $i < count($matriu); $i++) {

        if (count($matriu[$i]) > $max) {
            $max = count($matriu[$i]);
        }
    }

    foreach ($matriu as $key => $value) {
        $matriu[$key] = array_pad($matriu[$key], $max, null);
    }

    return $matriu;
}


//Per provar el funcionament:
$matriu = array(
    array(1, 2, 3, 4),
    array(1, 7, 3),
    array(1, 1),
    array(1, 4, 6, 7, 2, 8),
);
echo mostraMatriu($matriu);
echo "<br><br>";
echo mostraMatriu(transposaMatriu($matriu));
