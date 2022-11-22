<?php

class User
{
    public string $username;
    public string $password;

    function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = hash('sha512', $password);
    }

    function insertToDB(): bool
    {
        $connection = Database::getInstance()->getConnection();
        if (!$connection) return false;

        $query = $connection->prepare("INSERT INTO user (`username`,`password`) VALUES(?,?)");
        $query->bindParam(1, $this->username);
        $query->bindParam(2, $this->password);
        return $query->execute();
    }
}
