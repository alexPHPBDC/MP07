<?php

//retrieving
function llegeix_de_disc() {
    $file = 'my_array.php';
    $var = null;
    if ( file_exists($file) ) {
        $var = json_decode(file_get_contents($file), true);
    } else {
        $var = array();
    }
    
    return $var;
}

// storing
function persisteix_a_disc($var) {
    $file = 'my_array.php';
    file_put_contents($file,json_encode($var));
}

?>