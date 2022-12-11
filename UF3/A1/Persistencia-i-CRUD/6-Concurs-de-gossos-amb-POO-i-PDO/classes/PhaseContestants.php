<?php
class PhaseContestants
{

    /**
     * Inserts dog and phase into PhaseContestant table
     * @param string $idDog Dog's id
     * @param string $idPhase Phase's id
     * @return bool
     */
    static function insertDogToPhase(string $idDog, string $idPhase): bool
    {

        $connection = Database::getInstance()->getConnection();
        if (!$connection) return false;

        $query = $connection->prepare("INSERT INTO phasecontestants (`idDog`,`idPhase`) VALUES(?,?)");
        $query->bindParam(1, $idDog);
        $query->bindParam(2, $idPhase);

        return $query->execute();
    }

    /**
     * Gets array of winner dog's from database
     * @param string $phaseId
     * @return Array | bool
     */
    static function getWinnersByPhaseFromDb(string $phaseId): array | bool
    {
        $connection = Database::getInstance()->getConnection();
        if (!$connection) return false;

        $query = $connection->prepare("SELECT dog.id,dog.name,dog.image FROM phasecontestants as p inner join dog ON p.idDog = dog.id WHERE p.idPhase = ?");
        $query->bindParam(1, $phaseId);
        $query->execute();
        $query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Dog');
        return $query->fetchAll();
    }

