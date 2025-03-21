<?php

class cSport
{
    private $conn;
    private $db;

    private cbdd $cbbd;

    public function __construct($cbbd)
    {
        $this->conn = $cbbd;
    }

    public function GetSport()
    {
 
        return $this->conn->SelectSport();
    }

    public function GetGym_sport()
    {
         return $this->conn->SelSelectGym_SportectSport();
    }

    public function GetAllSport()
    {
        return $this->conn->SelectAllSport();
    }
    public function AjoutSport($name, $collec)
    {
        return $this->conn->AddSport($name, $collec);

    }
    public function getddlsport($selectedGymId, $selectedSportId = null)
    {
        if ($selectedGymId === null) {
            $selectedGymId = 0;
        }

        $SportList = [];
        
        $result = $this->conn->SelectDdlSport($selectedGymId);

        while ($row = $result->fetch_assoc()) {
            $SportList[] = $row;
        }

        $dropdown = '<select id="SportSelect" name="sport_id">';
        foreach ($SportList as $SportItem) {
            $selected = ($SportItem['id_sport'] == $selectedSportId) ? 'selected' : '';
            $dropdown .= '<option value="' . $SportItem['id_sport'] . '" ' . $selected . '>' . htmlspecialchars($SportItem['nom_du_sport']) . '</option>';
        }
        $dropdown .= '</select>';
        return $dropdown;
    }


}
?>