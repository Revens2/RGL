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
            return "La réservation a bien été ajoutée à la liste d'attente !";
        } else {
            return "Erreur lors de l'ajout de la réservation : " . $stmt->error;
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

    public function getReservationDetails($reservationId)
    {
        $stmt = $this->conn->prepare("SELECT r.Date_debut, r.Date_fin, r.Commentaire, g.Nom, s.Nom_du_sport FROM reservation r JOIN gymnase g ON g.Id_Gymnase = r.Id_Gymnase JOIN sport s ON s.Id_Sport = r.Id_Sport WHERE Id_reservation = ?");
        $stmt->bind_param("i", $reservationId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }

    public function editReservation($reservationId, $dateDebut, $dateFin, $commentaire, $statut)
    {
        $stmt = $this->conn->prepare("UPDATE reservation SET Date_debut = ?, Date_fin = ?, Commentaire = ?, statut = ? WHERE Id_reservation = ?");
        $stmt->bind_param("sssii", $dateDebut, $dateFin, $commentaire, $statut, $reservationId);
        if ($stmt->execute()) {
            return "La réservation a été mise à jour avec succès.";
        } else {
            return "Erreur lors de la mise à jour de la réservation : " . $stmt->error;
        }
    }

    public function cancelReservation($reservationId)
    {
        $stmt = $this->conn->prepare("UPDATE reservation SET statut = 0 WHERE Id_reservation = ?");
        $stmt->bind_param("i", $reservationId);
        if ($stmt->execute()) {
            return "La réservation a été annulée avec succès.";
        } else {
            return "Erreur lors de l'annulation de la réservation : " . $stmt->error;
        }
    }
}

?>
