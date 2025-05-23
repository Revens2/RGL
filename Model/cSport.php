<?php

class cSport
{
    private $conn;
    private $sportId = 0;
    private $name = '';
    private $collec = 0;

    public function getSportId()
    {
        return $this->sportId;
    }
    public function setSportId($sportId)
    {
        $this->sportId = $sportId;
    }
    public function getName()
    {
        return $this->name;
    }
    public function setName($name)
    {
        $this->name = $name;
    }
    public function getCollec()
    {
        return $this->collec;
    }
    public function setCollec($collec)
    {
        $this->collec = $collec;
    }
    public function __construct()
    {
       cbdd::init();

    }
    public function GetSport()
    {
 
        return cbdd::SelectSport();
    }

    public function GetGym_sport()
    {
         return cbdd::SelectGym_Sport();
    }

    public function GetAllSport()
    {
        return cbdd::SelectAllSport();
    }
    public function AjoutSport()
    {
        return cbdd::AddSport($this);

    }
    public function getddlsport($selectedGymId, $selectedSportId = null)
    {
        if ($selectedGymId === null) {
            $selectedGymId = 0;
        }

        $SportList = [];
        
        $result = cbdd::SelectDdlSport($selectedGymId);

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