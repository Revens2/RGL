<?php
class cConnected
{
    private $db;
    private $conn;

    private $servername = "localhost";
    private $username = "root";
    private $password = "root";
    private $dbname = "rgl";

    private string $mail;
    private string $mdp;
    private int $userid;


    private function getMail()
    {
        return $this->mail;
    }

    public function setMail($mail)
    {
        $this->mail = $mail;
    }

  
    private function getMdp()
    {
        return $this->mdp;
    }

    public function setMdp($mdp)
    {
        $this->mdp = $mdp;
    }



    public function __construct()
    {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        $this->db = $this->conn; 
    }


    public function isAuthenticated()
    {
        return isset($_SESSION['user_id']);
    }

 public function login()
    {
        $query = $this->db->prepare("SELECT Id_Utilisateur, isClient, isAdmin FROM utilisateur WHERE Email = ? AND Mot_de_Passe = ?");
        if ($query) {
            $hashed_password = md5($this-> mdp);
            $query->bind_param("ss", $this-> mail, $hashed_password);
            $query->execute();
            $query->store_result();

            if ($query->num_rows == 1) {
                $query->bind_result($user_id, $isClient, $isAdmin);
                $query->fetch();

                $_SESSION['user_id'] = $user_id;
                $_SESSION['client'] = $isClient;
                $_SESSION['admin'] = $isAdmin;
                
                return true;
            } else {
                return false;
            }
        } 
    }

    public function closeConnection()
    {
        $this->db->close();
    }

    public function isAdmin()
    {
        if ($this->isAuthenticated()) {
            $userId = $_SESSION['user_id'];

            $query = $this->db->prepare("SELECT isAdmin FROM utilisateur WHERE Id_Utilisateur = ?");
            if ($query) {
                $query->bind_param("i", $userId);
                $query->execute();
                $result = $query->get_result()->fetch_assoc();
                return $result['isAdmin'] == 1;
            } 
        }
        return false;
    }




    public function isClient()
    {
        if ($this->isAuthenticated()) {
            $userId = $_SESSION['user_id'];
            $query = $this->db->prepare("SELECT isClient FROM utilisateur WHERE Id_Utilisateur = ?");
            $query->bind_param("i", $userId);
            $query->execute();
            $result = $query->get_result()->fetch_assoc();
            return $result['isClient'] == 1;
        }
        return false;
    }


    public function getUserInfo()
    {
        if ($this->isAuthenticated()) {
            $userId = $_SESSION['user_id'];
            $query = $this->db->prepare("SELECT * FROM utilisateur WHERE Id_Utilisateur = ?");
            $query->bind_param("i", $userId);
            $query->execute();
            return $query->get_result()->fetch_assoc();
        }
        return null;
    }
     public function account() {
        $userId = $_SESSION['user_id'];
        $query = $this->db->prepare("SELECT Nom, Prenom, Date_de_naissance, Numero_de_telephone, Email, Adresse, Zip, Ville FROM utilisateur WHERE Id_Utilisateur = ?");
        $query->bind_param("i", $userId);
        $query->execute();
        $result = $query->get_result();
        $userData = $result->fetch_assoc();
        $query->close();
        return $userData;
    }

}
