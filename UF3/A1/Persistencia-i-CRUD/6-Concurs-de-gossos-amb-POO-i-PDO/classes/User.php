<?php
class User
{
    public string $username;
    public string $password;

    function __construct($username="", $password="")
    {
        $this->username = $username;
        $this->password = hash('sha512', $password);
    }


     /**
     * Inserts a user on database
     * @return True on success, false on failure
     */
    function insertToDB(): bool
    {
        $connection = Database::getInstance()->getConnection();
        if (!$connection) return false;

        $query = $connection->prepare("INSERT INTO user (`username`,`password`) VALUES(?,?)");
        $query->bindParam(1, $this->username);
        $query->bindParam(2, $this->password);
        return $query->execute();
    }

    /**
     * Gets user from database
     * @param string $username
     * @return User | bool
     */
    static function getUserFromDb(string $username): User | bool
    {
        $connection = Database::getInstance()->getConnection();
        if (!$connection) return false;

        $query = $connection->prepare("SELECT `username`,`password` FROM user WHERE username = ?");
        $query->bindParam(1, $username);
        $query->execute();
        $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'User');
        return $query->fetch();



    }
    
}
