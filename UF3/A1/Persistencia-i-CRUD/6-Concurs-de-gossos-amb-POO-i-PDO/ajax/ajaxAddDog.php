<?php
require_once("../classes/Database.php");
require_once("../classes/Dog.php");
require_once("../utils/utilFunctions.php");
$response = array();
$response['success'] = [];
$response['errors']  = [];
if (isset($_POST['name']) && isset($_FILES['image']) && isset($_POST['owner']) && isset($_POST['breed'])) {
    $name = $_POST['name'];
    $image = $_FILES['image'];
    $owner = $_POST['owner'];
    $breed = $_POST['breed'];

    $filledFields = !empty($name) && !empty($owner) && !empty($breed);
    if (!$filledFields) {
        $response['errors'][] = "Falten camps per emplenar";
    } else {

        $target_file = "/var/www/html/MP07/UF3/A1/Persistencia-i-CRUD/6-Concurs-de-gossos-amb-POO-i-PDO/img/" . $name . "-" . basename($image["name"]);
        //Si no hi ha errors i m'han cambiat la imatge, la pujo.


        if ($image['size'] != 0) {
            $uploadingInfo = uploadImage($_FILES['image'], $target_file);

            if (isset($uploadingInfo["errors"]) && !empty($uploadingInfo["errors"])) {
                foreach ($uploadingInfo["errors"] as $error) {
                    $response['errors'][] = $error;
                }
            }
        }else{
            $response['errors'][] = "No es pot deixar l'imatge buida";
        }
        if (empty($response['errors'])) {
            try {

                $dog = new Dog("",$name,$target_file,$owner,$breed);
            
                if ($dog->insertToDB()) {
                    $response['success'][] = "Usuari insertat correctament.";
                    $dogs = Dog::getDogsFromDB();
                    $concursants = $dogs ? UpdateDogForms($dogs) : "";
                    $response['concursants'] = $concursants;
                    $response['addDogForm'] = addDogForm();
                } else {
                    $response['errors'][] = "Error en la base de dades";
                }


                //todo ADD IT so that can be modified
            } catch (PDOException $e) {
                $response['errors'][] = "Usuari ja creat";
            }
        }
    }
} else {
    $response['errors'][] = "error formulari";
}

echo (json_encode($response));
