<?php
class PhaseContestants
{

    public string $idDog;
    public string $idPhase;


    static function insertDogToPhase($idDog, $idPhase)
    {

        $connection = Database::getInstance()->getConnection();
        if (!$connection) return false;

        $query = $connection->prepare("INSERT INTO phasecontestants (`idDog`,`idPhase`) VALUES(?,?)");
        $query->bindParam(1, $idDog);
        $query->bindParam(2, $idPhase);

        return $query->execute();
    }

    static function getContestantsByPhaseFromDb($phaseId){
        $connection = Database::getInstance()->getConnection();
        if (!$connection) return false;

        $dogs = array();
        $query = $connection->prepare("SELECT dog.id,dog.name,dog.image FROM phasecontestants as p inner join dog ON p.idDog = dog.id WHERE p.idPhase = ?");
        $query->bindParam(1, $phaseId);
        $query->execute();
        $rows = $query->fetchAll();

        foreach ($rows as $row) {
            $dog = new Dog($row['id'],$row['name'],$row['image']);
            $dogs[$row['id']] = $dog;
        }

        return $dogs;

    }

    static function getPhaseContestantsUntilToday($date){
        $connection = Database::getInstance()->getConnection();
        if (!$connection) return false;

        $data = [];

        $query = $connection->prepare('
        (SELECT count(dog.id) as "voteCount",phase.phaseNumber,dog.id as dogId,dog.name as dogName,dog.image as dogImage, 
        count(dog.Id)/(select count(*) from vote 
         JOIN phase ON vote.idPhase = phase.id WHERE   ? BETWEEN phase.startDate AND phase.endDate ) as "votePercentage"
        FROM phasecontestants as p
        inner join dog ON p.idDog = dog.id
         INNER JOIN phase ON p.idPhase = phase.id
         INNER JOIN vote ON vote.idDog = dog.id AND vote.idPhase = phase.id
          WHERE ? BETWEEN phase.startDate AND phase.endDate
          GROUP BY dog.id)
        '   );
        $query->bindParam(1, $date);
        $query->bindParam(2, $date);
        $query->execute();
        $rows = $query->fetchAll();

        foreach($rows as $row){
        $phase = $row['phaseNumber'];
        $data[$phase][] = array("voteCount"=>$row['voteCount'],"phaseNumber"=>$row['phaseNumber'],"dogId"=>$row['dogId'],"dogName"=>$row['dogName'],"dogImage"=>$row['dogImage'],"votePercentage"=>$row['votePercentage']);    
        }

return $data;

    }
}
