$(document).ready(function () {

    $("label").on('click', function () {

        //var idLabel = this.id.replace(/opt/, 'label');
        $("label").removeClass("selected");
        $("#" + this.id).addClass("selected");
    });
});


function ajaxVoteDog(idFormulariDog) {

    var formData = new FormData(document.getElementById(idFormulariDog));
    var actionUrl = "../ajax/ajaxVoteDog.php";
    var missatgesError = document.getElementById("MissatgeError");
    var missatgesSuccess = document.getElementById("MissatgeSuccess");
    var dogVotedName = document.getElementById("dogVotedName");
    $.ajax({
        url: actionUrl,
        type: 'POST',
        data: formData,
        success: function (data) {
            response = JSON.parse(data);
            if (response['success'].length != 0) {
                response['success'].forEach(success => {
                    missatgesSuccess.innerHTML = success;
                })

                if (response['dogName']) {
                    MissatgeError.innerHTML = "Ja has votat al gos " + response['dogName'] + ". Es modificarà la teva resposta";
                }


            } else if (response['errors'].length != 0) {
                response['errors'].forEach(error => {
                    missatgesError.innerHTML = error;
                })
            }


        },
        cache: false,
        contentType: false,
        processData: false
    });
}
