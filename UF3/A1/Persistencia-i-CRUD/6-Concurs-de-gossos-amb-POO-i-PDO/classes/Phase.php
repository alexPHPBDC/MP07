<?php
class Phase
{
    public string $id;
    public string $startDate;
    public string $endDate;
    public string $phaseNumber;

    function __construct($id="",$startDate = "",$endDate = "",$phaseNumber = "")
    {
        $this->id = $id;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->phaseNumber = $phaseNumber;
    }

    static function getPhaseByDateFromDb($date)
    {
        $connection = Database::getInstance()->getConnection();
        if (!$connection) {
            return false;
        }

        $query = $connection->prepare("SELECT `id`,`startDate`,`endDate`,`phaseNumber` FROM phase where ? between `startDate` AND `endDate`");
        $query->bindParam(1, $date);
        $query->execute();
        $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Phase');
        return $query->fetch();
        
    }

    function modificarPhaseDB()
    {
        $connection = Database::getInstance()->getConnection();
        if (!$connection) {
            return false;
        }
        $query = $connection->prepare("UPDATE phase SET `startDate` = ?,`endDate` = ?, `phaseNumber` = ? WHERE id=?");
        $query->bindParam(1, $this->startDate);
        $query->bindParam(2, $this->endDate);
        $query->bindParam(3, $this->phaseNumber);
        $query->bindParam(4, $this->id);
        return $query->execute();
    }

    function insertToDB(): bool
    {
        $connection = Database::getInstance()->getConnection();
        if (!$connection) return false;

        $query = $connection->prepare("INSERT INTO phase (`startDate`,`endDate`,`phaseNumber`) VALUES(?,?,?)");
        $query->bindParam(1, $this->startDate);
        $query->bindParam(2, $this->endDate);
        $query->bindParam(3, $this->phaseNumber);
        return $query->execute();
    }

    static function getAllPhases(){
        $connection = Database::getInstance()->getConnection();
        if (!$connection) return false;

        $query = $connection->prepare("SELECT `id`,`startDate`,`endDate`,`phaseNumber` FROM phase");
        $query->execute();
        $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Phase');
        return $query->fetchAll();
    }

}
