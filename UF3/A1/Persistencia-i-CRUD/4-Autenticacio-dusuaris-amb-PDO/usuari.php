<?php
include_once("link.php");

class Usuari
{
    public string $name;
    public string $password;
    public string $email;

    public function __construct($email = "", $password = "", $name = "")
    {
        $this->email = $email;
        $this->password = $password;
        $this->name = $name;
    }

    public function getUserFromDb($email): Usuari | null
    {
        global $link;
        $query = $link->prepare("SELECT * FROM users where `email`=?");
        $query->bindParam(1, $email);
        $query->execute();
        $result = $query->fetch();

        $usuari = null;
        if ($result) {
            $usuari = new Usuari($result['email'], $result['password'], $result['name']);
        }

        return $usuari;
    }

    function addUserToDB()
    {
        global $link;
        $query = $link->prepare("INSERT INTO users (`email`,`password`,`name`) VALUES(?,?,?)");
        $query->bindParam(1, $this->email);
        $query->bindParam(2, hashPassword($this->password));
        $query->bindParam(3, $this->name);
        $query->execute();
    }
}

function hashPassword($password)
{
    return hash('sha512', $password);
}
