<?php

class cSport
{
    private $conn;
    private $db;



    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    public function GetSport()
    {
        $stmt = $this->conn->prepare("SELECT Id_Sport, Nom_du_sport FROM sport");
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    public function GetGym_sport()
    {
        $stmt = $this->conn->prepare("SELECT Id_Gymnase, Id_Sport FROM gymnase_sport");
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    public function GetAllSport()
    {
        $stmt = $this->conn->prepare("SELECT * FROM sport");
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }
}
?>
