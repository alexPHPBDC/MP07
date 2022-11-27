<?php

require_once("../classes/Database.php");
require_once("../classes/Vote.php");

$response = array();
$response['error'] = [];
$response['success'] = [];

if (isset($_POST['delete'])) {

    try {
        if (Vote::removeAllVotes()) {
            $response['success'][] = "Tots els vots han set borrats";
        } else {
            $response['error'][] = "Error en la base de dades";
        }
    } catch (PDOException $e) {
        $response['error'][] = "Error en la sentència SQL";
    }
} else {
    $response['error'][] = "Error formulari";
}
echo (json_encode($response));
