<?php
require_once("connexio.php");

$link = getLink();
if($link == null){
    header("Location: formulariGoblins.php?error=nullLink", true, 303);
}

$pass = md5($_POST['psw']); 

$sql = "SELECT * FROM goblins WHERE goblin_name = '" . $_POST['gobliname'] ."'";

$statement = $link->prepare($sql);

if (!$statement->execute()) {
     die("Ha fallat la consulta, comprova usuari, passwd, bd, nom taula i nom columna");
}

if ($row = $statement->fetch())
{
    if ( $row['password'] == $pass )
    {
       die("perfecte");
    }
    die("usuari trobat, passwd malament");
}    
die("usuari no trobat"); 
?>