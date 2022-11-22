<?php
require_once("../classes/Database.php");
require_once("../classes/Dog.php");

$response = array();
$response['success'] = [];
$response['errors']  = [];
if (isset($_POST['name']) && isset($_FILES['image']) && isset($_POST['owner']) && isset($_POST['breed'])) {
    $name = $_POST['name'];
    $image = $_FILES['image'];
    $owner = $_POST['owner'];
    $breed = $_POST['breed'];

    $filledFields = !empty($name) && !empty($image) && !empty($owner) && !empty($breed);
    if (!$filledFields) {
        $response['errors'][] = "Falten camps per emplenar";
    } else {

        $target_file = "../img/" . $name . "-" . basename($image["name"]);
        $uploadingInfo = uploadImage($_FILES['image'], $target_file);
        if (isset($uploadingInfo["errors"]) && !empty($uploadingInfo["errors"])) {
            foreach ($uploadingInfo["errors"] as $error) {
                $response['errors'][] = $error;
            }
        } else if (isset($uploadingInfo["success"]) && !empty($uploadingInfo["success"])) {
            try {

                $dog = new Dog();
                $dog->setVariables("", $name, $target_file, $owner, $breed);
                $dog->insertToDB();
                $response['success'][] = "Usuari insertat correctament.";

                //todo ADD IT so that can be modified
            } catch (PDOException $e) {
                $response['errors'][] = "Usuari ja creat";
            }
        }
    }
} else {
    $response['errors'][] = "error formulari";
}

$dogs = Dog::getDogsFromDB();
$concursants = "";
foreach ($dogs as $dog) {
    $concursants .= "<form id=f_{$dog->id} action='ajaxAdministrarConcursants(this)'>
                            <input type='hidden' name='id' value={$dog->id}>
                            <input type='text' placeholder='Nom' name='name' value={$dog->name}>
                            <input type='text' placeholder='Imatge' name='image' value={$dog->imageUrl}>
                            <input type='text' placeholder='Amo' name='owner' value={$dog->owner}>
                            <input type='text' placeholder='Raça' name='breed' value={$dog->breed}>
                            <input type='button' name='action' value='Modifica'>
                        </form>";
}

$response['concursants'] = $concursants;
exit(json_encode($response));

function uploadImage($image, $target_file)
{
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $errors = [];
    $success = [];
    $check = getimagesize($image["tmp_name"]);
    if ($check !== false) {
        $success[] = "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        $errors[] = "File is not an image.";
    }


    // Check if file already exists
    if (file_exists($target_file)) {
        $errors[] = "Sorry, file already exists.";
    }

    // Check file size
    if ($image["size"] > 500000) {
        $errors[] = "Sorry, your file is too large.";
    }

    // Allow certain file formats
    $acceptedFormats = ["jpg" => true, "png" => true, "jpeg" => true, "gif" => true];
    if (!isset($acceptedFormats[$imageFileType])) {
        $errors[] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $errors[] = "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($image["tmp_name"], $target_file)) {
            $success[] = "The file " . htmlspecialchars(basename($image["name"])) . " has been uploaded.";
        } else {
            $errors[] = "Sorry, there was an error uploading your file.";
        }
    }

    return ["success" => $success, "errors" => $errors];
}
