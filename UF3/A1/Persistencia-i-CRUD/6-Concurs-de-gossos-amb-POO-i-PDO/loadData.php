<?php
require_once("classes/Database.php");
require_once("classes/Phase.php");
require_once("classes/Dog.php");
require_once("classes/User.php");
require_once("classes/PhaseContestants.php");
echo "Inserint Dades";

try{
    Database::loadSampleData();
    echo "Tot correcte!";
}catch(PDOException $e){
    echo $e;
}

?>