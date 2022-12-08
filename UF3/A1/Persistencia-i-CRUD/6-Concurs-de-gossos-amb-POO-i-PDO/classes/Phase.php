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

    /**
     * Given a date, returns the phase that corresponds to it
     * @param string $date The phase's date
     * @return Phase|bool
     */
    static function getPhaseByDateFromDb(string $date): Phase | bool
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

    /**
     * Given a phaseNumber, returns the phase that corresponds to it
     * @param string $phaseNumber
     */
    static function getPhaseByPhaseNumber(string $phaseNumber):Phase | bool
    {
        $connection = Database::getInstance()->getConnection();
        if (!$connection) {
            return false;
        }

        $query = $connection->prepare("SELECT `id`,`startDate`,`endDate`,`phaseNumber` FROM phase where phaseNumber=?");
        $query->bindParam(1, $phaseNumber);
        $query->execute();
        $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Phase');
        return $query->fetch();
        
    }

    /**
     * Updates phase on database, requires the phase to have an ID
     * @param $date The phase's date
     */
    function modificarPhaseDB():bool
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

    /**
     * Inserts a phase on database
     * @return True on success, false on failure
     */
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

    /**
     * Gets all phases from database
     * @return Array of Phases or false on failure
     */
    static function getAllPhases():Array | bool{
        $connection = Database::getInstance()->getConnection();
        if (!$connection) return false;

        $query = $connection->prepare("SELECT `id`,`startDate`,`endDate`,`phaseNumber` FROM phase");
        $query->execute();
        $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Phase');
        return $query->fetchAll();
    }

    /**
     * Checks if date is before given phase
     * @param string $date 
     * @param string $phaseNumber
     * @return boolean 
     */
    static function dateIsBeforePhase(string $date,string $phaseNumber):bool{

        $connection = Database::getInstance()->getConnection();
        if (!$connection) return false;

        $query = $connection->prepare("SELECT DATE(?) < startDate as 'isBefore'  from phase where phaseNumber=?");
        $query->bindParam(1,$date);
        $query->bindParam(2,$phaseNumber);
        $query->execute();
        
        $row = $query->fetch();
        if ($row) {
            return $row['isBefore'];
        }
        return false;
    }

    /**
     * Checks if date is after given phase
     * @param string $date 
     * @param string $phaseNumber
     * @return boolean 
     */
    static function dateIsAfterPhase(string $date,string $phaseNumber):bool{
        $connection = Database::getInstance()->getConnection();
        if (!$connection) return false;

        $query = $connection->prepare("SELECT DATE(?) > endDate as 'isAfter'  from phase where phaseNumber=?");
        $query->bindParam(1,$date);
        $query->bindParam(2,$phaseNumber);
        $query->execute();
        
        $row = $query->fetch();
        if ($row) {
            return $row['isAfter'];
        }
        return false;
    }

    /**
     * Changes phase's date
     * @param string $startDate 
     * @param string $endDate
     * @param string $phaseNumber
     * @return boolean True on success, False on failure
     */
    static function changeDate(string $startDate, string $endDate, string $phaseNumber): bool{
        $connection = Database::getInstance()->getConnection();
        if (!$connection) return false;

        $query = $connection->prepare("UPDATE phase SET `startDate`=DATE(?),`endDate`=DATE(?) where phaseNumber=?");
        $query->bindParam(1,$startDate);
        $query->bindParam(2,$endDate);
        $query->bindParam(3,$phaseNumber);
        return $query->execute();

    }

}
