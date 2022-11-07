<?php

print_r($_REQUEST);
echo "<br>";
foreach ($_REQUEST as $variable => $valor) {

    if (!is_array($valor)) {
        echo "El valor de " . $variable . " és " . $valor . "<br>";
    } else {
        echo $variable . " és un array: <br>";
        foreach ($valor as $index => $value) {
            echo "Valor " . $index . " --> " . $value . "<br>";
        }
    }
}
