<?php
require_once("connexio.php");

$link = getLink();
if($link == null){
    header("Location: formulariGoblins.php?error=nullLink", true, 303);
}

$pass = md5($_POST['psw']); 

$query = $link->prepare("SELECT * FROM goblins WHERE goblin_name = ?");
$query->bindParam(1,$pass);

if (!$query->execute()) {
     die("Ha fallat la consulta, comprova usuari, passwd, bd, nom taula i nom columna");
}

if ($row = $query->fetch())
{
    if ( $row['password'] == $pass )
    {
       die("perfecte");
    }
    die("usuari trobat, passwd malament");
}    
die("usuari no trobat"); 
?>