<?php
require_once("classes/Database.php");
require_once("classes/Dog.php");
require_once("classes/Phase.php");
require_once("classes/User.php");
require_once("dbFunctions.php");
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
                    <img class="dog" alt="Musclo" title="Musclo 15%" src="img/g1.png">
                    <img class="dog" alt="Jingo" title="Jingo 45%" src="img/g2.png">
                    <img class="dog" alt="Xuia" title="Xuia 4%" src="img/g3.png">
                    <img class="dog" alt="Bruc" title="Bruc 3%" src="img/g4.png">
                    <img class="dog" alt="Mango" title="Mango 13%" src="img/g5.png">
                    <img class="dog" alt="Fluski" title="Fluski 12 %" src="img/g6.png">
                    <img class="dog" alt="Fonoll" title="Fonoll 5%" src="img/g7.png">
                    <img class="dog" alt="Swing" title="Swing 2%" src="img/g8.png">
                    <img class="dog eliminat" alt="Coloma" title="Coloma 1%" src="img/g9.png">
                </div>
            </div>
            <div class="admin-row">
                <h1> Nou usuari: </h1>
                <div id="addUserResponse" class="toast-container"></div>
                <form id="addUser">
                    <input type="text" placeholder="Nom" name="username">
                    <input type="password" placeholder="Contrassenya" name="password">
                    <input type="submit" value="Crea usuari">
                </form>
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
                <h1> Concursants: </h1>
                <div id="concursants">
                    <?php
                    $dogs = Dog::getDogsFromDB();
                    foreach ($dogs as $dog) {
                    ?>
                        <form id="f_<?= $dog->id ?>" action="ajaxAdministrarConcursants(this)">
                            <input type="hidden" name="id" value=<?= $dog->id ?>>
                            <input type="text" placeholder="Nom" name="name" value=<?= $dog->name ?>>
                            <input type="text" placeholder="Imatge" name="image" value=<?= $dog->imageUrl ?>>
                            <input type="text" placeholder="Amo" name="owner" value=<?= $dog->owner ?>>
                            <input type="text" placeholder="Raça" name="breed" value=<?= $dog->breed ?>>
                            <input type="button" name="action" value="Modifica">
                        </form>
                    <?php
                    }
                    ?>
                </div>

                <div id="addDogResponse" class="toast-container"></div>
                <form id="addDog" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-3">
                            <label class="form-label">Nom
                                <input type="text" placeholder="Joselito" name="name">
                            </label>
                        </div>

                        <div class="col-3"> <label class="form-label">Imatge<input type="file" placeholder="Imatge" name="image"></div></label>
                        <div class="col-3"> <label class="form-label">Amo<input type="text" placeholder="Amo" name="owner"></div></label>
                        <div class="col-3"> <label class="form-label">Raça<input type="text" placeholder="Raça" name="breed"></div></label>
                    </div>
                    <input class="btn btn-danger float-right" type="submit">
                </form>
            </div>

            <div class="admin-row">
                <h1> Altres operacions: </h1>
                <form>
                    Esborra els vots de la fase
                    <input type="number" placeholder="Fase" value="">
                    <input type="button" value="Esborra">
                </form>
                <form>
                    Esborra tots els vots
                    <input type="button" value="Esborra">
                </form>
            </div>
        </div>
    </div>

</body>

</html>

<script>
    $("#addDog").submit(function(e) {
        var missatges = document.getElementById("addDogResponse");
        missatges.innerHTML = "";
        var concursants = document.getElementById("concursants");
        e.preventDefault();
        var formData = new FormData(this);
        var actionUrl = "ajax/ajaxAddDog.php";
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
                        concursants.innerHTML = response['concursants'];
                    })
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

    $("#addUser").submit(function(e) {
        var missatges = document.getElementById("addUserResponse");
        missatges.innerHTML = "";
        e.preventDefault();
        var formData = new FormData(this);
        var actionUrl = "ajax/ajaxAddUser.php";
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
        var imatgeURL = "assets/tick.png";
        var titol = "TOT CORRECTE";

        if (codi == "failure") {
            imatgeURL = "assets/cross.png";
            titol = "ERROR";
        }
        var htmlString = `
    <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
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