    /**
     * Gets most voted dogs, needs last phase number as param to only get dogs who are currently
     * playing
     * @param string $phaseAnterior
     * @return Array | bool
     */
    static function getMostVotedDogs(string $phaseAnterior): array | bool
    {
        $connection = Database::getInstance()->getConnection();
        if (!$connection) return false;

        $dogs = array();
        $query = $connection->prepare("
        SELECT count(vote.idDog) as 'votes',dog.id,dog.image,dog.name FROM phasecontestants 
        inner join dog ON phasecontestants.idDog = dog.id 
        inner join phase ON phase.id = phasecontestants.idPhase
        inner join vote ON vote.idDog = dog.id and vote.idPhase = phase.id
        WHERE dog.id
        IN
        (SELECT phasecontestants.idDog FROM phasecontestants INNER JOIN dog ON phasecontestants.idDog = dog.id INNER JOIN phase ON phasecontestants.idPhase = phase.id where phase.phaseNumber = ?)
        AND dog.id
        NOT IN
        (SELECT vote.idDog from vote WHERE vote.idPhase = ?)
        GROUP BY dog.id
        
        UNION

        SELECT 0,dog.id,dog.image,dog.name FROM dog 
        WHERE dog.id NOT IN ( SELECT dog.id FROM vote INNER JOIN dog ON dog.id = vote.idDog)
		AND dog.id IN
        (SELECT phasecontestants.idDog FROM phasecontestants WHERE phasecontestants.idPhase = ?)
        ORDER BY votes ASC
    ");

        $phaseActual = $phaseAnterior + 1;
        $query->bindParam(1, $phaseAnterior);
        $query->bindParam(2, $phaseActual);
        $query->bindParam(3, $phaseAnterior);
        $query->execute();
        $rows = $query->fetchAll();

        foreach ($rows as $row) {

            $dog = array("votes" => $row['votes'], "id" => $row['id'], "image" => $row['image'], "name" => $row['name']);
            $dogs[] = $dog;
        }

        return $dogs;
    }

    static function getMostVotedDogsOfFirstPhase()
    {
        $connection = Database::getInstance()->getConnection();
        if (!$connection) return false;

        $dogs = array();
        $query = $connection->prepare("
        SELECT count(vote.idDog) as 'votes',dog.id,dog.image,dog.name FROM dog 
 inner join vote ON vote.idDog = dog.id 
        inner join phase ON phase.id = vote.idPhase
       
        WHERE phase.phaseNumber = 1
        GROUP BY dog.id
        
        UNION

        SELECT 0,dog.id,dog.image,dog.name FROM dog 
        WHERE dog.id NOT IN ( SELECT dog.id FROM vote INNER JOIN dog ON dog.id = vote.idDog)
        ORDER BY votes ASC
    ");

        $query->execute();
        $rows = $query->fetchAll();

        foreach ($rows as $row) {

            $dog = array("votes" => $row['votes'], "id" => $row['id'], "image" => $row['image'], "name" => $row['name']);
            $dogs[] = $dog;
        }

        return $dogs;
    }


    static function getVotedDogsOfPhase($phaseNumber)
    {

        $connection = Database::getInstance()->getConnection();
        if (!$connection) {
            return false;
        }

        $query = $connection->prepare("
        select dog.name,dog.image,dog.id,count(sessionID) as 'votes' FROM vote INNER JOIN dog ON vote.idDog = dog.id INNER JOIN phase ON phase.id = vote.idPhase WHERE phase.phaseNumber = ? GROUP BY dog.id

        UNION

        SELECT dog.name,dog.image,dog.id,0 FROM dog 
        WHERE dog.id NOT IN (
        SELECT dog.id FROM vote INNER JOIN dog ON vote.idDog = dog.id INNER JOIN phase ON phase.id = vote.idPhase WHERE phase.phaseNumber = ? 
        )
        ORDER BY votes ASC");



        $dogs = array();
        $query->bindParam(1, $phaseNumber);
        $query->bindParam(2, $phaseNumber);
        $query->execute();
        $rows = $query->fetchAll();
        foreach ($rows as $row) {
            $dog = array("votes" => $row['votes'], "id" => $row['id'], "image" => $row['image'], "name" => $row['name']);
            $dogs[] = $dog;
        }

        return $dogs;
    }

    /**
     * Gets the dog who was eliminated on given phaseNumber
     * @param string $phaseNumber
     * @return Dog |bool
     */
    static function getDeletedDog($phaseNumber): Dog | bool
    {
        $connection = Database::getInstance()->getConnection();
        if (!$connection) return false;

        $query = $connection->prepare('
(SELECT * from dog
WHERE dog.id IN
(SELECT dog.id from dog INNER JOIN phasecontestants ON phasecontestants.idDog = dog.id INNER JOIN phase ON phase.id = phasecontestants.idPhase where phase.phaseNumber = ?)
AND
dog.id NOT IN
(SELECT dog.id from dog INNER JOIN phasecontestants ON phasecontestants.idDog = dog.id INNER JOIN phase ON phase.id = phasecontestants.idPhase where phase.phaseNumber = ?
)
)');
        $previousPhase = $phaseNumber - 1;
        $query->bindParam(1, $previousPhase);
        $query->bindParam(2, $phaseNumber);
        $query->execute();
        $query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Dog');
        return $query->fetch();
    }

    /**
     * Gets the dog who was eliminated on first phase
     * @return Dog |bool
     */
    static function getDeletedDogFromFirstPhase(): Dog |bool
    {
        $connection = Database::getInstance()->getConnection();
        if (!$connection) return false;

        $query = $connection->prepare('
        (SELECT * from dog
        WHERE 
        dog.id NOT IN
        (SELECT dog.id from dog INNER JOIN phasecontestants ON phasecontestants.idDog = dog.id INNER JOIN phase ON phase.id = phasecontestants.idPhase where phase.phaseNumber = 1
        )
        )');

        $query->execute();
        $query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Dog');
        return $query->fetch();
    }

    /**
     * Returns an array with the phases number as the array indexes
     * Inside every inner array, theres an array that contains the dog's vote count,
     * id, name, image and it's vote percentage.
     * @link https://docs.google.com/drawings/d/19ZeEPJ1DiUs8OPeMrtF6wHCE2VMEuFdb0VhADNA-bMo/edit
     * @param string $date
     * @return Array | bool
     */
    static function getPhaseContestantsUntilToday(string $date): array | bool
    {
        $connection = Database::getInstance()->getConnection();
        if (!$connection) return false;

        $data = [];

        $query = $connection->prepare('
        (SELECT count(dog.id) as "voteCount",f.phaseNumber,dog.id as dogId,dog.name as dogName,dog.image as dogImage, 
        count(dog.Id)/(select count(*) from vote INNER JOIN phase ON vote.idPhase = phase.id WHERE date(?)> phase.startDate AND phase.phaseNumber = f.phaseNumber ) as "votePercentage"
        FROM phasecontestants as p
        INNER JOIN dog ON p.idDog = dog.id
         INNER JOIN phase as f ON p.idPhase = f.id
         INNER JOIN vote ON vote.idDog = dog.id AND vote.idPhase = f.id 
          WHERE date(?) > f.startDate
          GROUP BY dog.id,f.id)
          
          union 
          
(SELECT 0 as "voteCount",phase.phaseNumber,dog.id as dogId,dog.name as dogName,dog.image as dogImage,  0 as "votePercentage"
        FROM phasecontestants as p
        INNER JOIN dog ON p.idDog = dog.id
         INNER JOIN phase ON p.idPhase = phase.id
         LEFT JOIN vote ON vote.idDog = dog.id AND vote.idPhase = phase.id 
          WHERE date(?) > phase.startDate AND
          (SELECT dog.id,phase.id) NOT IN (SELECT dog.id,phase.id FROM dog INNER JOIN vote ON vote.idDog = dog.id INNER JOIN phase ON vote.idPhase = phase.id)
          )
          
          ORDER BY phaseNumber ASC
        ');
        $query->bindParam(1, $date, PDO::PARAM_STR);
        $query->bindParam(2, $date, PDO::PARAM_STR);
        $query->bindParam(3, $date, PDO::PARAM_STR);
        $query->execute();
        $rows = $query->fetchAll();

        foreach ($rows as $row) {
            $phaseNumber = $row['phaseNumber'];
            $data[$phaseNumber][] = array("voteCount" => $row['voteCount'], "phaseNumber" => $row['phaseNumber'], "dogId" => $row['dogId'], "dogName" => $row['dogName'], "dogImage" => $row['dogImage'], "votePercentage" => $row['votePercentage']);
        }

        return $data;
    }
}
