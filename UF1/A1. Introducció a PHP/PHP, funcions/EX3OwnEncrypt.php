<?php

$string = "🐘Alex tot be?";
echo "<br>Abans d'encriptar: $string <br>";
$secretKey = "Lisy";
$ip = getIPAddress();

$stringEncriptada = encrypt($string, $secretKey, $ip);

echo "<br>després d'encriptar $stringEncriptada <br>";

//Per simular que no sap la clau i la ip es diferent:
/*
$secretKey = "wrongKey";
$ip ="126.333.111.50";
*/
//Per simular que sap la clau i la ip es igual:
/* 
$secretKey = "Lisy";
$ip =getIPAddress();
*/

$stringDesencriptada = (decrypt($stringEncriptada, $secretKey, $ip));

echo "<br>després de desencriptar $stringDesencriptada <br>";

/**
 *
 * Encripta una string i la converteix a
 * alfanumeric (base 62)
 * @return string
 *
 */
function encrypt($string, $secretKey, $ip)
{

    $string = mb_strrev($string);
    $string = swapArray($string);
    $string = stringtoBase62($string, $secretKey, $ip);
    return $string;
}
/**
 *
 * A partir d'una string, te la decripta
 * @return string
 *
 */
function decrypt($string, $secretKey, $ip)
{

    $string = base62toString($string, $secretKey, $ip);
    $string = swapArray($string);
    $string = mb_strrev($string);

    return $string;
}

/**
 *
 * Passa string a alfanumeric, tot concatenant l'ip al principi i secretKey al final
 * i un seguit de numeros i lletres random despres de la clau secreta
 *
 * @param  string $string String a convertir 
 * @param  string $secretKey clau secreta per desencriptar
 * @param  string $ip ip del usuari
 * @return string
 *
 */
function stringtoBase62($string, $secretKey, $ip)
{
    $arrayLiante = [];
    for($i=0;$i<5;$i++){
        $rand = substr(md5(microtime()),rand(0,26),5);
        $arrayLiante[]=$rand;
    }

    $string = $ip . $string . $secretKey.implode($arrayLiante);
    $string = base64_encode($string);
    $string = base64to62($string);
    return $string;
}

/**
 *
 * Passa alfanumeric (base 62) a string
 *
 * @param  string $string String a convertir 
 * @param  string $secretKey clau secreta per desencriptar
 * @param  string $ip ip del usuari
 * @return string
 *
 */
function base62toString($string, $secretKey, $ip)
{
$string = base62to64($string);
    $string = base64_decode($string);

    $string = preg_replace('/' . $ip . '/', "", $string, 1);
    /*
    Si la ip no la troba, no fara un replace, i per tant, depen de l'ip per desencriptar
    el preg_replace em permet que nomes ho canvii un cop, per tant poden posar 127.etc etc com a frase i es mante
    */
    $lastOcurrenceSK = strrpos($string, $secretKey);

    //Si la clau secreta no es bona, l'array liante d'abans fara que sigui una frase molt rara
    if ($lastOcurrenceSK) {
        $string = substr($string, 0, $lastOcurrenceSK);
    }

    return $string;
}


/**
 *
 * Agafa una string i barreja la primera posicio amb la ultima, segona amb penultima...
 *
 * @param  string $string String a barrejar 
 * @return string
 *
 */
function swapArray($string)
{

    $array = mb_str_split($string, 1, 'UTF-8');
    $arrayLength = count($array) - 1;
    for ($i = 0; $i <= $arrayLength / 6; $i++) {
        $aux = $array[$i];
        $array[$i] = $array[$arrayLength - $i];
        $array[$arrayLength - $i] = $aux;
    }

    return implode($array);
}

/**
 *
 * Gira una string UTF-8
 *
 * @param  string $string String a girar 
 * @param  string $encoding codificacio de la string
 * @return string
 *
 */
function mb_strrev($string, $encoding = null)
{
    if ($encoding === null) {
        $encoding = mb_detect_encoding($string);
    }

    $length = mb_strlen($string, $encoding);
    $reversed = '';
    while ($length-- > 0) {
        $reversed .= mb_substr($string, $length, 1, $encoding);
    }

    return $reversed;
}

/**
 *
 * Aconsegueix l'ip de l'usuari
 * (funcio no segura, es pot bypass)
 * @return string
 *
 */
function getIPAddress()
{
    //whether ip is from the share internet  
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    //whether ip is from the proxy  
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    //whether ip is from the remote address  
    else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function base64to62($string)
{

    $array = str_split($string);

    for ($i = 0; $i < strlen($string); $i++) {
        $char = $array[$i];
        if ($char == "=") {
            $array[$i] = "UnUIG";
        } else if ($char == "/") {
            $array[$i] = "Una0Bar";
        } else if ($char == "+")
        $array[$i] = "UnM3ais";
    }

    return implode($array);
}

function base62to64($string)
{
$igual = "UnUIG";
$mes = "UnM3ais";
$barra = "Una0Bar";

    $string = preg_replace('/' . $igual . '/', "=", $string);
    $string = preg_replace('/' . $mes . '/', "+", $string);
    $string = preg_replace('/' . $barra . '/', "/", $string);

return ($string);

}
