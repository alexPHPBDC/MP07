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

    /**Loads sample data, only for debug purposes */
    public static function loadSampleData()
    {
         Database::truncateTables();

        $time = strtotime("2022/10/31");
        $phases = [];
        $dogs = [];
        $dataInici = date("Y/m/d", strtotime("+1 month", $time));
        $dataFi = date("Y/m/d", strtotime("+2 month", $time));


        for ($i = 1; $i <= 8; $i++) {
            $dataInici = date("Y/m/d", strtotime("+1 day", strtotime($dataFi)));
            $dataFi = date("Y/m/d", strtotime("+1 month", strtotime($dataInici)));                  
            $phases[] = new Phase("", $dataInici, $dataFi, $i);
        }

        for($i=1;$i<=9;$i++){
            $dogs[] = new Dog("", "lisy$i", "../img/g$i.png", "owner$i", "breed$i");
        }
        

        foreach ($dogs as $dog) {
            $dog->insertToDB();
        }

        foreach ($phases as $phase) {
            $phase->insertToDB();
        }

       

        $users = [
            new User("admin","admin"),
            new User("alex","patata")
        ];

        foreach($users as $user){
            $user->insertToDB();
        }

    }

    private static function truncateTables(){
        $connection = Database::getInstance()->getConnection();

        $query = $connection->prepare("TRUNCATE TABLE user");
        $query->execute();
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
