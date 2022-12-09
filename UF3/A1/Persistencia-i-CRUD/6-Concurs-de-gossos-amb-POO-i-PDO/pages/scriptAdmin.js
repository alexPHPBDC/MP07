function ajaxModifyPhaseDate(idFormulariPhaseDate) {

    var formData = new FormData(document.getElementById(idFormulariPhaseDate));
    var actionUrl = "../ajax/ajaxUpdatePhaseDate.php";
    var missatgesError = document.getElementById("MissatgeErrorPhase");
    var missatgesSuccess = document.getElementById("MissatgeSuccessPhase");
    var phases = document.getElementById("phases");
    $.ajax({
        url: actionUrl,
        type: 'POST',
        data: formData,
        success: function (data) {
            response = JSON.parse(data);
            if (response['success'].length != 0) {
                response['success'].forEach(success => {
                    missatgesSuccess.innerHTML = crearToast(success, "success");
                })
            } else if (response['errors'].length != 0) {
                response['errors'].forEach(error => {
                    missatgesError.innerHTML = crearToast(error, "failure");
                })
            }

            if (response['phases'] != "") {
                phases.innerHTML = response['phases'];
            }


        },
        cache: false,
        contentType: false,
        processData: false
    });
    deleteToasts(3000);
}






$(document).on('submit', '#esborrarTotsVots', function (e) {
    e.preventDefault();
    var actionUrl = "../ajax/ajaxDeleteAllVotes.php";
    var formData = new FormData(this);
    var missatges = document.getElementById("missatgeEsborrarTotsVots");
    $.ajax({
        url: actionUrl,
        type: 'POST',
        data: formData,
        success: function (data) {
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

$(document).on('submit', '#esborrarVotsFaseEspecifica', function (e) {
    e.preventDefault();
    var actionUrl = "../ajax/ajaxDeleteVotesPhase.php";

    var form_data = new FormData();
    form_data.append("phaseInfo", document.getElementById("selectVotsFases").value);

    var missatges = document.getElementById("missatgeEsborrarVotsPhase");
    $.ajax({
        url: actionUrl,
        type: 'POST',
        data: form_data,
        success: function (data) {
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


$(document).on('submit', '#addDog', function (e) {
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
        success: function (data) {
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

$(document).on('submit', '#addUser', function (e) {
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
        success: function (data) {
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
    var actionUrl = "../ajax/ajaxUpdateDog.php";
    $.ajax({
        url: actionUrl,
        type: 'POST',
        data: formData,
        success: function (data) {
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
    setTimeout(function () {
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
