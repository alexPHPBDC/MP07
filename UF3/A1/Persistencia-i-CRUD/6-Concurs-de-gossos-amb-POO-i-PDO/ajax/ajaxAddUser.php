<?php
require_once("../classes/Database.php");
require_once("../classes/User.php");

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
            $user->insertToDB();
            $response['success'][] = "Usuari insertat correctament";
        } catch (PDOException $e) {
            $response['errors'][] = "Usuari ja existent";
        }
    }
} else {
    $response['errors'][] = "Error formulari";
}

exit(json_encode($response));
