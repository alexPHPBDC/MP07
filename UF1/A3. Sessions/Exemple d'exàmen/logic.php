<?php

include_once("funcions.php");

if(isset($_COOKIE["connCounter"])){
    $val = $_COOKIE["connCounter"];
    $val +=1;
    setcookie("connCounter", $val);
}else{
    $val = 1;
    setcookie("connCounter", $val);
}

//retrieving
$var = llegeix_de_disc();

//afegir dades darrera connexió
$ip_remota = $_SERVER['REMOTE_ADDR']; //todo: obtenir l'adreça remota del navegador
$navegador = $_SERVER['HTTP_USER_AGENT']; //obtenir el tipus de navegador que s'hi connecta
$txt = "Connexió $val des de $ip_remota using $navegador";
$var[] = $txt;

$taula = crearTaula($var);
echo $taula;

persisteix_a_disc($var);

function crearTaula($var)
{
    $table = "<table style='border: 1px solid black;'>";
    foreach ($var as $conn) {
        $table .= " <tr><td>$conn</td></tr>";
    }
    $table .= "</table>";
    return $table;
}
