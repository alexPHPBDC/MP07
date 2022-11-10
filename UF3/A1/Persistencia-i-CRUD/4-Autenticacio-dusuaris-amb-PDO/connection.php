<?php
include_once("link.php");
class Connection
{
    public string $ip;
    public string $email;
    public string $time;
    public string $status;

    public function __construct($ip="", $email="", $status = "", $time = "")
    {
        $this->ip = $ip;
        $this->email = $email;
        $this->time = $time;
        $this->status = $status;
    }

    /**
     * @param string $ip L'ip de l'usuari
     * @param string $correu El correu de l'usuari
     * @param string $status L'estatus de la connexió
     * @return bool True on success, False on failure
     */
    public function addConnectionToDB(): bool
    {
        $time = date("Y-m-d H:i:s");

        global $link;
        $query = $link->prepare("INSERT INTO connections (`ip`,`email`,`time`,`status`) VALUES(?,?,?,?)");

        $query->bindParam(1, $this->ip);
        $query->bindParam(2, $this->email);
        $query->bindParam(3, $time);
        $query->bindParam(4, $this->status);
        return $query->execute();
    }

    /**
     * Gets all successful connections a certain email has done.
     * @param string $email 
     * @return array
     */
    function getSuccessfulConnectionsFromEmail(): array
    {
        global $link;
        $query = $link->prepare("SELECT * FROM connections where `email`=? AND `status` IN ('signup_success','signin_success')");
        $query->bindParam(1, $this->email);
        $query->execute();
        $rows = $query->fetchAll();
        $connections = array();
        foreach ($rows as $row) {
            $connection = new Connection($row['ip'], $row['email'], $row['status'], $row['time']);
            $connections[] = $connection;
        }

        return ($connections);
    }
}
