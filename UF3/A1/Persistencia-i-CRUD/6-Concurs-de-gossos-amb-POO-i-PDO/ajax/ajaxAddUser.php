<?php
require_once("../classes/Database.php");
require_once("../classes/User.php");
require_once("../utils/utilFunctions.php");

$response = array();
$response['success'] = [];
$response['errors']  = [];
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $filledFields = !empty($username) && !empty($password);
    if (!$filledFields) {
        $response['errors'][] = "Falten camps per emplenar";
    } else {
        try {

            $user = new User($username, $password);
            if($user->insertToDB()){
                $response['success'][] = "Usuari insertat correctament";
                $response['addUserForm'] = addUserForm();
            }else{
                $response['errors'][] = "Error en la base de dades";
            }
            
        } catch (PDOException $e) {
            $response['errors'][] = "Usuari ja existent";
        }
    }
} else {
    $response['errors'][] = "Error formulari";
}

echo (json_encode($response));
