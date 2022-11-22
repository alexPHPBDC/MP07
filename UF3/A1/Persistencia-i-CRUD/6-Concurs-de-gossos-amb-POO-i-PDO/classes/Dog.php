<?php
class Dog
{

    public string $ip;
    public string $name;
    public string $imageUrl;
    public string $owner;
    public string $breed;

    function __construct()
    {
    }

    function setVariables($id, $name, $imageUrl, $owner, $breed)
    {
        $this->id = $id;
        $this->name = $name;
        $this->imageUrl = $imageUrl;
        $this->owner = $owner;
        $this->breed = $breed;
    }

    function updateDogDB(): bool
    {
        $connection = Database::getInstance()->getConnection();
        if (!$connection) return false;

        $query = $connection->prepare("UPDATE dog SET `name` = ?,`image` = ?, `owner` = ?, `breed` =? WHERE id=?");
        $query->bindParam(1, $this->name);
        $query->bindParam(2, $this->imageUrl);
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
        $query->bindParam(2, $this->imageUrl);
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
        $rows = $query->fetchAll();

        foreach ($rows as $row) {
            $dog = new Dog();
            $dog->id = $row['id']; //Preguntar
            $dog->name = $row['name'];
            $dog->imageUrl = $row['image'];
            $dog->owner = $row['owner'];
            $dog->breed = $row['breed'];

            $dogs[$row['id']] = $dog;
        }

        return $dogs;
    }
}
