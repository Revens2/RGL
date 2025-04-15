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
        $mail = $cUtilisateur->getEmail();
        $mdp = $cUtilisateur->getMdp();
        return self::ExecuteSelectedtostore("SELECT Id_Utilisateur, isClient, isAdmin FROM utilisateur WHERE Email = '$mail' AND Mot_de_Passe = '$mdp'");

    }

      public static function SelectUser($cUtilisateur)
    {
        $userid = $cUtilisateur->GetUserId();
        return self::ExecuteSelected("SELECT isClient, isAdmin FROM utilisateur WHERE Id_Utilisateur = $userid ");

    }

      public static function SelectAccount($cUtilisateur)
    {  
        $userid= $cUtilisateur->getUserId();
        return self::ExecuteSelected("SELECT Nom, Prenom, Date_de_naissance, Numero_de_telephone, Email, Adresse, Zip, Ville FROM utilisateur WHERE Id_Utilisateur = $userid ");

    }

      public static function UpdateAccount($cUtilisateur)
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
        return self::ExecuteSelected("UPDATE Utilisateur SET `Nom` = '$nom', `Prenom` = '$prenom', `Date_de_naissance` = '$birth', `Numero_de_telephone` = '$tel', `Adresse`= '$adresse', `Email` = '$email', `Ville`= '$ville', `Zip` = '$zip'  WHERE Id_Utilisateur = $userId ");

    }

      public static function AddAccount($cUtilisateur)
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
        return self::ExecuteSelected("INSERT INTO `Utilisateur` (`Nom`, `Prenom`, `Date_de_naissance`, `Numero_de_telephone`, `Adresse`, `isClient`, `Email`, `Ville`, `Zip`, `Mot_de_Passe`) VALUES ('$nom', '$prenom', '$birth', '$tel', '$adresse','$isClient','$email', '$ville', '$zip','$hashed')");

    }

      public static function SelectEmail($cUtilisateur)
    {
        $email = $cUtilisateur->GetEmail();
        return self::ExecuteSelected("select Email from utilisateur where email = '$email' ");

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
        $gymid = $cGymnase->GetGymId();
        return self::ExecuteSelected("SELECT * FROM gymnase WHERE Id_Gymnase = $gymid");

    }

      public static function InsertGym($cGymnase)
    {
        $gymname = $cGymnase->getGymname();
        $latitude=$cGymnase->getLatitude();
        $longitude=$cGymnase->getLongitude();
        $adresse=$cGymnase->getAdresse();
        $ville=$cGymnase->getVille();
        $zip=$cGymnase->getZip();
            
        return self::ExecuteSelected("INSERT INTO gymnase (Nom, Coordonnees_latitude, Coordonnees_longitude, Adresse, Ville, Zip) VALUES ('$gymname','$latitude','$longitude','$adresse','$ville','$zip')");

    }

    


     public static function UpdateParaGym($cGymnase)
    {
        $gymid = $cGymnase->getGymId();
        $gymname = $cGymnase->getGymname();
        $latitude = $cGymnase->getLatitude();
        $longitude = $cGymnase->getLongitude();
        $adresse = $cGymnase->getAdresse();
        $ville = $cGymnase->getVille();
        $zip = $cGymnase->getZip();
        return self::ExecuteSelected("UPDATE gymnase SET Nom = '$gymname', Coordonnees_latitude = '$latitude' , Coordonnees_longitude = '$longitude', Adresse = '$adresse', Ville = '$ville', Zip = $zip WHERE Id_Gymnase = $gymid ");

    }

     public static function SelectOneGym_sport($cGymnase)
    {
        $gymid = $cGymnase->GetGymId();
        return self::ExecuteSelected("SELECT Id_Sport FROM gymnase_sport WHERE Id_Gymnase = $gymid ");

    }

     public static function DelOneGym_sport($cGymnase)
    {
        $gymid = $cGymnase->GetGymId();
        return self::ExecuteSelected("DELETE FROM gymnase_sport WHERE Id_Gymnase = $gymid ");

    }
     public static function InsertGym_sport($cGymnase,)
    {
        $gymId = $cGymnase->GetGymId();
        $sportId = $cGymnase->GetSportId();
        return self::ExecuteSelected("INSERT INTO gymnase_sport (Id_Gymnase, Id_Sport) VALUES ( $gymId, $sportId) ");

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
        $name = $cSport->getName();
        $collec = $cSport->getCollec();
        return self ::ExecuteSelected("INSERT INTO sport (Nom_du_sport, Collectif) VALUES ('$name', $collec)");

    }
     public static function SelectDdlSport($selectedGymId)
    {
        return self ::ExecuteSelected("SELECT DISTINCT s.id_sport, s.nom_du_sport FROM sport s JOIN gymnase_sport g ON s.id_sport = g.Id_Sport WHERE g.Id_Gymnase = $selectedGymId ");

    }

    #regioncReservation


     public static function addReservation($cReservation)
    {
        $gymId = $cReservation->getGymId();
        $userId = $cReservation->getUserId();
        $sportId = $cReservation->getSportId();
        $dateDebut = $cReservation->getDateDebut();
        $dateFin = $cReservation->getDateFin();
        $commentaire = $cReservation->getCommentaire();
        $statut = 1;
        return self ::ExecuteSelected("INSERT INTO reservation (Id_Gymnase, Id_Utilisateur, Id_Sport, Date_debut, Date_fin, Commentaire, statut) VALUES ($gymId, $userId, $sportId, '$dateDebut', '$dateFin', '$commentaire', $statut ) ");

    }


     public static function SelectUserReservations($cReservation)
    {
        $userId=$cReservation->GetUserId();
        return self ::ExecuteSelected("SELECT r.Id_reservation, r.Date_debut, r.Date_fin, s.Nom_du_sport, r.statut, g.nom FROM reservation r JOIN sport s ON s.Id_Sport = r.Id_Sport JOIN gymnase g ON g.Id_Gymnase = r.Id_Gymnase WHERE Id_Utilisateur = $userId and r.statut > 0");

    }

     public static function SelectUserValidation()
    {
        return self ::ExecuteSelected("SELECT r.Id_reservation, r.Date_debut, r.Date_fin, s.Nom_du_sport, r.statut, g.nom, u.Nom, u.Prenom FROM reservation r JOIN sport s ON s.Id_Sport = r.Id_Sport JOIN gymnase g ON g.Id_Gymnase = r.Id_Gymnase JOIN utilisateur u ON u.Id_Utilisateur = r.Id_Utilisateur where r.statut > 0");

    }

     public static function SelectUserHistorique($cReservation)
    {
        $userId= $cReservation->GetUserId();
        return self ::ExecuteSelected("SELECT r.Id_reservation, r.Date_debut, r.Date_fin, s.Nom_du_sport, g.nom, r.Commentaire FROM reservation r JOIN sport s ON s.Id_Sport = r.Id_Sport JOIN gymnase g ON g.Id_Gymnase = r.Id_Gymnase WHERE Id_Utilisateur = $userId and statut = 0");

    }

     public static function SelectReservationDetails($cReservation)
    {
        $reservationId=$cReservation->GetResaid();
        return self::ExecuteSelected("SELECT r.Date_debut, r.Date_fin, r.Commentaire, g.Id_Gymnase, s.Nom_du_sport, s.Id_Sport FROM reservation r JOIN gymnase g ON g.Id_Gymnase = r.Id_Gymnase JOIN sport s ON s.Id_Sport = r.Id_Sport WHERE Id_reservation = $reservationId");

    }

     public static function UpdateValidation($cReservation)
    {
        $valid = $cReservation->GetValid();
        $reservationId = $cReservation->GetResaid();
        return self::ExecuteSelected("UPDATE reservation SET statut = $valid WHERE Id_reservation = $reservationId");

    }

     public static function EndReservation($cReservation)
    {
        $reservationId = $cReservation->GetResaid();
        return self::ExecuteSelected("UPDATE reservation SET statut = 0 WHERE Id_reservation =  $reservationId");

    }
     public static function UpdateReservation($cReservation)
    {
        $resaid = $cReservation->GetResaid();
        $gymId = $cReservation->GetGymId();
        $sportId = $cReservation->GetSportId();
        $dateDebut = $cReservation->GetDateDebut();
        $dateFin = $cReservation->GetDateFin();
        $commentaire = $cReservation->GetCommentaire();
        $statut = 1;
        return self::ExecuteSelected("update reservation set `Id_Gymnase`= $gymId, `Id_Sport`= $sportId, `Date_debut`= '$dateDebut', `Date_fin` = '$dateFin', `Commentaire` = '$commentaire', `statut` = $statut where Id_reservation = $resaid ");

    }

     public static function DeleteReservation($cReservation)
    {
        $resaid = $cReservation->GetResaid();
        return self::ExecuteSelected("delete from reservation where Id_reservation =  $resaid ");

    }

     public static function SelectValidReservation($cReservation)
    {
        $resaid = $cReservation->GetResaid();
        return self::ExecuteSelected("SELECT r.Id_reservation, r.Date_debut, r.Date_fin, r.statut, r.Commentaire, g.Nom, s.Nom_du_sport FROM reservation r JOIN gymnase g ON g.Id_Gymnase = r.Id_Gymnase JOIN sport s ON s.Id_Sport = r.Id_Sport WHERE Id_reservation =  $resaid ");

    }

}
?>
