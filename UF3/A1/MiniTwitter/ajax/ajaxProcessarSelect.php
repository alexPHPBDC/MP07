<?php

include_once("../utils/pintar.php");

if (isset($_POST['self']) && isset($_POST['idUsuari']) && $_POST['idUsuari'] != "selectBuit") {
    pintarTot($_POST['self'], $_POST['idUsuari']);
}
