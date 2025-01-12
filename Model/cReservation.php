<?php

class cReservation
{   
    private $conn;
    private $db;

  

    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    public function addReservation($gymId, $userId, $sportId, $dateDebut, $dateFin, $commentaire)
    {
        $statut = 1;
        $stmt = $this->conn->prepare("INSERT INTO reservation (Id_Gymnase, Id_Utilisateur, Id_Sport, Date_debut, Date_fin, Commentaire, statut) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iiisssi", $gymId, $userId, $sportId, $dateDebut, $dateFin, $commentaire, $statut);

        if ($stmt->execute()) {
            return "La r�servation a bien �t� ajout�e � la liste d'attente !";
        } else {
            return "Erreur lors de l'ajout de la r�servation : " . $stmt->error;
        }
    }

    public function getUserReservations(int $userId)
    {
        $stmt = $this->conn->prepare("SELECT r.Id_reservation, r.Date_debut, r.Date_fin, s.Nom_du_sport, r.statut, g.nom FROM reservation r JOIN sport s ON s.Id_Sport = r.Id_Sport JOIN gymnase g ON g.Id_Gymnase = r.Id_Gymnase WHERE Id_Utilisateur = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $reservations = [];
        while ($row = $result->fetch_assoc()) {
            $reservations[] = $row;
        }

        return $reservations;
    }

    public function getUserValidation()
    {
        $stmt = $this->conn->prepare("SELECT r.Id_reservation, r.Date_debut, r.Date_fin, s.Nom_du_sport, r.statut, g.nom, u.Nom, u.Prenom
        FROM reservation r
        JOIN sport s ON s.Id_Sport = r.Id_Sport
        JOIN gymnase g ON g.Id_Gymnase = r.Id_Gymnase
        JOIN utilisateur u ON u.Id_Utilisateur = r.Id_Utilisateur");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }


    public function getReservationDetails($reservationId)
    {
        $editGymData = null;
        $stmt = $this->conn->prepare("SELECT r.Date_debut, r.Date_fin, r.Commentaire, g.Id_Gymnase, s.Nom_du_sport, s.Id_Sport FROM reservation r JOIN gymnase g ON g.Id_Gymnase = r.Id_Gymnase JOIN sport s ON s.Id_Sport = r.Id_Sport WHERE Id_reservation = ?");
        $stmt->bind_param("i", $reservationId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $editGymData = $result->fetch_assoc();
        }
        $stmt->close();
        return $editGymData;
    }

    public function editValidation($valid, $resaid)
    {
        $stmt = $this->conn->prepare("UPDATE reservation SET statut = ? WHERE Id_reservation = ?");
        $stmt->bind_param("ii", $valid, $resaid);
        $stmt->execute();
        $stmt->close();
    }

    public function cancelReservation($resaid)
    {
        $stmt = $this->conn->prepare("UPDATE reservation SET statut = 0 WHERE Id_reservation = ?");
        $stmt->bind_param("i", $resaid);
        $stmt->execute();
        $stmt->close();
    }


    public function editReservation( $userId, $gymId, $sportId, $dateDebut, $dateFin, $commentaire)
    {
        $statut = 1;
        $stmt = $this->conn->prepare("update reservation set  `Id_Utilisateur` = ?, `Id_Sport`= ?, `Date_debut`= ?, `Date_fin` = ?, `Commentaire` = ?, `statut` = ? where Id_reservation = ?");
        $stmt->bind_param("iiisssi", $userId, $gymId, $sportId, $dateDebut, $dateFin, $commentaire, $statut);

        if ($stmt->execute()) {
            return "La r�servation a bien �t� ajout�e � la liste d'attente !";
        } else {
            return "Erreur lors de l'ajout de la r�servation : " . $stmt->error;
        }
    }

    public function deleteReservation($resaid)
    {
        $stmt = $this->conn->prepare("delete from reservation where Id_reservation = ?;");
        $stmt->bind_param("i", $resaid);
        $stmt->execute();
        $stmt->close();
    }

}

?>
