<?php

$desempatadors = array();
$desempatadors[] = "hola";
var_dump($desempatadors);
echo "<br><br>";



$numeroRandom = rand(0, count($desempatadors) - 1);
                                array_splice($desempatadors, $numeroRandom, 1);

                                var_dump($desempatadors);