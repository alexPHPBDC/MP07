<?php
const HOST = "127.0.0.1";
const DBNAME = "dwes-alexcalvo-autpdo";
const USER = "dwes-user";
const PASSWORD = "dwes-pass";
const PORT = "3306";

$link = getLink();

if ($link == null) {
    header("Location: index.php?error=LinkError", true, 303);
    exit();
}
/**
 * Returns Pdo object or null(note the ?)
 * @return PDO|null
 */
function getLink(): ?PDO
{
    $link = null;

    try {
        $link = new PDO("mysql:host=" . HOST . ";dbname=" . DBNAME . ";port=" . PORT, USER, PASSWORD);
    } catch (PDOException $e) {
        echo "ERROR", $e->getMessage();
    }

    return $link;
}
