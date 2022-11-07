<?php
$hostname = "localhost";
$dbname = "acces_dades";
$username = "u_acces_dades";
$port = "3308";
$pw = "i";

try {
    $link = new PDO("mysql:host=$hostname;port=$port;dbname=$dbname", "$username", "$pw");
} catch (PDOException $e) {
    echo "Failed to get DB handle: " . $e->getMessage() . "<br>";
    exit;
}

$query = $link->prepare("INSERT INTO prova (i, a) VALUES(?,?)");
$i = 13;
$a = "caco";
$query->bindParam(1, $i);
$query->bindParam(2, $a);

try {
    $query->execute();
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "</br>";
}
?>