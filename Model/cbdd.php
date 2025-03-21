<?php
class cbdd
{
private string $servername = "localhost";
private string $username = "root";
private string $password = "root";
private string $dbname = "rgl";

public $conn;

    private function GetServername()
    {
        return $this->servername;
    }

    private function GetUsername()
    {
        return $this->username;
    }

    private function GetPassword ()
    {
        return $this->password;
    }

    private function GetDbname ()
    {
        return $this->dbname;
    }



    

    public function __construct()
    {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
    }



    public function ExecuteSelected(string $query)
    {
        $stmt = $this->conn->prepare("$query");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }
    public function ExecuteSelectedtostore(string $query)
    {
        $stmt = $this->conn->prepare("$query");
        $stmt->execute();
        $stmt->store_result();
        return $stmt;
    }
    #regioncUtilisateur

    public function SelectLogin($connect)
    {
        $mail = $connect->getEmail();
        $mdp = $connect->getMdp();
        return $this->ExecuteSelectedtostore("SELECT Id_Utilisateur, isClient, isAdmin FROM utilisateur WHERE Email = '$mail' AND Mot_de_Passe = '$mdp'");

    }

    public function SelectUser($userid)
    {
        return $this->ExecuteSelected("SELECT isClient, isAdmin FROM utilisateur WHERE Id_Utilisateur = $userid ");

    }
    public function SelectUserInfo($userid)
    {
        return $this->ExecuteSelected("SELECT * FROM utilisateur WHERE Id_Utilisateur = $userid ");

    }

    public function SelectAccount($connect)
    {  
        $userid= $connect->getUserId();
        return $this->ExecuteSelected("SELECT Nom, Prenom, Date_de_naissance, Numero_de_telephone, Email, Adresse, Zip, Ville FROM utilisateur WHERE Id_Utilisateur = $userid ");

    }

    public function UpdateAccount($user)
    {
        $nom = $user->GetNom();
        $prenom = $user->GetPrenom();
        $birth = $user->GetBirth();
        $tel = $user->GetTel();
        $adresse = $user->GetAdresse();
        $email = $user->GetEmail();
        $ville = $user->GetVille();
        $zip = $user->GetZip();
        $userId = $user->GetUserId();
        return $this->ExecuteSelected("UPDATE Utilisateur SET `Nom` = '$nom', `Prenom` = '$prenom', `Date_de_naissance` = '$birth', `Numero_de_telephone` = '$tel', `Adresse`= '$adresse', `Email` = '$email', `Ville`= '$ville', `Zip` = '$zip'  WHERE Id_Utilisateur = $userId ");

    }

    public function AddAccount( $nom, $prenom, $birth, $tel, $adress, $ville, $zip, $email, $hashed)
    {
        $isClient = 1;
        return $this->ExecuteSelected("INSERT INTO `Utilisateur` (`Nom`, `Prenom`, `Date_de_naissance`, `Numero_de_telephone`, `Adresse`, `isClient`, `Email`, `Ville`, `Zip`, `Mot_de_Passe`) VALUES ('$nom', '$prenom', '$birth', '$tel', '$adress','$isClient','$email', '$ville', '$zip','$hashed')");

    }

    public function SelectEmail($email)
    {
        return $this->ExecuteSelected("select Email from utilisateur where email = '$email' ");

    }
    #regioncGymnase


    public function SelectGym()
    {
        return $this->ExecuteSelected("SELECT Id_Gymnase, Nom, Coordonnees_latitude, Coordonnees_longitude, Adresse, Ville, Zip FROM gymnase");

    }

    public function SelectGym_Sport()
    {
        return $this->ExecuteSelected("SELECT Id_Gymnase, Id_Sport FROM gymnase_sport");

    }

    public function SelectOneGym($gymid)
    {
        return $this->ExecuteSelected("SELECT * FROM gymnase WHERE Id_Gymnase = $gymid ");

    }


    public function UpdateParaGym($gymid, $gymname, $latitude, $longitude, $adresse, $ville, $zip)
    {
        return $this->ExecuteSelected("UPDATE `gymnase` SET `Nom` = $gymname, `Coordonnees_latitude` = $latitude, `Coordonnees_longitude` = $longitude, `Adresse` = $adresse, `Ville` = $ville, `Zip` = $zip WHERE `Id_Gymnase` = $gymid");

    }

    public function SelectOneGym_sport($gymid)
    {
        return $this->ExecuteSelected("SELECT Id_Sport FROM gymnase_sport WHERE Id_Gymnase = $gymid ");

    }

    public function DelOneGym_sport($gymid)
    {
        return $this->ExecuteSelected("DELETE FROM gymnase_sport WHERE Id_Gymnase = $gymid ");

    }
    public function InsertGym_sport($gymId, $sportId)
    {
        return $this->ExecuteSelected("INSERT INTO gymnase_sport (Id_Gymnase, Id_Sport) VALUES ( $gymId, $sportId) ");

    }

    public function SelectNamGym()
    {
        return $this->ExecuteSelected("SELECT Id_Gymnase, Nom FROM gymnase ");

    }

    

    



    #regioncSport


