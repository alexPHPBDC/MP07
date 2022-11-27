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

    function addVoteToDB()
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



    static function removeAllVotes(): bool
    {
        $connection = Database::getInstance()->getConnection();
        if (!$connection) {
            return false;
        }

        $query = $connection->prepare("TRUNCATE TABLE vote");
        return $query->execute();
    }

    static function removeVotesFromPhase($idPhase): bool
    {
        $connection = Database::getInstance()->getConnection();
        if (!$connection) {
            return false;
        }

        $query = $connection->prepare("DELETE FROM vote WHERE idPhase = ?");
        $query->bindParam(1, $idPhase);
        return $query->execute();
    }

    static function getVotedDog($sessionID,$phaseId):bool | Dog{
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
}
