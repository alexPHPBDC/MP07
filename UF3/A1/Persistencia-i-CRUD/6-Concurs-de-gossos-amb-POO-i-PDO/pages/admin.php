<?php
require_once("../classes/Database.php");
require_once("../classes/Dog.php");
require_once("../classes/Phase.php");
require_once("../classes/User.php");
require_once("../dbFunctions.php");
require_once("../utils/utilFunctions.php");
?>
<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN - Concurs Internacional de Gossos d'Atura</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>

<body>
    <div class="wrapper medium">
        <header>ADMINISTRADOR - Concurs Internacional de Gossos d'Atura</header>
        <div class="admin">
            <div class="admin-row">
                <h1> Resultat parcial: Fase 1 </h1>
                <div class="gossos">
                    <img class="dog" alt="Musclo" title="Musclo 15%" src="../img/g1.png">
                    <img class="dog" alt="Jingo" title="Jingo 45%" src="../img/g2.png">
                    <img class="dog" alt="Xuia" title="Xuia 4%" src="../img/g3.png">
                    <img class="dog" alt="Bruc" title="Bruc 3%" src="../img/g4.png">
                    <img class="dog" alt="Mango" title="Mango 13%" src="../img/g5.png">
                    <img class="dog" alt="Fluski" title="Fluski 12 %" src="../img/g6.png">
                    <img class="dog" alt="Fonoll" title="Fonoll 5%" src="../img/g7.png">
                    <img class="dog" alt="Swing" title="Swing 2%" src="../img/g8.png">
                    <img class="dog eliminat" alt="Coloma" title="Coloma 1%" src="../img/g9.png">
                </div>
            </div>
            <div class="admin-row">
                <h1> Nou usuari: </h1>
                <div id="addUserResponse" class="toast-container"></div>
                <div id="formulariUser">
                    <?php
                    echo addUserForm();
                    ?>
                </div>
            </div>
            <div class="admin-row">
                <h1> Fases: </h1>
                <form class="fase-row">
                    Fase <input type="text" value="1" disabled style="width: 3em">
                    del <input type="date" placeholder="Inici">
                    al <input type="date" placeholder="Fi">
                    <input type="button" value="Modifica">
                </form>

                <form class="fase-row">
                    Fase <input type="text" value="2" disabled style="width: 3em">
                    del <input type="date" placeholder="Inici">
                    al <input type="date" placeholder="Fi">
                    <input type="button" value="Modifica">
                </form>

                <form class="fase-row">
                    Fase <input type="text" value="3" disabled style="width: 3em">
                    del <input type="date" placeholder="Inici">
                    al <input type="date" placeholder="Fi">
                    <input type="button" value="Modifica">
                </form>

                <form class="fase-row">
                    Fase <input type="text" value="4" disabled style="width: 3em">
                    del <input type="date" placeholder="Inici">
                    al <input type="date" placeholder="Fi">
                    <input type="button" value="Modifica">
                </form>

                <form class="fase-row">
                    Fase <input type="text" value="5" disabled style="width: 3em">
                    del <input type="date" placeholder="Inici">
                    al <input type="date" placeholder="Fi">
                    <input type="button" value="Modifica">
                </form>

                <form class="fase-row">
                    Fase <input type="text" value="6" disabled style="width: 3em">
                    del <input type="date" placeholder="Inici">
                    al <input type="date" placeholder="Fi">
                    <input type="button" value="Modifica">
                </form>

                <form class="fase-row">
                    Fase <input type="text" value="7" disabled style="width: 3em">
                    del <input type="date" placeholder="Inici">
                    al <input type="date" placeholder="Fi">
                    <input type="button" value="Modifica">
                </form>

                <form class="fase-row">
                    Fase <input type="text" value="8" disabled style="width: 3em">
                    del <input type="date" placeholder="Inici">
                    al <input type="date" placeholder="Fi">
                    <input type="button" value="Modifica">
                </form>

            </div>

            <div class="admin-row">
                <h1> Modificar concursants: </h1>
                <div id="updateDogResponse" class="toast-container"></div>
                <div id="concursants">
                    <?php
                    $dogs = Dog::getDogsFromDB();
                    if ($dogs) {
                        $formularis = UpdateDogForms($dogs);
                        echo $formularis;
                    } else {
                        echo "<h1>No hi ha gossos :)</h1>";
                    }
                    ?>
                </div>

                <h1> Crear concursants: </h1>
                <div id="addDogResponse" class="toast-container"></div>
                <div id="formulariAddDog">
                    <?php
                    echo addDogForm();
                    ?>
                </div>
            </div>

            <div class="admin-row">
                <h1> Altres operacions: </h1>
                <?php
                $phases = Phase::getAllPhases();

                if ($phases) { ?>
                    <div id="missatgeEsborrarVotsPhase"></div>
                    Esborra els vots de la fase
                    <form id="esborrarVotsFaseEspecifica">
                        <select id="selectVotsFases" name="phaseInfo">
                            <?php

                            foreach ($phases as $phase) {
                            ?>
                                <option value='{"id":"<?= $phase->id ?>","phaseNumber":"<?= $phase->phaseNumber ?>"}'><?= $phase->phaseNumber ?></option>
                            <?php
                            } ?>
                        </select>

                        <input type="submit" value="Esborra">
                    </form>
                <?php }else{
                    echo "No hi ha fases :)";
                } ?>

                <div id="missatgeEsborrarTotsVots"></div>
                <form id="esborrarTotsVots">
                    Esborra tots els vots
                    <input type="hidden" name="delete" value="true">
                    <input type="submit" value="Esborra">
                </form>

            </div>
        </div>
    </div>

</body>

</html>

