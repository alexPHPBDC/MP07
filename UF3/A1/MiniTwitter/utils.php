<?php
/**
 * @param string $string var
 * @return string returns POST variable or empty
 */
function obtenirVariablePOST(string $string): string
{
    $var = "";
    if (isset($_POST[$string])) {
        $var = $_POST[$string];
    }

    return $var;
}

/**
 * @param string $string var
 * @return string returns GET variable or empty
 */
function obtenirVariableGET(string $string): string
{
    $var = "";
    if (isset($_GET[$string])) {
        $var = $_GET[$string];
    }

    return $var;
}



