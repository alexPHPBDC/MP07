<?php
session_start();
include_once("funcions.php");
include_once("classes.php");
include_once("utils/pintar.php");
/* Dona la benviguda a l’usuari mostrant un missatge de “Benvingut al MiniTwitter NOM_USUARI” i 
mostra tots els seus tuits ordenats de més actual a més antic.*/

if (!isset($_SESSION['user'])) {
    //How did u end up here? go back to index
    header("Location: formularis/formulari_login.php?error=huh", true, 303);
}

$userName = $_SESSION['user'];
$userId = $_SESSION['userId'];

$usuari = new Usuari();
$usuari->id = $userId;
$usuari->name = $userName;
$usuari->followers = getFollowers($usuari);
$usuari->following = getFollowing($usuari);
$usuari->tweets = getMissatgesOrderByDate($usuari);
$usuari->likes = getLikes($usuari);
$users = getOtherUsers($usuari);

$svgHeart = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="20px" height="20px" viewBox="0 0 256 256" xml:space="preserve"><defs></defs><g style="stroke: none; stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: none; fill-rule: nonzero; opacity: 1;" transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)">	<path d="M 7.486 13.502 c 9.982 -9.982 26.165 -9.982 36.147 0 L 45 14.869 l 0 0 c 6.895 22.882 6.259 47.092 0 72.294 L 26.927 69.089 c 0 0 0 0 0 0 l -19.44 -19.44 C -2.495 39.667 -2.495 23.484 7.486 13.502 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(214,73,62); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"/>	<path d="M 82.514 13.502 c -9.982 -9.982 -26.165 -9.982 -36.147 0 L 45 14.869 l 0 0 v 72.294 l 18.073 -18.073 c 0 0 0 0 0 0 l 19.44 -19.44 C 92.495 39.667 92.495 23.484 82.514 13.502 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(215,90,74); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"/></g></svg>';
?>
<head>
<script type="text/javascript" src="js/logic.js"></script>
</head>
<ul>
    <li><a href="formularis/formulari_tweet.php">Crear Tweet</a></li>
    <li><a id='link-sortir'>Sortir</a></li>
</ul>

<h1>INICI:</h1>

<div>
    <h3>Benvingut al MiniTwitter <?= $usuari->name ?></h3>
</div>

<?php


/*echo count($usuari->followers) . " seguidors,";
echo count($usuari->following) . " seguint";*/

if (isset($_COOKIE['tweetMessage'])) {
    echo htmlspecialchars($_COOKIE["tweetMessage"]);
    setcookie('tweetMessage', '', 1); //I do this else cookie doesnt get removed?? tf
    unset($_COOKIE['tweetMessage']);
}
if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'notYourTweet':
            echo "No pots borrar tweets que no són teus!!";
            break;
        case 'notYou':
            echo "//TODO ERROR";
            break;
    }
}


?>
<div class="tab">
    <button class="tablinks active" onclick="clickHandle(event, 'ownTweets')">Els meus tweets</button>
    <button class="tablinks" onclick="clickHandle(event, 'othersTweets')">Altres tweets</button>
    <button class="tablinks" onclick="clickHandle(event, 'feed')">El meu feed</button>
</div>
<div style='display:block' id="ownTweets" class="tabcontent">
    <?php pintarOwnTweets($usuari); ?>
</div>

<div id="othersTweets" class="tabcontent">
    <h3>De quin usuari vols veure els tweets?</h3>

    <form action="#">

        <select id="selectID" name="usuarisSelect" onchange="ajaxLlistarUsuaris(event,<?=$userId?>)">
            <option selected value="selectBuit">Llistat d'usuaris</option>
            <?php
            pintarLlistatUsuaris($users);
            ?>
        </select>
    </form>
    <div id="menuUsuari"></div>
    <div id="altresTweets"></div>


</div>

<div id="feed" class="tabcontent">
    <?php
    $tweetsFromPeopleIfollow = getMissatgesFromPeopleIfollow($userId);
    pintarTweets($usuari, $tweetsFromPeopleIfollow);

    ?>
    <h3>Bork Bork.</h3>
</div>


<form id='form-sortir' action="process/processa_sortir.php" method="POST">

    <div>
        <button name="tancarSessio" type="submit">SORTIR</button>
    </div>

</form>

<script>
    function ajaxLlistarUsuaris(event,userId) {

        var idOpcio = event.currentTarget.value;
        
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("altresTweets").innerHTML = this.responseText;
            }
        };


        xhttp.open("POST", "ajax/ajaxProcessarSelect.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("idUsuari="+idOpcio+"&self="+userId);

    }

    function ajaxMenuFollow(event,self) {
        event.preventDefault();
        var self = document.getElementById('self').value;
        var usuariAtractar = event.currentTarget.value;
        
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("altresTweets").innerHTML = this.responseText;
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

    function ajaxLike(event, self){
        event.preventDefault();
        var usuariAtractar = event.currentTarget.value;
        
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("altresTweets").innerHTML = this.responseText;
            }
        };


        xhttp.open("POST", "ajax/ajaxProcessarLike.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        
        var auxId = document.getElementById("usuariAuxId").value;
        var action = event.currentTarget.getAttribute("name");
        if(action == "dislike" ){
            xhttp.send("dislike="+usuariAtractar+"&self="+self+"&idAux="+auxId);
        }else if(action == "like"){
            xhttp.send("like="+usuariAtractar+"&self="+self+"&idAux="+auxId);
        }

    }

    function ajaxMyFeed(event,self){
        event.preventDefault();

        var usuariAtractar = event.currentTarget.value;
        
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("altresTweets").innerHTML = this.responseText;
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

    function clickHandle(evt, animalName) {
        let i, tabcontent, tablinks;

        // This is to clear the previous clicked content.
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }

        // Set the tab to be "active".
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }

        // Display the clicked tab and set it to active.
        document.getElementById(animalName).style.display = "block";
        evt.currentTarget.className += " active";
    }


    var form = document.getElementById("form-sortir");

    document.getElementById("link-sortir").addEventListener("click", function() {
        form.submit();
    });
</script>
<style>
    /* Style the tab */
    .tab {
        overflow: hidden;
        border: 1px solid #ccc;
        background-color: #f1f1f1;
    }

    /* Style the buttons inside the tab */
    .tab button {
        background-color: inherit;
        float: left;
        border: none;
        outline: none;
        cursor: pointer;
        padding: 14px 16px;
        transition: 0.3s;
        font-size: 17px;
    }

    /* Change background color of buttons on hover */
    .tab button:hover {
        background-color: #ddd;
    }

    /* Create an active/current tablink class */
    .tab button.active {
        background-color: #ccc;
    }

    /* Style the tab content */
    .tabcontent {
        display: none;
        padding: 6px 12px;
        border: 1px solid #ccc;
        border-top: none;
    }
</style>

