<?php
class cGymnase
{
    private $conn;
    private $gymid = 0;
    private $gymname = '';
    private $latitude = 0;
    private $longitude = 0;
    private $adresse = '';
    private $ville = '';
    private $zip = '';

    private $sportid = 0;

    private $dateDebut = '';
    private $dateFin = '';
    private $commentaire = '';


    private $userId = 0;

    public function __construct()
    {
        $this->conn = new cbdd();
    }

    public function getGymId()
    {
        return $this->gymid;
    }
    
    public function setGymId($gymid)
    {
        $this->gymid = $gymid;
    }
    public function setSportId($sportid)
    {
        $this->sportid = $sportid;
    }
    public function getSportId()
    {
        return $this->sportid;
    }
    public function getGymname()
    {
        return $this->gymname;
    }

    public function getUserId()
    {
        return $this->userId;
    }
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function getDateDebut()
    {
        return $this->dateDebut;
    }
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;
        $this->startTime = strtotime($dateDebut);
    }
    public function getDateFin()
    {
        return $this->dateFin;
    }
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;
        $this->endTime = strtotime($dateFin);
    }
    public function getCommentaire()
    {
        return $this->commentaire;
    }
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;
    }
    public function setGymname($gymname)
    {
        $this->gymname = $gymname;
    }
    public function getLatitude()
    {
        return $this->latitude;
    }
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }
    public function getLongitude()
    {
        return $this->longitude;
    }
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }
    public function getAdresse()
    {
        return $this->adresse;
    }
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    }
    public function getVille()
    {
        return $this->ville;
    }
    public function setVille($ville)
    {
        $this->ville = $ville;
    }
    public function getZip()
    {
        return $this->zip;
    }
    public function setZip($zip)
    {
        $this->zip = $zip;
    }
    public function GetGym()
    {
        return $this->conn->SelectGym();
    }
    public function GetGym_sport()
    {
        return $this->conn->SelectGym_Sport();
    }
    public function GetOneGym()
    {
        return $this->conn->SelectOneGym($this);
    }
    public function GetOneGym_sport()
    {
        return $this->conn->SelectOneGym_sport($this);
    }
    public function MAJParaGym()
    {
        return $this->conn->UpdateParaGym($this);
    }
    public function SuppOneGym_sport()
    {
        return $this->conn->DelOneGym_sport($this);
    }
    public function AddGym_sport()
    {
        return $this->conn->InsertGym_sport($this);
    }
    public function getddlgym($selectedGymId)
    {
        if ($selectedGymId === null) {
            $selectedGymId = 0;
        }
        $gymList = array();
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
