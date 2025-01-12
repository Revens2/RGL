<?php
include '../db_connect.php';



class cGymnase
{
    private $conn;
    private $db;

   

    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    public function GetGym()
    {
        $stmt = $this->conn->prepare("SELECT Id_Gymnase, Nom, Coordonnees_latitude, Coordonnees_longitude, Adresse, Ville, Zip FROM gymnase");
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    public function GetGym_sport()
    {
        $stmt = $this->conn->prepare("SELECT Id_Gymnase, Id_Sport FROM gymnase_sport");
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    public function GetOneGym($gymid)
    {
        $stmt = $this->conn->prepare("SELECT * FROM gymnase WHERE Id_Gymnase = ?");
        $stmt->bind_param("i", $gymid);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    public function GetOneGym_sport($gymid)
    {
        $stmt = $this->conn->prepare("SELECT Id_Sport FROM gymnase_sport WHERE Id_Gymnase = ?");
        $stmt->bind_param("i", $gymid);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }
    public function UpdateParaGym($gymid, $gymname, $latitude, $longitude, $adresse, $ville, $zip)
    {

        $stmt = $this->conn->prepare("UPDATE `gymnase` SET `Nom` = ?, `Coordonnees_latitude` = ?, `Coordonnees_longitude` = ?, `Adresse` = ?, `Ville` = ?, `Zip` = ? WHERE `Id_Gymnase` = ?");
        if (!$stmt) {
            echo "Prepare failed: (" . $this->conn->errno . ") " . $this->conn->error;
            return false;
        }


        $stmt->bind_param("sddsssi", $gymname, $latitude, $longitude, $adresse, $ville, $zip, $gymid);

        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            return false;
        }

        return true;
    }

    public function DelOneGym_sport($gymid)
    {
        $stmt = $this->conn->prepare("DELETE FROM gymnase_sport WHERE Id_Gymnase = ?");
        $stmt->bind_param("i", $gymid);
        $stmt->execute();

    }

    public function InsertGym_sport($gymId, $sportId)
    {
        $stmt = $this->conn->prepare("INSERT INTO gymnase_sport (Id_Gymnase, Id_Sport) VALUES (?, ?)");
        $stmt->bind_param("ii", $gymId, $sportId);

        return $stmt->execute();
    }

    public function getddlgym($selectedGymId)
    {


        if ($selectedGymId === null) {
            $selectedGymId = 0;
        }

        $gymList = [];
        $result = $this->conn->query("SELECT Id_Gymnase, Nom FROM gymnase");
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
