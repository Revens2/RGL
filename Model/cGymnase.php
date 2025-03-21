<?php


class cGymnase
{
    private $conn;
    private $db;

    private cbdd $cbdd;

    public function __construct($cbdd)
    {
        $this->conn = $cbdd;
    }


    public function GetGym()

    {
        return $this->conn->SelectGym();
    }

    public function GetGym_sport()
    {
        return $this->conn->SelectGym_Sport();
    }

    public function GetOneGym($gymid)
    {
        return $this->conn->SelectOneGym($gymid);
    }

    public function GetOneGym_sport($gymid)
    {
        return $this->conn->SelectOneGym_sport($gymid);

    }
    public function MAJParaGym($gymid, $gymname, $latitude, $longitude, $adresse, $ville, $zip)
    {

        return $this->conn->UpdateParaGym($gymid, $gymname, $latitude, $longitude, $adresse, $ville, $zip);
    }

    public function SuppOneGym_sport($gymid)
    {
        return $this->conn->DelOneGym_sport($gymid);
    }

    public function AddGym_sport($gymId, $sportId)
    {
        return $this->conn->InsertGym_sport($gymId, $sportId);
    }

    public function getddlgym($selectedGymId)
    {

        if ($selectedGymId === null) {
            $selectedGymId = 0;
        }

        $gymList = [];
        $result = $this->conn->SelectNamGym();
        while ($row = $result->fetch_assoc()) {
            $gymList[] = $row;
        }
        $dropdown = '<select id="gymSelect" name="gym_id">';
        foreach ($gymList as $gymItem) {
            $selected = ($gymItem['Id_Gymnase'] == $selectedGymId) ? 'selected' : '';
            $dropdown .= '<option value="' . $gymItem['Id_Gymnase'] . '" ' . $selected . '>' . htmlspecialchars($gymItem['Nom']) . '</option>';
        }
        $dropdown .= '</select>';
        return $dropdown;

    }



}
?>
