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
    
    /**
     * Gets dog's image from database
     * @return The dog's image or false if Database is Offline / No image was found
     */
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

    /**
     * Updates a dog on database
     * @return True on success, false on failure
     */
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

     /**
     * Inserts a dog on database
     * @return True on success, false on failure
     */
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

    /**
     * Gets all dogs from database
     * @return Array of Dogs or false on failure
     */
    static function getDogsFromDB(): array | bool
    {
        $connection = Database::getInstance()->getConnection();
        if (!$connection) return false;

        $query = $connection->prepare("SELECT `id`,`name`,`image`,`owner`,`breed` FROM dog");
        $query->execute();
        $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Dog');
        return $query->fetchAll();
    }

    /**
     * Gets dog from database
     * @param string $id The dog's id
     * @return The dog's image or false if no image was found
     */
    static function getDogFromDB(string $id): Dog | bool
    {
        $connection = Database::getInstance()->getConnection();
        if (!$connection) return false;

        $query = $connection->prepare("SELECT `id`,`name`,`image`,`owner`,`breed` FROM dog WHERE id=?");
        $query->bindParam(1, $id);
        $query->execute();
        $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Dog');
        return $query->fetch();

    }
}
