<?php
require_once("../classes/Database.php");
require_once("../classes/Dog.php");
require_once("../utils/utilFunctions.php");
$response = array();
$response['success'] = [];
$response['errors']  = [];

if (isset($_POST['id']) && isset($_POST['name']) && isset($_FILES['image']) && isset($_POST['owner']) && isset($_POST['breed'])) {
    $name = $_POST['name'];
    $image = $_FILES['image'];
    $owner = $_POST['owner'];
    $breed = $_POST['breed'];
    $id = $_POST['id'];
    $filledFields = !empty($name) && !empty($owner) && !empty($breed);
    if (!$filledFields) {
        $response['errors'][] = "Falten camps per emplenar";
    } else {
        $target_file = "../img/" . $name . "-" . basename($image["name"]);
        //Si no hi ha errors i m'han cambiat la imatge, la pujo.


        if ($image['size'] != 0) {
            $uploadingInfo = uploadImage($_FILES['image'], $target_file);

            if (isset($uploadingInfo["errors"]) && !empty($uploadingInfo["errors"])) {
                foreach ($uploadingInfo["errors"] as $error) {
                    $response['errors'][] = $error;
                }
            }
        }

        if (empty($response['errors'])) {
            try {

                if ($dog = Dog::getDogFromDB($id)) {

                    if ((($image["name"]) != "")) { //Només cambio la foto si han pujat una foto
                        $dog->image = $target_file;
                    }
                    $dog->name = $name;
                    $dog->owner = $owner;
                    $dog->breed = $breed;
                    
                    if ($dog->updateDogDB()) {
                        $response['success'][] = "Usuari modificat correctament.";
                        $dogs = Dog::getDogsFromDB();
                        $concursants = $dogs ? UpdateDogForms($dogs) : "";
                        $response['concursants'] = $concursants;
                    } else {
                        $response['errors'][] = "Error en la base de dades";
                    }
                } else {
                    $response['errors'][] = "Error en la base de dades";
                }

                //todo ADD IT so that can be modified
            } catch (PDOException $e) {
                $response['errors'][] = "Error en la base de dades";
            }
        }
    }
} else {
    $response['errors'][] = "error formulari";
}

echo (json_encode($response));


