<?php
class Database
{
    private $host = "127.0.0.1";
    private $dbname = "concursgossos";
    private $user = "alex";
    private $password = "patata";
    private $port = "3306";

    // Hold the class instance.
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        try {
            $this->connection = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->dbname . ";port=" . $this->port, $this->user, $this->password);
        } catch (PDOException $e) {
        }
    }

    public static function getInstance()
    {
        if (!self::$instance) // If no instance then make one
        {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }

}
