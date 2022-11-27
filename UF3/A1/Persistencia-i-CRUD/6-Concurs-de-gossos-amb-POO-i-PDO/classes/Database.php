<?php
class Database
{
    private $host = "127.0.0.1";
    private $dbname = "concursgossos";
    private $user = "root";
    private $password = "patata";
    private $port = "3308";

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

    public static function loadSampleData()
    {

        Database::truncateTables();

        

        $time = strtotime("2022/10/26");
        $phases = [];
        $dogs = [];

        for ($i = 1; $i <= 8; $i++) {

            $dogs[] = new Dog("", "lisy$i", "../img/g$i.png", "owner$i", "breed$i");

            $j = $i + 1;
            $dataInici = date("Y/m/d", strtotime("+$i month", $time));
            $dataFi = date("Y/m/d", strtotime("+$j month", $time));

            $phases[] = new Phase("", $dataInici, $dataFi, $i);
        }
        

        foreach ($dogs as $dog) {
            $dog->insertToDB();
        }

        foreach ($phases as $phase) {
            $phase->insertToDB();
        }

        $dogs = Dog::getDogsFromDB();
        foreach($dogs as $dog){
            PhaseContestants::insertDogToPhase($dog->id,1);
        }



    }

    private static function truncateTables(){
        $connection = Database::getInstance()->getConnection();
        $query = $connection->prepare("TRUNCATE TABLE vote");
        $query->execute();
        $query = $connection->prepare("TRUNCATE TABLE phasecontestants");
        $query->execute();
        $query = $connection->prepare("DELETE FROM phase");
        $query->execute();
        $query = $connection->prepare("ALTER TABLE phase AUTO_INCREMENT = 1");
        $query->execute();
       
        $query = $connection->prepare("DELETE FROM dog");
        $query->execute();

        
    }
}
