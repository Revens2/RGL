<?php
class cbdd
{
private static string $servername = "localhost";
private static string $username = "root";
private static string $password = "root";
private static string $dbname = "rgl";

 public static $conn;

    private function GetServername()
    {
        return self ::servername;
    }

    private function GetUsername()
    {
        return self ::username;
    }

    private function GetPassword ()
    {
        return self ::password;
    }

    private function GetDbname ()
    {
        return self ::dbname;
    }



    

     public static function init()
    {
        SELF ::$conn = new mysqli(SELF ::$servername , self::$username, self::$password, self:: $dbname);
    }



     public static function ExecuteSelected(string $query)
    {
        $stmt = self::$conn->prepare("$query");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }
      public static function ExecuteSelectedtostore(string $query)
    {
        $stmt = self::$conn->prepare("$query");
        $stmt->execute();
        $stmt->store_result();
        return $stmt;
    }
    #regioncUtilisateur

    public static function SelectLogin($cUtilisateur)
    {
        $sql = "SELECT Id_Utilisateur, isClient, isAdmin FROM utilisateur WHERE Email = ? AND Mot_de_Passe = ?";
        $stmt = self::$conn->prepare($sql);
        $stmt->bind_param("ss",$cUtilisateur->getEmail(),$cUtilisateur->getMdp());
        $stmt->execute();
        $stmt->store_result();
        return $stmt;
    }

      public static function SelectUser($cUtilisateur)
    {
        $sql = "SELECT isClient, isAdmin FROM utilisateur WHERE Id_Utilisateur =?";
        $stmt = self::$conn->prepare($sql);
        $stmt->bind_param("i", $cUtilisateur->GetUserId());
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;

    }

      public static function SelectAccount($cUtilisateur)
    {  
        $sql = "SELECT Nom, Prenom, Date_de_naissance, Numero_de_telephone, Email, Adresse, Zip, Ville FROM utilisateur WHERE Id_Utilisateur = ?";
        $stmt = self::$conn->prepare($sql);
        $stmt->bind_param("i", $cUtilisateur->GetUserId());
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }

      public static function UpdateAccount($cUtilisateur)
    {
        
  
         $sql = "UPDATE Utilisateur SET `Nom` = ?, `Prenom` = ?, `Date_de_naissance` = ?, `Numero_de_telephone` = ?, `Adresse`= ?, `Email` = ?, `Ville`= ?, `Zip` = ? WHERE Id_Utilisateur = ?";
        $stmt = self::$conn->prepare($sql);
        $stmt->bind_param("sssisssii", $cUtilisateur->GetNom(), $cUtilisateur->GetPrenom(), $cUtilisateur->GetBirth(),$cUtilisateur->GetTel(),$cUtilisateur->GetAdresse(), $cUtilisateur->GetEmail(), $cUtilisateur->GetVille(), $cUtilisateur->GetZip(), $cUtilisateur->GetUserId());
        $stmt->execute();
        $stmt->store_result();
        return $stmt;
    }

      public static function AddAccount($cUtilisateur)
    {
        $sql = "INSERT INTO `Utilisateur` (`Nom`, `Prenom`, `Date_de_naissance`, `Numero_de_telephone`, `Adresse`, `isClient`, `Email`, `Ville`, `Zip`, `Mot_de_Passe`) VALUES (?,?,?,?,?,?,?,?,?,?)";
        $stmt = self::$conn->prepare($sql);
        $stmt->bind_param("sssisissis", $cUtilisateur->GetNom(), $cUtilisateur->GetPrenom(), $cUtilisateur->GetBirth(), $cUtilisateur->GetTel(), $cUtilisateur->GetAdresse(), $isClient = 1, $cUtilisateur->GetEmail(), $cUtilisateur->GetVille(), $cUtilisateur->GetZip(), $cUtilisateur->GetMdp());
        $stmt->execute();
        $stmt->store_result();
        return $stmt;
    }

      public static function SelectEmail($cUtilisateur)
    {

        $sql = "select Email from utilisateur where email = ?";
        $stmt = self::$conn->prepare($sql);
        $stmt->bind_param("s", $cUtilisateur->GetEmail());
        $stmt->execute();
        $stmt->store_result();
        return $stmt;
    }
    #regioncGymnase


      public static function SelectGym()
    {
        return self::ExecuteSelected("SELECT Id_Gymnase, Nom, Coordonnees_latitude, Coordonnees_longitude, Adresse, Ville, Zip FROM gymnase");

    }

      public static function SelectGym_Sport()
    {
        return self::ExecuteSelected("SELECT Id_Gymnase, Id_Sport FROM gymnase_sport");

    }

