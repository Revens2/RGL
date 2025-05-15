<?php

class cReservation
{   

    private $conn;
    private int $userid = 0;
    private int $resaid = 0;
    private int $gym_id = 0;
    private int $sport_id = 0;
    private string $datedebut = '';
    private string $datefin = '';
    private string $commentaire = '';

    private int $valid = 0;

    public function GetUserId()
    {
        return $this->userid;
    }

    public function SetUserId($userid)
    {
        $this->userid = $userid;
    }

    public function GetResaid()
    {
        return $this->resaid;
    }

    public function SetResaid($resaid)
    {
        $this->resaid = $resaid;
    }

    public function GetGymId()
    {
        return $this->gym_id;
    }

    public function SetGymId($gym_id)
    {
        $this->gym_id = $gym_id;
    }

    public function GetSportId()
    {
        return $this->sport_id;
    }

    public function SetSportId($sport_id)
    {
        $this->sport_id = $sport_id;
    }

    public function GetDateDebut()
    {
        return $this->datedebut;
    }

    public function SetDateDebut($datedebut)
    {
        $this->datedebut = $datedebut;
    }

    public function GetDateFin()
    {
        return $this->datefin;
    }

    public function SetDateFin($datefin)
    {
        $this->datefin = $datefin;
    }

    public function GetCommentaire()
    {
        return $this->commentaire;
    }

    public function SetCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;
    }

    public function GetValid()
    {
        return $this->valid;
    }

    public function SetValid($valid)
    {
        $this->valid = $valid;
    }



    public function __construct()
    {
        cbdd::init();
    }

    public function AjoutReservation()
    {
         return cbdd::addReservation($this);
    }

    public function Verifresaexiste()
    {
        return cbdd::CheckResa($this);
    }

    public function getUserReservations()
    {
        return cbdd::SelectUserReservations($this);
    }

    public function getUserValidation()
    {
        return cbdd::SelectUserValidation();
    }

    public function getUserHistorique()
    {
        
        $result = cbdd::SelectUserHistorique($this);
        $historique = [];
        while ($row = $result->fetch_assoc()) {
            $historique[] = $row;
        }

        return $historique;
    }

    public function getReservationDetails()
    {
        $editGymData = null;
        $result = cbdd::SelectReservationDetails($this);
        if ($result->num_rows > 0) {
            $editGymData = $result->fetch_assoc();
        }
        return $editGymData;
    }

    public function editValidation()
    {
        cbdd::UpdateValidation($this);
    }

    public function cancelReservation()
    {
        cbdd::EndReservation($this);
    }


    public function editReservation()
    {
        cbdd::UpdateReservation($this);
    }
    public function GetValidReservation()
    {
        $editGymData = null;
        $result = cbdd::SelectValidReservation($this);
        if ($result->num_rows > 0) {
            $editGymData = $result->fetch_assoc();
        }
        return $editGymData;
    }
}

?>
