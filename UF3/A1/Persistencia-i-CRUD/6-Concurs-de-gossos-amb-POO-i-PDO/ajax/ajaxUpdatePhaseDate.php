<?php

require_once("../classes/Database.php");
require_once("../classes/Phase.php");
require_once("../utils/utilFunctions.php");

$response = array();
$response['error'] = [];
$response['success'] = [];
$response['phases'] = "";
if (isset($_POST['endDate']) && isset($_POST['startDate']) && isset($_POST['phaseNumber'])) {
    $phaseNumber = $_POST['phaseNumber'];
    $endDate = strtotime($_POST['endDate']);
    $startDate = strtotime($_POST['startDate']);
    $endDate = date("Y/m/d",$endDate);
    $startDate = date("Y/m/d",$startDate);


    if($endDate <= $startDate){
        $response['errors'][] = "La data de fi ha de ser major a la data d'inici";
    }else if($startDate >= $endDate){
        $response['errors'][] = "La data d'inici a de ser menor a la data de fi";
    }else{

        if(Phase::dateIsAfterPhase($startDate,$phaseNumber-1) && Phase::dateIsBeforePhase($endDate,$phaseNumber+1)){

            Phase::changeDate($startDate,$endDate,$phaseNumber);
            $response['success'][] = "Dades cambiades!";
        }else{

            $response['errors'][] = "Dades solapades, selecciona una altra dada";

        }

    }


if(isset($_POST['date'])){
    $response['phases'] = getStringTotesLesPhases($_POST['date']);
}    

}else{
    $response['errors'][] = "Error formulari";
}

echo (json_encode($response));
