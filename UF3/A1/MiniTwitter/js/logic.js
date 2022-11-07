
    /*function ajaxMenuFollow(event) {
        var self = document.getElementById('self').value;
        var usuariAtractar = event.currentTarget.value;
        
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("menuUsuari").innerHTML = this.responseText;
            }
        };


        xhttp.open("POST", "ajax/ajaxProcessarFollow.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        
        var action = event.currentTarget.getAttribute("name");
        if(action == "follow" ){
            xhttp.send("idUsuariToFollow="+usuariAtractar+"&self="+self);
        }else if(action == "unfollow"){
            xhttp.send("idUsuariToUnfollow="+usuariAtractar+"&self="+self);
        }

    }




    */