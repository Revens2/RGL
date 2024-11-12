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
    public function AddSport($name, $collec)
    {
        $stmt = $this->conn->prepare("INSERT INTO sport (Nom_du_sport, Collectif) VALUES (?, ?)");
        $stmt->bind_param("si", $name, $collec);

        $stmt->execute();

    }
    public function getddlsport($selectedGymId, $selectedSportId = null)
    {
        if ($selectedGymId === null) {
            $selectedGymId = 0;
        }

        $SportList = [];

        $stmt = $this->conn->prepare("SELECT DISTINCT s.id_sport, s.nom_du_sport FROM sport s JOIN gymnase_sport g ON s.id_sport = g.Id_Sport WHERE g.Id_Gymnase = ?");
        $stmt->bind_param("i", $selectedGymId);
        $stmt->execute();
        $result = $stmt->get_result();


        while ($row = $result->fetch_assoc()) {
            $SportList[] = $row;
        }

        $dropdown = '<select id="SportSelect" name="sport_id">';
        foreach ($SportList as $SportItem) {
            $selected = ($SportItem['id_sport'] == $selectedSportId) ? 'selected' : '';
            $dropdown .= '<option value="' . $SportItem['id_sport'] . '" ' . $selected . '>' . htmlspecialchars($SportItem['nom_du_sport']) . '</option>';
        }
        $dropdown .= '</select>';


        $stmt->close();

        return $dropdown;
    }


}
?>