      public static function SelectOneGym($cGymnase)
    {
        $sql = "SELECT * FROM gymnase WHERE Id_Gymnase =  ?";
        $stmt = self::$conn->prepare($sql);
        $stmt->bind_param("i", $cGymnase->GetGymId());
        $stmt->execute();
        $stmt->store_result();
        return $stmt;
    }

      public static function InsertGym($cGymnase)
    {
        $sql = "INSERT INTO gymnase (Nom, Coordonnees_latitude, Coordonnees_longitude, Adresse, Ville, Zip) VALUES (?,?,?,?,?,?)";
        $stmt = self::$conn->prepare($sql);
        $stmt->bind_param("siissi", $cGymnase->getGymname(), $cGymnase->getLatitude(), $cGymnase->getLongitude(),$cGymnase->getAdresse(), $cGymnase->getVille(),$cGymnase->getZip());
        $stmt->execute();
        $stmt->store_result();
        return $stmt;
    }

    


     public static function UpdateParaGym($cGymnase)
    {
        $sql = "UPDATE gymnase SET Nom = ?, Coordonnees_latitude = ? , Coordonnees_longitude = ?, Adresse = ?, Ville = ?, Zip = ? WHERE Id_Gymnase = ? ";
        $stmt = self::$conn->prepare($sql);
        $stmt->bind_param("siissii", $cGymnase->getGymname(), $cGymnase->getLatitude(), $cGymnase->getLongitude(),$cGymnase->getAdresse(), $cGymnase->getVille(),$cGymnase->getZip(), $cGymnase->getGymId());
        $stmt->execute();
        $stmt->store_result();
        return $stmt;
    }

     public static function SelectOneGym_sport($cGymnase)
    {
        $sql = "SELECT Id_Sport FROM gymnase_sport WHERE Id_Gymnase = ?";
        $stmt = self::$conn->prepare($sql);
        $stmt->bind_param("i", $cGymnase->GetGymId());
        $stmt->execute();
        $stmt->store_result();
        return $stmt;
    }

     public static function DelOneGym_sport($cGymnase)
    {
        $sql = "DELETE FROM gymnase_sport WHERE Id_Gymnase = ?";
        $stmt = self::$conn->prepare($sql);
        $stmt->bind_param("i", $cGymnase->GetGymId());
        $stmt->execute();
        $stmt->store_result();
        return $stmt;

    }
     public static function InsertGym_sport($cGymnase,)
    {
        $sql = "INSERT INTO gymnase_sport (Id_Gymnase, Id_Sport) VALUES ( ?, ?) ";
        $stmt = self::$conn->prepare($sql);
        $stmt->bind_param("ii", $cGymnase->GetGymId(), $cGymnase->GetSportId());
        $stmt->execute();
        $stmt->store_result();
        return $stmt;
    }

     public static function SelectNamGym()
    {
        return self::ExecuteSelected("SELECT Id_Gymnase, Nom FROM gymnase ");

    }

    #regioncSport


     public static function SelectSport()
    {
        return self::ExecuteSelected("SELECT Id_Sport, Nom_du_sport FROM sport");
        ;
    }



     public static function SelectAllSport()
    {
        return self ::ExecuteSelected("SELECT * FROM sport");

    }
     public static function AddSport($cSport)
    {
        $sql = "INSERT INTO sport (Nom_du_sport, Collectif) VALUES (?, ?) ";
        $stmt = self::$conn->prepare($sql);
        $stmt->bind_param("si", $cSport->getName(), $cSport->getCollec());
        $stmt->execute();
        $stmt->store_result();
        return $stmt;

    }
     public static function SelectDdlSport($selectedGymId)
    {
        $sql = "SELECT DISTINCT s.id_sport, s.nom_du_sport FROM sport s JOIN gymnase_sport g ON s.id_sport = g.Id_Sport WHERE g.Id_Gymnase = ? ";
        $stmt = self::$conn->prepare($sql);
        $stmt->bind_param("i", $selectedGymId);
        $stmt->execute();
        $stmt->store_result();
        return $stmt;
    }

    #regioncReservation


     public static function addReservation($cReservation)
    {
        $sql = "INSERT INTO reservation (Id_Gymnase, Id_Utilisateur, Id_Sport, Date_debut, Date_fin, Commentaire, statut) VALUES (?,?,?,?,?,?,?)";
        $stmt = self::$conn->prepare($sql);
        $stmt->bind_param("iiisssi", $cReservation->getGymId(), $cReservation->getUserId(), $cReservation->getSportId(),$cReservation->getDateDebut(),  $cReservation->getDateFin(),$cReservation->getCommentaire(), $statut = 1);
        $stmt->execute();
        $stmt->store_result();
        return $stmt;
    }


