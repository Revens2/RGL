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

    public function SelectLogin($cUtilisateur)
    {
        $mail = $cUtilisateur->getEmail();
        $mdp = $cUtilisateur->getMdp();
        return $this->ExecuteSelectedtostore("SELECT Id_Utilisateur, isClient, isAdmin FROM utilisateur WHERE Email = '$mail' AND Mot_de_Passe = '$mdp'");

    }

    public function SelectUser($cUtilisateur)
    {
        $userid = $cUtilisateur->GetUserId();
        return $this->ExecuteSelected("SELECT isClient, isAdmin FROM utilisateur WHERE Id_Utilisateur = $userid ");

    }

    public function SelectAccount($cUtilisateur)
    {  
        $userid= $cUtilisateur->getUserId();
        return $this->ExecuteSelected("SELECT Nom, Prenom, Date_de_naissance, Numero_de_telephone, Email, Adresse, Zip, Ville FROM utilisateur WHERE Id_Utilisateur = $userid ");

    }

    public function UpdateAccount($cUtilisateur)
    {
        $nom = $cUtilisateur->GetNom();
        $prenom = $cUtilisateur->GetPrenom();
        $birth = $cUtilisateur->GetBirth();
        $tel = $cUtilisateur->GetTel();
        $adresse = $cUtilisateur->GetAdresse();
        $email = $cUtilisateur->GetEmail();
        $ville = $cUtilisateur->GetVille();
        $zip = $cUtilisateur->GetZip();
        $userId = $cUtilisateur->GetUserId();
        return $this->ExecuteSelected("UPDATE Utilisateur SET `Nom` = '$nom', `Prenom` = '$prenom', `Date_de_naissance` = '$birth', `Numero_de_telephone` = '$tel', `Adresse`= '$adresse', `Email` = '$email', `Ville`= '$ville', `Zip` = '$zip'  WHERE Id_Utilisateur = $userId ");

    }

    public function AddAccount($cUtilisateur)
    {
        $nom = $cUtilisateur->GetNom();
        $prenom = $cUtilisateur->GetPrenom();
        $birth = $cUtilisateur->GetBirth();
        $tel = $cUtilisateur->GetTel();
        $adresse = $cUtilisateur->GetAdresse();
        $email = $cUtilisateur->GetEmail();
        $ville = $cUtilisateur->GetVille();
        $zip = $cUtilisateur->GetZip();
        $hashed = $cUtilisateur->GetMdp();
        $isClient = 1;
        return $this->ExecuteSelected("INSERT INTO `Utilisateur` (`Nom`, `Prenom`, `Date_de_naissance`, `Numero_de_telephone`, `Adresse`, `isClient`, `Email`, `Ville`, `Zip`, `Mot_de_Passe`) VALUES ('$nom', '$prenom', '$birth', '$tel', '$adresse','$isClient','$email', '$ville', '$zip','$hashed')");

    }

    public function SelectEmail($cUtilisateur)
    {
        $email = $cUtilisateur->GetEmail();
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

    public function SelectOneGym($cGymnase)
    {
        $gymid = $cGymnase->GetGymId();
        return $this->ExecuteSelected("SELECT * FROM gymnase WHERE Id_Gymnase = $gymid");

    }

    public function InsertGym($cGymnase)
    {
        $gymname = $cGymnase->getGymname();
        $latitude=$cGymnase->getLatitude();
        $longitude=$cGymnase->getLongitude();
        $adresse=$cGymnase->getAdresse();
        $ville=$cGymnase->getVille();
        $zip=$cGymnase->getZip();
            
        return $this->ExecuteSelected("INSERT INTO gymnase (Nom, Coordonnees_latitude, Coordonnees_longitude, Adresse, Ville, Zip) VALUES ('$gymname','$latitude','$longitude','$adresse','$ville','$zip')");

    }

    


    public function UpdateParaGym($cGymnase)
    {
        $gymid = $cGymnase->getGymId();
        $gymname = $cGymnase->getGymname();
        $latitude = $cGymnase->getLatitude();
        $longitude = $cGymnase->getLongitude();
        $adresse = $cGymnase->getAdresse();
        $ville = $cGymnase->getVille();
        $zip = $cGymnase->getZip();
        return $this->ExecuteSelected("UPDATE gymnase SET Nom = '$gymname', Coordonnees_latitude = '$latitude' , Coordonnees_longitude = '$longitude', Adresse = '$adresse', Ville = '$ville', Zip = $zip WHERE Id_Gymnase = $gymid ");

    }

    public function SelectOneGym_sport($cGymnase)
    {
        $gymid = $cGymnase->GetGymId();
        return $this->ExecuteSelected("SELECT Id_Sport FROM gymnase_sport WHERE Id_Gymnase = $gymid ");

    }

    public function DelOneGym_sport($cGymnase)
    {
        $gymid = $cGymnase->GetGymId();
        return $this->ExecuteSelected("DELETE FROM gymnase_sport WHERE Id_Gymnase = $gymid ");

    }
    public function InsertGym_sport($cGymnase,)
    {
        $gymId = $cGymnase->GetGymId();
        $sportId = $cGymnase->GetSportId();
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
    public function AddSport($cSport)
    {
        $name = $cSport->getName();
        $collec = $cSport->getCollec();
        return $this->ExecuteSelected("INSERT INTO sport (Nom_du_sport, Collectif) VALUES ('$name', $collec)");

    }
    public function SelectDdlSport($selectedGymId)
    {
        return $this->ExecuteSelected("SELECT DISTINCT s.id_sport, s.nom_du_sport FROM sport s JOIN gymnase_sport g ON s.id_sport = g.Id_Sport WHERE g.Id_Gymnase = $selectedGymId ");

    }

    #regioncReservation


    public function addReservation($cReservation)
    {
        $gymId = $cReservation->getGymId();
        $userId = $cReservation->getUserId();
        $sportId = $cReservation->getSportId();
        $dateDebut = $cReservation->getDateDebut();
        $dateFin = $cReservation->getDateFin();
        $commentaire = $cReservation->getCommentaire();
        $statut = 1;
        return $this->ExecuteSelected("INSERT INTO reservation (Id_Gymnase, Id_Utilisateur, Id_Sport, Date_debut, Date_fin, Commentaire, statut) VALUES ($gymId, $userId, $sportId, '$dateDebut', '$dateFin', '$commentaire', $statut ) ");

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

    public function SelectUserHistorique($cReservation)
    {
        $userId= $cReservation->GetUserId();
        return $this->ExecuteSelected("SELECT r.Id_reservation, r.Date_debut, r.Date_fin, s.Nom_du_sport, g.nom, r.Commentaire FROM reservation r JOIN sport s ON s.Id_Sport = r.Id_Sport JOIN gymnase g ON g.Id_Gymnase = r.Id_Gymnase WHERE Id_Utilisateur = $userId and statut = 0");

    }

    public function SelectReservationDetails($cReservation)
    {
        $reservationId=$cReservation->GetResaid();
        return $this->ExecuteSelected("SELECT r.Date_debut, r.Date_fin, r.Commentaire, g.Id_Gymnase, s.Nom_du_sport, s.Id_Sport FROM reservation r JOIN gymnase g ON g.Id_Gymnase = r.Id_Gymnase JOIN sport s ON s.Id_Sport = r.Id_Sport WHERE Id_reservation = $reservationId");

    }

    public function UpdateValidation($cReservation)
    {
        $valid = $cReservation->GetValid();
        $reservationId = $cReservation->GetResaid();
        return $this->ExecuteSelected("UPDATE reservation SET statut = $valid WHERE Id_reservation = $reservationId");

    }

    public function EndReservation($cReservation)
    {
        $reservationId = $cReservation->GetResaid();
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
        return $this->ExecuteSelected("SELECT r.Id_reservation, r.Date_debut, r.Date_fin, r.statut, r.Commentaire, g.Nom, s.Nom_du_sport FROM reservation r JOIN gymnase g ON g.Id_Gymnase = r.Id_Gymnase JOIN sport s ON s.Id_Sport = r.Id_Sport WHERE Id_reservation =  $resaid ");

    }


    



}




 

?>
