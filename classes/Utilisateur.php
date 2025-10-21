<?php
// Utilisateur.php
// Classe Utilisateur : contient les attributs et les fonctions CRUD, y compris la connexion

require_once __DIR__ . '/../includes/config/Database.php';

class Utilisateur {
    private $conn;
    private $table_name = "utilisateurs";

    public $id;
    public $username;
    public $email;
    public $password;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function ajouterUtilisateur() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (username, email, password) 
                  VALUES (:username, :email, :password)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);

        $hashed_password = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $hashed_password);

        return $stmt->execute();
    }

    public function connecterUtilisateur() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($this->password, $user['password'])) {
            $this->id = $user['id'];
            $this->username = $user['username'];
            return true;
        }
        return false;
    }
}

?>
