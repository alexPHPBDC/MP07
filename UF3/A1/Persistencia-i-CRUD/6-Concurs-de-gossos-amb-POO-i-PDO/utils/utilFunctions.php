<?php

function addDogForm()
{
    return '<form id="addDog" enctype="multipart/form-data">
<div class="row">
    <div class="col-2">
        <label class="form-label">Nom
            <input class="form-control" type="text" placeholder="Joselito" name="name">
        </label>
    </div>

    <div class="col-2"> <label class="form-label">Imatge<input type="file" class="form-control" placeholder="Imatge" name="image"></div></label>
    <div class="col-2"> <label class="form-label">Amo<input type="text" class="form-control" placeholder="Amo" name="owner"></div></label>
    <div class="col-2"> <label class="form-label">Raça<input type="text" class="form-control" placeholder="Raça" name="breed"></div></label>
    <div class="col-2"><label class="form-label"><span style="visibility:hidden">Enviar</span><input class="form-control btn btn-danger float-right" type="submit"></label></div>
</div>

</form>';
}

function addUserForm()
{

    return '<form id="addUser">
    <input type="text" placeholder="Nom" name="username">
    <input type="password" placeholder="Contrassenya" name="password">
    <input type="submit" value="Crea usuari">
</form>';
}

function UpdateDogForms(array $dogs)
{
    $allForms = "";
    foreach ($dogs as $dog) {
        $allForms .= "<form id=f_{$dog->id} enctype='multipart/form-data'>
        <div class='row'>                    
        <input type='hidden' name='id' value={$dog->id}>
                            <div class='col-2'>
                            <label class='form-label'>Nom
                            <input class='form-control' type='text' placeholder='Nom' name='name' value={$dog->name}>
                            </label>
                            </div>
                            
                            <div class='col-2'>
                            <label class='form-label'>Imatge
                            <img class='form-control dog' src='{$dog->image}' alt='Dog photo'>
                            <input style='display:none' type='file' placeholder='Imatge' name='image' value=''>
                            </label>
                            </div>
                            <div class='col-2'>
                            <label class='form-label'>Amo
                            <input class='form-control' type='text' placeholder='Amo' name='owner' value={$dog->owner}>
                            </label>
                            </div>
                            <div class='col-2'>
                            <label class='form-label'>Raça
                            <input class='form-control' type='text' placeholder='Raça' name='breed' value={$dog->breed}>
                            </label>
                            </div>
                            <div class='col-2'>
                            <label class='form-label'><span style='visibility:hidden'>Modifica</span>
                            <input class='form-control' type='button' name='f_{$dog->id}' value='Modifica' onclick='ajaxUpdateDog(this.name)'>
                            </label>
                            </div>
                            </div>
                            </form>";
    }

    return $allForms;
}

function FormVotarGos($sessionID,$phaseId,$dogId,$idGosVotat="")
{
    $isSelected = $idGosVotat == $dogId ? "selected" : "";
    $dog = Dog::getDogFromDB($dogId);
    $formulari = "";
    if($dog){
    $formulari .= "<form id={$dog->id}>
    <input type='hidden' name='phaseId' value='$phaseId'>
    <input type='hidden' name='sessionId' value='$sessionID'>
    <input type='hidden' name='dogId' value='$dogId'>
    <input type='hidden' name='dogName' value='$dog->name'>
        <button type='button' name='$dogId' id='opt-$dogId' onclick='ajaxVoteDog(this.name)'></button>
        <label id='label-$dogId' for='opt-$dogId' class='opt-$dogId $isSelected'>
            <div class='row'>
                <div class='column'>
                    <div class='right'>
                        <span class='circle'></span>
                        <span class='text'>$dog->name</span>
                    </div>
                    <img class='dog' alt='$dog->name' src='$dog->image'>
                </div>
            </div>
        </label>
        </form>";
    }
    return $formulari;

}

function uploadImage($image, $target_file)
{
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $errors = [];

    $check = getimagesize($image["tmp_name"]);
    if ($check !== false) {

        $uploadOk = 1;
    } else {
        $errors[] = "El fitxer no és una imatge";
    }




    // Check file size
    if ($image["size"] > 500000) {
        $errors[] = "Imatge massa grossa :)";
    }

    // Allow certain file formats
    $acceptedFormats = ["jpg" => true, "png" => true, "jpeg" => true, "gif" => true];
    if (!isset($acceptedFormats[$imageFileType])) {
        $errors[] = "Només admeto JPG, JPEG, PNG I GIF";
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $errors[] = "El teu fitxer no s'ha penjat :(";
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($image["tmp_name"], $target_file)) {
        } else {
            $errors[] = "Hi ha hagut un error penjant l'imatge.";
        }
    }

    return ["errors" => $errors];
}
