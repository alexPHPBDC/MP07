<?php

require_once("../classes/Database.php");
require_once("../classes/Vote.php");

$response = array();
$response['error'] = [];
$response['success'] = [];

if (isset($_POST['phaseInfo'])) {
    $phaseInfo = json_decode($_POST['phaseInfo']);
    $phaseId = $phaseInfo->id;
    $phaseNumber = $phaseInfo->phaseNumber;
    try {
        if (Vote::removeVotesFromPhase($phaseId)) {
            $response['success'][] = "S'han borrat els vots de la fase $phaseNumber";
        } else {
            $response['errors'][] = "Error en la base de dades";
        }
    } catch (PDOException $e) {
        $response['errors'][] = "Error en la base de dades";
    }
} else {
    $response['errors'][] = "Error formulari";
}
echo (json_encode($response));