     public static function SelectUserReservations($cReservation)
    {
        $sql = "SELECT r.Id_reservation, r.Date_debut, r.Date_fin, s.Nom_du_sport, r.statut, g.nom FROM reservation r JOIN sport s ON s.Id_Sport = r.Id_Sport JOIN gymnase g ON g.Id_Gymnase = r.Id_Gymnase WHERE Id_Utilisateur = ? and r.statut > 0 ";
        $stmt = self::$conn->prepare($sql);
        $stmt->bind_param("i", $cReservation->GetUserId());
        $stmt->execute();
        $stmt->store_result();
        return $stmt;
    }

     public static function SelectUserValidation()
    {
        return self ::ExecuteSelected("SELECT r.Id_reservation, r.Date_debut, r.Date_fin, s.Nom_du_sport, r.statut, g.nom, u.Nom, u.Prenom FROM reservation r JOIN sport s ON s.Id_Sport = r.Id_Sport JOIN gymnase g ON g.Id_Gymnase = r.Id_Gymnase JOIN utilisateur u ON u.Id_Utilisateur = r.Id_Utilisateur where r.statut > 0");

    }

     public static function SelectUserHistorique($cReservation)
    {
        $sql = "SELECT r.Id_reservation, r.Date_debut, r.Date_fin, s.Nom_du_sport, g.nom, r.Commentaire FROM reservation r JOIN sport s ON s.Id_Sport = r.Id_Sport JOIN gymnase g ON g.Id_Gymnase = r.Id_Gymnase WHERE Id_Utilisateur = ? and statut = 0";
        $stmt = self::$conn->prepare($sql);
        $stmt->bind_param("i", $cReservation->GetUserId());
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }

     public static function SelectReservationDetails($cReservation)
    {
        $sql = "SELECT r.Date_debut, r.Date_fin, r.Commentaire, g.Id_Gymnase, s.Nom_du_sport, s.Id_Sport FROM reservation r JOIN gymnase g ON g.Id_Gymnase = r.Id_Gymnase JOIN sport s ON s.Id_Sport = r.Id_Sport WHERE Id_reservation = ?";
        $stmt = self::$conn->prepare($sql);
        $stmt->bind_param("i", $cReservation->GetResaid());
        $stmt->execute();
        $stmt->store_result();
        return $stmt;
    }

     public static function UpdateValidation($cReservation)
    {
        $sql = "UPDATE reservation SET statut = ? WHERE Id_reservation = ?";
        $stmt = self::$conn->prepare($sql);
        $stmt->bind_param("ii", $cReservation->GetValid(), $cReservation->GetResaid());
        $stmt->execute();
        $stmt->store_result();
        return $stmt;
    }

     public static function EndReservation($cReservation)
    {
         $sql = "UPDATE reservation SET statut = 0 WHERE Id_reservation =  ?";
        $stmt = self::$conn->prepare($sql);
        $stmt->bind_param("i",$cReservation->GetResaid());
        $stmt->execute();
        $stmt->store_result();
        return $stmt;

    }
     public static function UpdateReservation($cReservation)
    {
        $sql = "update reservation set `Id_Gymnase`= ?, `Id_Sport`= ?, `Date_debut`= ?, `Date_fin` = ?, `Commentaire` = ?, `statut` = ? where Id_reservation =?";
        $stmt = self::$conn->prepare($sql);
        $stmt->bind_param("i",$cReservation->GetGymId(),$cReservation->GetSportId(),$cReservation->GetDateDebut() , $cReservation->GetDateFin(), $cReservation->GetCommentaire(),$statut = 1, $cReservation->GetResaid());
        $stmt->execute();
        $stmt->store_result();
        return $stmt;
    }

     public static function DeleteReservation($cReservation)
    {
        $sql = "delete from reservation where Id_reservation = ?";
        $stmt = self::$conn->prepare($sql);
        $stmt->bind_param("i",$cReservation->GetResaid());
        $stmt->execute();
        $stmt->store_result();
        return $stmt;
    }

     public static function SelectValidReservation($cReservation)
    {
        $sql = "SELECT r.Id_reservation, r.Date_debut, r.Date_fin, r.statut, r.Commentaire, g.Nom, s.Nom_du_sport FROM reservation r JOIN gymnase g ON g.Id_Gymnase = r.Id_Gymnase JOIN sport s ON s.Id_Sport = r.Id_Sport WHERE Id_reservation = ?";
        $stmt = self::$conn->prepare($sql);
        $stmt->bind_param("i",$cReservation->GetResaid());
        $stmt->execute();
        $stmt->store_result();
        return $stmt;
    }

}
?>
