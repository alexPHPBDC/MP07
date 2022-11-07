<?php
const HOST = "127.0.0.1";
const DBNAME = "gringottsDB";
const USER = "goblinSupremo";
const PASSWORD = "patata";
const PORT = "3308";


/**
 * Returns Pdo object or null(note the ?)
 * @return PDO|null
 */
function getLink(): ?PDO
{
    $link = null;

    try {
        $link = new PDO("mysql:host=" . HOST . ";dbname=" . DBNAME . ";port=" . PORT , USER  ,PASSWORD);
    } catch (PDOException $e) {
        echo "ERROR", $e->getMessage();
    }

    return $link;
}
