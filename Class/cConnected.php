<?php
class cConnected
{
    private $db;
    private $conn;

    private $servername = "localhost";
    private $username = "root";
    private $password = "root";
    private $dbname = "rgl";

    public function __construct()
    {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);

        if ($this->conn->connect_error) {
            die("La connexion a échoué : " . $this->conn->connect_error);
        }

        $this->db = $this->conn; 
    }


    public function isAuthenticated()
    {
        return isset($_SESSION['user_id']);
    }

 public function login($email, $password)
    {
        $query = $this->db->prepare("SELECT Id_Utilisateur, isClient, isAdmin FROM utilisateur WHERE Email = ? AND Mot_de_Passe = ?");
        if ($query) {
            $hashed_password = md5($password);
            $query->bind_param("ss", $email, $hashed_password);
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
        } else {
            die("Erreur lors de la préparation de la requête : " . $this->db->error);
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
            } else {
                die("Erreur lors de la préparation de la requête : " . $this->db->error);
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
}
