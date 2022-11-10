<?php

$host = "127.0.0.1";
$dbname = "gringottsDB";
$user = "goblinSupremo";
$password = "patata";
$port = "3308";

try{
$link = new PDO("mysql:host=$host;dbname=$dbname;port=$port", $user, $password);
}catch(PDOException $e){
    echo "ERROR", $e->getMessage();
    exit();
}

$query = $link->prepare("SELECT * FROM goblins");
$query->execute();
$rows = $query->fetchAll();

foreach($rows as $row){
    echo "<p>El goblin $row[0], amb contrasenya $row[1], va fer l'últim login $row[2] </p>";
}

unset($link);
unset($query);
unset($rows);
?>