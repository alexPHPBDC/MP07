<?php
include_once("connexio.php");

class Goblin
{
    protected string $goblinName;
    protected string $password;

    public function __construct(string $goblinName, string $password)
    {
        $this->goblinName = $goblinName;
        $this->password = $password;
    }
}

if (isset($_POST)) {
    $link = getLink();
    if ($link != null) {
        $goblinName = obtenirVariable("goblinName");
        $password = obtenirVariable("password");
        $password = hash('sha512', $password);
        inserirGoblin($link, new Goblin($goblinName, $password));
        header("Location: formulariGoblins.php?data=$goblinName", true, 302);
    }
}

header("Location: formulariGoblins.php?error=Forbidden", true, 303);


/**
 * @param string $string var
 * @return string returns POST variable or empty
 */
function obtenirVariable(string $string): string
{
    $var = "";
    if (isset($_POST[$string])) {
        $var = $_POST[$string];
    }

    return $var;
}

/**
 * Inserts goblin in database
 * @param PDO $link
 * @param Goblin $goblin
 * @return void
 */
function inserirGoblin(PDO $link, Goblin $goblin): void
{
    $query = $link->prepare("INSERT INTO goblins (`goblin_name`, `password`) VALUES(?, ? )");
    $query->bindParam(1, $goblin->goblinName);
    $query->bindParam(2, $goblin->password);

    try {
        $query->execute();
    } catch (PDOException $e) {
        echo "Error!: " . $e->getMessage() . "</br>";
    }
}
