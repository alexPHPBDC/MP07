<?php
class Dog
{

    public string $id;
    public string $name;
    public string $image;
    public string $owner;
    public string $breed;

    function __construct($id = "", $name = "", $image = "", $owner="", $breed="")
    {
        $this->id = $id;
        $this->name = $name;
        $this->image = $image;
        $this->owner = $owner;
        $this->breed = $breed;
    }
    


    function getDogImageDB(): string | bool
    {
        $connection = Database::getInstance()->getConnection();
        if (!$connection) return false;

        $query = $connection->prepare("SELECT `image` FROM dog where id = ?");
        $query->bindParam(1, $this->id);
        $query->execute();
        $row = $query->fetch();
        if ($row) {
            return $row['image'];
        }
        return false;
    }

    function updateDogDB(): bool
    {
        $connection = Database::getInstance()->getConnection();
        if (!$connection) return false;

        $query = $connection->prepare("UPDATE dog SET `name` = ?,`image` = ?, `owner` = ?, `breed` =? WHERE id=?");
        $query->bindParam(1, $this->name);
        $query->bindParam(2, $this->image);
        $query->bindParam(3, $this->owner);
        $query->bindParam(4, $this->breed);
        $query->bindParam(5, $this->id);
        return $query->execute();
    }

    function insertToDB(): bool
    {
        $connection = Database::getInstance()->getConnection();
        if (!$connection) return false;

        $query = $connection->prepare("INSERT INTO dog (`name`,`image`,`owner`,`breed`) VALUES(?,?,?,?)");
        $query->bindParam(1, $this->name);
        $query->bindParam(2, $this->image);
        $query->bindParam(3, $this->owner);
        $query->bindParam(4, $this->breed);
        return $query->execute();
    }

    static function getDogsFromDB(): array | bool
    {
        $connection = Database::getInstance()->getConnection();
        if (!$connection) return false;

        $dogs = array();
        $query = $connection->prepare("SELECT `id`,`name`,`image`,`owner`,`breed` FROM dog");
        $query->execute();
        $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Dog');
        return $query->fetchAll();
    }

    static function getDogFromDB($id): Dog | false
    {
        $connection = Database::getInstance()->getConnection();
        if (!$connection) return false;

        $dogs = array();
        $query = $connection->prepare("SELECT `id`,`name`,`image`,`owner`,`breed` FROM dog WHERE id=?");
        $query->bindParam(1, $id);
        $query->execute();
        $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Dog');
        return $query->fetch();

    }
}