<script>
    $(document).on('submit', '#esborrarTotsVots', function(e) {
        e.preventDefault();
        var actionUrl = "../ajax/ajaxDeleteAllVotes.php";
        var formData = new FormData(this);
        var missatges = document.getElementById("missatgeEsborrarTotsVots");
        $.ajax({
            url: actionUrl,
            type: 'POST',
            data: formData,
            success: function(data) {
                response = JSON.parse(data);
                if (response['success'].length != 0) {
                    response['success'].forEach(success => {
                        missatges.innerHTML = crearToast(success, "success");
                    })
                } else if (response['errors'].length != 0) {
                    response['errors'].forEach(error => {
                        missatges.innerHTML = crearToast(error, "failure");
                    })
                }


            },
            cache: false,
            contentType: false,
            processData: false
        });

        deleteToasts(3000);

    });

    $(document).on('submit', '#esborrarVotsFaseEspecifica', function(e) {
        e.preventDefault();
        var actionUrl = "../ajax/ajaxDeleteVotesPhase.php";

        var form_data = new FormData();
        form_data.append("phaseInfo", document.getElementById("selectVotsFases").value);

        var missatges = document.getElementById("missatgeEsborrarVotsPhase");
        $.ajax({
            url: actionUrl,
            type: 'POST',
            data: form_data,
            success: function(data) {
                response = JSON.parse(data);
                if (response['success'].length != 0) {
                    response['success'].forEach(success => {
                        missatges.innerHTML = crearToast(success, "success");
                    })
                } else if (response['errors'].length != 0) {
                    response['errors'].forEach(error => {
                        missatges.innerHTML = crearToast(error, "failure");
                    })
                }


            },
            cache: false,
            contentType: false,
            processData: false,

        });

        deleteToasts(3000);

    });


    $(document).on('submit', '#addDog', function(e) {
        var missatges = document.getElementById("addDogResponse");
        missatges.innerHTML = "";
        var concursants = document.getElementById("concursants");
        var formulariDog = document.getElementById("formulariAddDog");
        e.preventDefault();
        var formData = new FormData(this);
        var actionUrl = "../ajax/ajaxAddDog.php";
        $.ajax({
            url: actionUrl,
            type: 'POST',
            data: formData,
            success: function(data) {
                response = JSON.parse(data);
                console.log(response);
                if (response['success'].length != 0) {
                    response['success'].forEach(success => {

                        missatges.innerHTML = crearToast(success, "success");

                    })
                    formulariDog.innerHTML = response['addDogForm'];
                    concursants.innerHTML = response['concursants'];
                } else if (response['errors']) {
                    response['errors'].forEach(error => {
                        console.log(error);
                        missatges.innerHTML = crearToast(error, "failure");
                    })
                }

            },
            cache: false,
            contentType: false,
            processData: false
        });

        //Delete toasts after 3 seconds
        deleteToasts(3000);



    });

    $(document).on('submit', '#addUser', function(e) {
        var missatges = document.getElementById("addUserResponse");
        missatges.innerHTML = "";
        e.preventDefault();
        var formData = new FormData(this);
        var formulariUser = document.getElementById("formulariUser");
        var actionUrl = "../ajax/ajaxAddUser.php";
        $.ajax({
            url: actionUrl,
            type: 'POST',
            data: formData,
            success: function(data) {
                response = JSON.parse(data);
                console.log(response);
                if (response['success'].length != 0) {
                    response['success'].forEach(success => {
                        missatges.innerHTML = crearToast(success, "success");
                    })
                    formulariUser.innerHTML = response['addUserForm'];
                } else if (response['errors']) {
                    response['errors'].forEach(error => {
                        missatges.innerHTML = crearToast(error, "failure");
                    })
                }

            },
            cache: false,
            contentType: false,
            processData: false
        });

        //Delete toasts after 3 seconds
        deleteToasts(3000);

    });

    function ajaxUpdateDog(idFormulariDog) {
        var missatges = document.getElementById("updateDogResponse");
        missatges.innerHTML = "";
        var concursants = document.getElementById("concursants");
        var formData = new FormData(document.getElementById(idFormulariDog));
        console.log(formData);
        var actionUrl = "../ajax/ajaxUpdateDog.php";
        $.ajax({
            url: actionUrl,
            type: 'POST',
            data: formData,
            success: function(data) {
                response = JSON.parse(data);

                if (response['success'].length != 0) {
                    response['success'].forEach(success => {
                        missatges.innerHTML = crearToast(success, "success");
                    })
                    concursants.innerHTML = response['concursants'];
                } else if (response['errors'].length != 0) {
                    response['errors'].forEach(error => {
                        missatges.innerHTML = crearToast(error, "failure");
                    })
                }

            },
            cache: false,
            contentType: false,
            processData: false
        });

        //Delete toasts after 3 seconds
        deleteToasts(3000);
    }



    function deleteToasts(nSeconds) {
        setTimeout(function() {
            const toasts = Array.from(document.getElementsByClassName('toast'));

            toasts.forEach(toast => {
                toast.classList.remove('show');
                toast.classList.add('d-none');
            });
        }, nSeconds);
    }

    function crearToast(missatge, codi) {
        var imatgeURL = "../assets/tick.png";
        var titol = "TOT CORRECTE";

        if (codi == "failure") {
            imatgeURL = "../assets/cross.png";
            titol = "ERROR";
        }

        var htmlString = `
    <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true" style="position: fixed; top: 50%; left:40%;">
    <div class="toast-header">
      <img style="width:24px;" src="${imatgeURL}" class="rounded me-2" alt="...">
      <strong class="me-auto">${titol}</strong>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
      ${missatge}
    </div>
  </div>`;

        return htmlString;
    }
</script>