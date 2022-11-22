<?php
class Phase
{
    public string $startDate;
    public string $endDate;
    public string $phaseNumber;

    function getCurrentPhaseFromDb()
    {
        $connection = Database::getInstance()->getConnection();
        if (!$connection) {
            return false;
        }

        $query = $connection->prepare("SELECT `startDate`,`endDate`,`phaseNumber` FROM phase where `startDate`<= CURDATE() AND `endDate`>= CURDATE()");
        $query->execute();

        $row = $query->fetch();
        if ($row) {
            $this->startDate = $row['startDate'];
            $this->endDate = $row['endDate'];
            $this->phaseNumber = $row['phaseNumber'];
        }
        return true;
    }

    function modificarPhaseDB($id)
    {
        $connection = Database::getInstance()->getConnection();
        if (!$connection) {
            return false;
        }
        $query = $connection->prepare("UPDATE phase SET `startDate` = ?,`endDate` = ?, `phaseNumber` = ? WHERE id=?");
        $query->bindParam(1, $this->startDate);
        $query->bindParam(2, $this->endDate);
        $query->bindParam(3, $this->phaseNumber);
        $query->bindParam(4, $id);
        return $query->execute();
    }
}
