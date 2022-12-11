<?php

class Vote
{

    public string $idDog;
    public string $idPhase;
    public string $sessionID;

    function __construct($idDog, $idPhase, $sessionID)
    {
        $this->idDog = $idDog;
        $this->idPhase = $idPhase;
        $this->sessionID = $sessionID;
    }

    /**
     * Inserts vote to database
     * @return bool True on success, False on failure
     */
    function addVoteToDB(): bool
    {
        $connection = Database::getInstance()->getConnection();
        if (!$connection) {
            return false;
        }

        $query = $connection->prepare("INSERT INTO vote (`idDog`,`idPhase`,`sessionID`) VALUES(?,?,?) ON DUPLICATE KEY UPDATE `idDog`=?");
        $query->bindParam(1, $this->idDog);
        $query->bindParam(2, $this->idPhase);
        $query->bindParam(3, $this->sessionID);
        $query->bindParam(4, $this->idDog);
        return $query->execute();
    }


    /**
     * Removes all votes from database
     * @return bool True on success, False on failure
     */
    static function removeAllVotes(): bool
    {
        $connection = Database::getInstance()->getConnection();
        if (!$connection) {
            return false;
        }

        $query = $connection->prepare("TRUNCATE TABLE vote");
        return $query->execute();
    }

    /**
     * Removes all votes from given phase
     * @param string $idPhase
     * @return bool True on success, False on failure
     */
    static function removeVotesFromPhase(string $idPhase): bool
    {
        $connection = Database::getInstance()->getConnection();
        if (!$connection) {
            return false;
        }

        $query = $connection->prepare("DELETE FROM vote WHERE idPhase = ?");
        $query->bindParam(1, $idPhase);
        return $query->execute();
    }

    /**
     * Gets dog who was voted by $sessionID in $phaseId
     * @param string $sessionID
     * @param string $phaseId
     * @return Dog | bool
     */
    static function getVotedDog(string $sessionID,string $phaseId):bool | Dog{
        $connection = Database::getInstance()->getConnection();
        if (!$connection) {
            return false;
        }

        $query = $connection->prepare("SELECT `id`,`name`,`image`,`owner`,`breed` FROM vote inner join dog ON dog.id=vote.idDog WHERE vote.idPhase=? AND vote.sessionID = ?");
        $query->bindParam(1, $phaseId);
        $query->bindParam(2, $sessionID);
        $query->execute();
        $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Dog');
        return $query->fetch();
    }

    /**
     * Gets dog's partial votes on given phaseNumber in percentage (float)
     * @param string $phaseNumber
     */
    static function getPartialVotes(string $phaseNumber): Array | bool{
        $connection = Database::getInstance()->getConnection();
        if (!$connection) {
            return false;
        }

        $query = $connection->prepare("SELECT dog.id,phase.id,dog.name as dogName,dog.image as dogImage,
        count(dog.Id)/
        (select count(*) from vote JOIN phase ON vote.idPhase = phase.id WHERE phase.phaseNumber = ?) as 'votePercentage' FROM vote
        INNER JOIN phase ON phase.id = vote.idPhase 
        INNER JOIN dog ON vote.idDog = dog.id 
        WHERE phase.phaseNumber = ?
        GROUP BY dog.id,phase.id
        
        union
        
        (select dog.id,phase.id,dog.name,dog.image,0 from dog INNER JOIN phasecontestants ON phasecontestants.idDog = dog.id INNER JOIN phase ON phasecontestants.idPhase = phase.id 
        WHERE dog.id 
        IN (SELECT phasecontestants.idDog From phasecontestants 
        INNER JOIN phase ON phasecontestants.idPhase = phase.id WHERE phaseNumber = ?) 
        AND dog.id NOT IN
        (SELECT dog.id FROM dog INNER JOIN vote ON vote.idDog = dog.id INNER JOIN phase ON vote.idPhase = phase.id WHERE phase.phaseNumber = ?)
        AND phase.phaseNumber = ?
        GROUP BY dog.id,phase.id
        )");
    
        $previousPhaseNumber = $phaseNumber -1;
        $query->bindParam(1, $phaseNumber);
        $query->bindParam(2, $phaseNumber);
        $query->bindParam(3, $previousPhaseNumber);
        $query->bindParam(4, $phaseNumber);
        $query->bindParam(5, $phaseNumber);
        $query->execute();
        return $query->fetchAll();
    }
}
