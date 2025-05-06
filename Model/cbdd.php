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

        $email = $cUtilisateur->getEmail();
        $mdp = $cUtilisateur->getMdp();

        $stmt->bind_param("ss", $email, $mdp);
        $stmt->execute();
        $stmt->store_result();
        return $stmt;
    }

    public static function SelectUser($cUtilisateur)
    {
        $sql = "SELECT isClient, isAdmin FROM utilisateur WHERE Id_Utilisateur = ?";
        $stmt = self::$conn->prepare($sql);

        $userId = $cUtilisateur->GetUserId();

        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }

    public static function SelectAccount($cUtilisateur)
    {
        $sql = "SELECT Nom, Prenom, Date_de_naissance, Numero_de_telephone, Email, Adresse, Zip, Ville FROM utilisateur WHERE Id_Utilisateur = ?";
        $stmt = self::$conn->prepare($sql);

        $userId = $cUtilisateur->GetUserId();

        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }

    public static function UpdateAccount($cUtilisateur)
    {
        $sql = "UPDATE Utilisateur SET `Nom` = ?, `Prenom` = ?, `Date_de_naissance` = ?, `Numero_de_telephone` = ?, `Adresse`= ?, `Email` = ?, `Ville`= ?, `Zip` = ? WHERE Id_Utilisateur = ?";
        $stmt = self::$conn->prepare($sql);

        $nom = $cUtilisateur->GetNom();
        $prenom = $cUtilisateur->GetPrenom();
        $birth = $cUtilisateur->GetBirth();
        $tel = $cUtilisateur->GetTel();
        $adresse = $cUtilisateur->GetAdresse();
        $email = $cUtilisateur->GetEmail();
        $ville = $cUtilisateur->GetVille();
        $zip = $cUtilisateur->GetZip();
        $userId = $cUtilisateur->GetUserId();

        $stmt->bind_param("sssisssii", $nom, $prenom, $birth, $tel, $adresse, $email, $ville, $zip, $userId);
        $stmt->execute();
        $stmt->store_result();
        return $stmt;
    }

    public static function AddAccount($cUtilisateur)
    {
        $sql = "INSERT INTO `Utilisateur` (`Nom`, `Prenom`, `Date_de_naissance`, `Numero_de_telephone`, `Adresse`, `isClient`, `Email`, `Ville`, `Zip`, `Mot_de_Passe`) VALUES (?,?,?,?,?,?,?,?,?,?)";
        $stmt = self::$conn->prepare($sql);

        $nom = $cUtilisateur->GetNom();
        $prenom = $cUtilisateur->GetPrenom();
        $birth = $cUtilisateur->GetBirth();
        $tel = $cUtilisateur->GetTel();
        $adresse = $cUtilisateur->GetAdresse();
        $isClient = 1;
        $email = $cUtilisateur->GetEmail();
        $ville = $cUtilisateur->GetVille();
        $zip = $cUtilisateur->GetZip();
        $mdp = $cUtilisateur->GetMdp();

        $stmt->bind_param("sssisissis", $nom, $prenom, $birth, $tel, $adresse, $isClient, $email, $ville, $zip, $mdp);
        $stmt->execute();
        $stmt->store_result();
        return $stmt;
    }

    public static function SelectEmail($cUtilisateur)
    {
        $sql = "select Email from utilisateur where email = ?";
        $stmt = self::$conn->prepare($sql);

        $email = $cUtilisateur->GetEmail();

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt;
    }

    #endregion

    #region cGymnase

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
        $sql = "SELECT * FROM gymnase WHERE Id_Gymnase = ?";
        $stmt = self::$conn->prepare($sql);

        $gymId = $cGymnase->GetGymId();

        $stmt->bind_param("i", $gymId);
        $stmt->execute();
        $stmt->store_result();
        return $stmt;
    }

    public static function InsertGym($cGymnase)
    {
        $sql = "INSERT INTO gymnase (Nom, Coordonnees_latitude, Coordonnees_longitude, Adresse, Ville, Zip) VALUES (?,?,?,?,?,?)";
        $stmt = self::$conn->prepare($sql);

        $nom = $cGymnase->getGymname();
        $latitude = $cGymnase->getLatitude();
        $longitude = $cGymnase->getLongitude();
        $adresse = $cGymnase->getAdresse();
        $ville = $cGymnase->getVille();
        $zip = $cGymnase->getZip();

        $stmt->bind_param("siissi", $nom, $latitude, $longitude, $adresse, $ville, $zip);
        $stmt->execute();
        $stmt->store_result();
        return $stmt;
    }

    public static function UpdateParaGym($cGymnase)
    {
        $sql = "UPDATE gymnase SET Nom = ?, Coordonnees_latitude = ?, Coordonnees_longitude = ?, Adresse = ?, Ville = ?, Zip = ? WHERE Id_Gymnase = ?";
        $stmt = self::$conn->prepare($sql);

        $nom = $cGymnase->getGymname();
        $latitude = $cGymnase->getLatitude();
        $longitude = $cGymnase->getLongitude();
        $adresse = $cGymnase->getAdresse();
        $ville = $cGymnase->getVille();
        $zip = $cGymnase->getZip();
        $gymId = $cGymnase->getGymId();

        $stmt->bind_param("siissii", $nom, $latitude, $longitude, $adresse, $ville, $zip, $gymId);
        $stmt->execute();
        $stmt->store_result();
        return $stmt;
    }

    #endregion

    #region cSport

    public static function SelectSport()
    {
        return self::ExecuteSelected("SELECT Id_Sport, Nom_du_sport FROM sport");
        ;
    }



    public static function SelectAllSport()
    {
        return self::ExecuteSelected("SELECT * FROM sport");

    }

    public static function AddSport($cSport)
    {
        $sql = "INSERT INTO sport (Nom_du_sport, Collectif) VALUES (?, ?)";
        $stmt = self::$conn->prepare($sql);

        $nomSport = $cSport->getName();
        $collectif = $cSport->getCollec();

        $stmt->bind_param("si", $nomSport, $collectif);
        $stmt->execute();
        $stmt->store_result();
        return $stmt;
    }

    public static function SelectDdlSport($selectedGymId)
    {
        $sql = "SELECT DISTINCT s.id_sport, s.nom_du_sport FROM sport s JOIN gymnase_sport g ON s.id_sport = g.Id_Sport WHERE g.Id_Gymnase = ?";
        $stmt = self::$conn->prepare($sql);

        $gymId = $selectedGymId;

        $stmt->bind_param("i", $gymId);
        $stmt->execute();
        $stmt->store_result();
        return $stmt;
        }


    #endregion

    #region cReservation

    public static function addReservation($cReservation)
    {
        $sql = "INSERT INTO reservation (Id_Gymnase, Id_Utilisateur, Id_Sport, Date_debut, Date_fin, Commentaire, statut) VALUES (?,?,?,?,?,?,?)";
        $stmt = self::$conn->prepare($sql);

        $gymId = $cReservation->getGymId();
        $userId = $cReservation->getUserId();
        $sportId = $cReservation->getSportId();
        $dateDebut = $cReservation->getDateDebut();
        $dateFin = $cReservation->getDateFin();
        $commentaire = $cReservation->getCommentaire();
        $statut = 1;

        $stmt->bind_param("iiisssi", $gymId, $userId, $sportId, $dateDebut, $dateFin, $commentaire, $statut);
        $stmt->execute();
        $stmt->store_result();
        return $stmt;
    }



    public static function SelectUserReservations($cReservation)
    {
        $sql = "SELECT r.Id_reservation, r.Date_debut, r.Date_fin, s.Nom_du_sport, r.statut, g.nom FROM reservation r JOIN sport s ON s.Id_Sport = r.Id_Sport JOIN gymnase g ON g.Id_Gymnase = r.Id_Gymnase WHERE Id_Utilisateur = ? and r.statut > 0 ";
        $stmt = self::$conn->prepare($sql);

        $userId = $cReservation->getUserId();

        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }

     public static function SelectUserValidation()
    {
        return self ::ExecuteSelected("SELECT r.Id_reservation, r.Date_debut, r.Date_fin, s.Nom_du_sport, r.statut, g.nom, u.Nom, u.Prenom FROM reservation r JOIN sport s ON s.Id_Sport = r.Id_Sport JOIN gymnase g ON g.Id_Gymnase = r.Id_Gymnase JOIN utilisateur u ON u.Id_Utilisateur = r.Id_Utilisateur where r.statut > 0");

    }

     public static function SelectUserHistorique($cReservation)
    {
        $sql = "SELECT r.Id_reservation, r.Date_debut, r.Date_fin, s.Nom_du_sport, g.nom, r.Commentaire FROM reservation r JOIN sport s ON s.Id_Sport = r.Id_Sport JOIN gymnase g ON g.Id_Gymnase = r.Id_Gymnase WHERE Id_Utilisateur = ? and statut = 0";
        $stmt = self::$conn->prepare($sql);
        $userId = $cReservation->getUserId();

        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }

     public static function SelectReservationDetails($cReservation)
    {
        $sql = "SELECT r.Date_debut, r.Date_fin, r.Commentaire, g.Id_Gymnase, s.Nom_du_sport, s.Id_Sport FROM reservation r JOIN gymnase g ON g.Id_Gymnase = r.Id_Gymnase JOIN sport s ON s.Id_Sport = r.Id_Sport WHERE Id_reservation = ?";
        $stmt = self::$conn->prepare($sql);
        $resaid = $cReservation->GetResaid();

        $stmt->bind_param("i", $resaid);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }

     public static function UpdateValidation($cReservation)
    {
        $sql = "UPDATE reservation SET statut = ? WHERE Id_reservation = ?";
        $stmt = self::$conn->prepare($sql);

        $resaid = $cReservation->GetResaid();
        $valid = $cReservation->GetValid();

        $stmt->bind_param("ii", $resaid, $valid);
        $stmt->execute();
        $stmt->store_result();
        return $stmt;
    }
    public static function EndReservation($cReservation)
    {
        $sql = "UPDATE reservation SET statut = 0 WHERE Id_reservation = ?";
        $stmt = self::$conn->prepare($sql);

        $resaId = $cReservation->GetResaid();

        $stmt->bind_param("i", $resaId);
        $stmt->execute();
        $stmt->store_result();
        return $stmt;
    }

    public static function UpdateReservation($cReservation)
    {
        $sql = "UPDATE reservation SET `Id_Gymnase`= ?, `Id_Sport`= ?, `Date_debut`= ?, `Date_fin` = ?, `Commentaire` = ?, `statut` = ? WHERE Id_reservation = ?";
        $stmt = self::$conn->prepare($sql);

        $gymId = $cReservation->GetGymId();
        $sportId = $cReservation->GetSportId();
        $dateDebut = $cReservation->GetDateDebut();
        $dateFin = $cReservation->GetDateFin();
        $commentaire = $cReservation->GetCommentaire();
        $statut = 1;
        $resaId = $cReservation->GetResaid();

        $stmt->bind_param("iisssii", $gymId, $sportId, $dateDebut, $dateFin, $commentaire, $statut, $resaId);
        $stmt->execute();
        $stmt->store_result();
        return $stmt;
    }

    public static function DeleteReservation($cReservation)
    {
        $sql = "DELETE FROM reservation WHERE Id_reservation = ?";
        $stmt = self::$conn->prepare($sql);

        $resaId = $cReservation->GetResaid();

        $stmt->bind_param("i", $resaId);
        $stmt->execute();
        $stmt->store_result();
        return $stmt;
    }

    public static function SelectValidReservation($cReservation)
    {
        $sql = "SELECT r.Id_reservation, r.Date_debut, r.Date_fin, r.statut, r.Commentaire, g.Nom, s.Nom_du_sport FROM reservation r JOIN gymnase g ON g.Id_Gymnase = r.Id_Gymnase JOIN sport s ON s.Id_Sport = r.Id_Sport WHERE Id_reservation = ?";
        $stmt = self::$conn->prepare($sql);

        $resaId = $cReservation->GetResaid();

        $stmt->bind_param("i", $resaId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }

}
?>