    public function SelectSport()
    {
        return $this->ExecuteSelected("SELECT Id_Sport, Nom_du_sport FROM sport");
        ;
    }



    public function SelectAllSport()
    {
        return $this->ExecuteSelected("SELECT * FROM sport");

    }
    public function AddSport($name, $collec)
    {
        return $this->ExecuteSelected("INSERT INTO sport (Nom_du_sport, Collectif) VALUES ($name, $collec)");

    }
    public function SelectDdlSport($selectedGymId)
    {
        return $this->ExecuteSelected("SELECT DISTINCT s.id_sport, s.nom_du_sport FROM sport s JOIN gymnase_sport g ON s.id_sport = g.Id_Sport WHERE g.Id_Gymnase = $selectedGymId ");

    }

    #regioncReservation


    public function addReservation($gymId, $userId, $sportId, $dateDebut, $dateFin, $commentaire)
    {
        $statut = 1;
        return $this->ExecuteSelected("INSERT INTO reservation (Id_Gymnase, Id_Utilisateur, Id_Sport, Date_debut, Date_fin, Commentaire, statut) VALUES ($gymId, $userId, $sportId, $dateDebut, $dateFin, $commentaire, $statut ");

    }


    public function SelectUserReservations($cReservation)
    {
        $userId=$cReservation->GetUserId();
        return $this->ExecuteSelected("SELECT r.Id_reservation, r.Date_debut, r.Date_fin, s.Nom_du_sport, r.statut, g.nom FROM reservation r JOIN sport s ON s.Id_Sport = r.Id_Sport JOIN gymnase g ON g.Id_Gymnase = r.Id_Gymnase WHERE Id_Utilisateur = $userId and r.statut > 0");

    }

    public function SelectUserValidation()
    {
        return $this->ExecuteSelected("SELECT r.Id_reservation, r.Date_debut, r.Date_fin, s.Nom_du_sport, r.statut, g.nom, u.Nom, u.Prenom FROM reservation r JOIN sport s ON s.Id_Sport = r.Id_Sport JOIN gymnase g ON g.Id_Gymnase = r.Id_Gymnase JOIN utilisateur u ON u.Id_Utilisateur = r.Id_Utilisateur where r.statut > 0");

    }

    public function SelectUserHistorique($reservation)
    {
        $userId=$reservation->GetUserId();
        return $this->ExecuteSelected("SELECT r.Id_reservation, r.Date_debut, r.Date_fin, s.Nom_du_sport, g.nom, r.Commentaire FROM reservation r JOIN sport s ON s.Id_Sport = r.Id_Sport JOIN gymnase g ON g.Id_Gymnase = r.Id_Gymnase WHERE Id_Utilisateur = $userId and statut = 0");

    }

    public function SelectReservationDetails($cReservation)
    {
        $reservationId=$cReservation->GetReservationId();
        return $this->ExecuteSelected("SELECT r.Date_debut, r.Date_fin, r.Commentaire, g.Id_Gymnase, s.Nom_du_sport, s.Id_Sport FROM reservation r JOIN gymnase g ON g.Id_Gymnase = r.Id_Gymnase JOIN sport s ON s.Id_Sport = r.Id_Sport WHERE Id_reservation = $reservationId");

    }

    public function UpdateValidation($cReservation)
    {
        $valid = $cReservation->GetValid();
        $reservationId = $cReservation->GetReservationId();
        return $this->ExecuteSelected("UPDATE reservation SET statut = $valid WHERE Id_reservation = $reservationId");

    }

    public function EndReservation($cReservation)
    {
        $reservationId = $cReservation->GetReservationId();
        return $this->ExecuteSelected("UPDATE reservation SET statut = 0 WHERE Id_reservation =  $reservationId");

    }
    public function UpdateReservation($cReservation)
    {
        $resaid = $cReservation->GetResaid();
        $gymId = $cReservation->GetGymId();
        $sportId = $cReservation->GetSportId();
        $dateDebut = $cReservation->GetDateDebut();
        $dateFin = $cReservation->GetDateFin();
        $commentaire = $cReservation->GetCommentaire();
        $statut = 1;
        return $this->ExecuteSelected("update reservation set `Id_Gymnase`= $gymId, `Id_Sport`= $sportId, `Date_debut`= '$dateDebut', `Date_fin` = '$dateFin', `Commentaire` = '$commentaire', `statut` = $statut where Id_reservation = $resaid ");

    }

    public function DeleteReservation($cReservation)
    {
        $resaid = $cReservation->GetResaid();
        return $this->ExecuteSelected("delete from reservation where Id_reservation =  $resaid ");

    }

    public function SelectValidReservation($cReservation)
    {
        $resaid = $cReservation->GetResaid();
        return $this->ExecuteSelected("SELECT r.Date_debut, r.Date_fin, r.statut, r.Commentaire, g.Nom, s.Nom_du_sport FROM reservation r JOIN gymnase g ON g.Id_Gymnase = r.Id_Gymnase JOIN sport s ON s.Id_Sport = r.Id_Sport WHERE Id_reservation =  $resaid ");

    }


    



}




 

?>
