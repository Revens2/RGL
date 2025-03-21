<?php

class cReservation
{   
    private $conn;
    private $db;

    public function __construct($cbbd)
    {
        $this->conn = new cbdd();
    }

    public function AjoutReservation ($gymId, $userId, $sportId, $dateDebut, $dateFin, $commentaire)
    {
         return $this->conn->addReservation($gymId, $userId, $sportId, $dateDebut, $dateFin, $commentaire);
    }

    public function getUserReservations($userId)
    {
        return $this->conn->SelectUserReservations($userId);
    }

    public function getUserValidation()
    {
        return $this->conn->SelectUserValidation();
    }

    public function getUserHistorique($userId)
    {
        $result = $this->conn->SelectUserHistorique($userId);
        $historique = [];
        while ($row = $result->fetch_assoc()) {
            $historique[] = $row;
        }

        return $historique;
    }

    public function getReservationDetails($reservationId)
    {
        $editGymData = null;
        $result = $this->conn->SelectReservationDetails($reservationId);
        if ($result->num_rows > 0) {
            $editGymData = $result->fetch_assoc();
        }
        return $editGymData;
    }

    public function editValidation($valid, $resaid)
    {
        $this->conn->UpdateValidation($valid, $resaid);
    }

    public function cancelReservation($resaid)
    {
        $this->conn->EndReservation($resaid);
    }


    public function editReservation( $userId, $resaid, $sportId, $dateDebut, $dateFin, $commentaire)
    {
        $this->conn->UpdateReservation($resaid);
    }

    public function SuppReservation($resaid)
    {
        $this->conn->DeleteReservation($resaid);
    }
    public function GetValidReservation($resaid)
    {
        //$resaid = 17;
        $editGymData = null;
        $result = $this->conn->SelectValidReservation($resaid);
        if ($result->num_rows > 0) {
            $editGymData = $result->fetch_assoc();
        }
        return $editGymData;
    }
}

?>
