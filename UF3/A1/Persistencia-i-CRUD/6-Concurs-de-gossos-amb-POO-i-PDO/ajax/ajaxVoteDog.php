<?php
require_once("../classes/Database.php");
require_once("../classes/User.php");
require_once("../utils/utilFunctions.php");
require_once("../classes/Vote.php");

$response = array();
$response['success'] = [];
$response['errors']  = [];
$response['dogName'] = "";
if (isset($_POST['phaseId']) && isset($_POST['sessionId']) && isset($_POST['dogId'])) {
    $dogId = $_POST['dogId'];
    $sessionId = $_POST['sessionId'];
    $phaseId = $_POST['phaseId'];
    $vote = new Vote($dogId, $phaseId, $sessionId);

    try {
        
        if ($vote->addVoteToDB()) {
            $response['success'][] = "Vot entrat correctament";

            if(isset($_POST['dogName'])){
                $response['dogName'] = $_POST['dogName'];
            }

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
