<?php
class cUtilisateur
{

    private $conn;
    private string $mail = '';
    private string $mdp = '';
    private int $userid = 0;
    private bool $isClient = false;
    private bool $isAdmin = false;
    private string $nom = '';
    private string $prenom = '';
    private string $birth = '';
    private int $tel = 0;
    private string $adresse = '';
    private string $ville = '';
    private int $zip = 0;

    #regionget
    public function getMail()
    {
        return $this->mail;
    }
    public function getMdp()
    {
        return $this->mdp;
    }
    public function GetIsClient()
    {
     
        return $this->isClient;
    }
    
    public function GetIsAdmin()
    {

        return $this->isAdmin;
    }
    public function GetUserId()
    {

        return $this->userid;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function getPrenom()
    {
        return $this->prenom;
    }

    public function getBirth()
    {
        return $this->birth;
    }

    public function getTel()
    {
        return $this->tel;
    }

    public function getAdresse()
    {
        return $this->adresse;
    }

    public function getVille()
    {
        return $this->ville;
    }

    public function getZip()
    {
        return $this->zip;
    }
    #regionSet

    public function setMail($mail)
    {
        $this->mail = $mail;
    }
    public function setMdp($mdp)
    {
        $this->mdp = $mdp;
    }
    public function SetIsClient($isClient)
    {
        $this->isClient = $isClient;
    }
    public function SetIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;
    }

    public function SetUserId($userid)
    {
        $this->userid = $userid;
    }
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    public function setBirth($birth)
    {
        $this->birth = $birth;
    }

    public function setTel($tel)
    {
        $this->tel = $tel;
    }

    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    }

    public function setVille($ville)
    {
        $this->ville = $ville;
    }

    public function setZip($zip)
    {
        $this->zip = $zip;
    }


    public function __construct()
    {
        $this->conn = new cbdd();

        if($this->isAuthenticated()){
            $userId = $_SESSION['user_id'];
            $result = $this->conn->SelectUser($userId);

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $this->isClient = (bool) $row['isClient'];
                $this->isAdmin = (bool) $row['isAdmin'];
            }

        }
            
        

    }

    public function isAuthenticated()
    {
        return isset($_SESSION['user_id']);
    }

 public function login()
    {
            $query = $this->conn->SelectLogin($this);

            if ($query->num_rows == 1) {
                $query->bind_result($user_id, $isClient, $isAdmin);
                $query->fetch();

                $_SESSION['user_id'] = $user_id;
                $this->SetIsClient($isClient);
                $this->SetIsAdmin($isAdmin);
                
                return true;
            } else {
                return false;
            }
        
    }


    public function getUserInfo()
    {
        if ($this->isAuthenticated()) {
            $userId = $_SESSION['user_id'];
            $query = $this->conn->SelectUserInfo($userId);

            return $query->fetch_assoc();
        }
        return null;
    }
     public function account() {
        $query = $this->conn->SelectAccount($this);
        $result = $query;
        $userData = $result->fetch_assoc();
        return $userData;
    }

    public function AJoutAccount()
    {
        $this->setMdp(md5($this->mdp));
        $this->conn->AddAccount($this->nom, $this->prenom, $this->birth, $this->tel, $this->adresse, $this->ville, $this->zip, $this->mail, $this->mdp);
    }

    public function ModifAccount()
    {
        $this->SetUserId($_SESSION['user_id']);
        $this->conn->UpdateAccount($this->userid, $this->nom, $this->prenom, $this->birth, $this->tel, $this->adresse, $this->ville, $this->zip, $this->mail);
    }

     public function VerifAccount()
    {
        return $this->conn->SelectEmail($this->mail);
    }
}
