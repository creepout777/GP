<?php
class Utilisateur {
    private $id;
    private $username;
    private $email;
    private $password;

    public function __construct($username, $email, $password, $id = null) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
    }

    public function ajouterUtilisateur($pdo) {
        $sql = "INSERT INTO utilisateurs (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':username' => $this->username,
            ':email' => $this->email,
            ':password' => password_hash($this->password, PASSWORD_DEFAULT)
        ]);
        echo "Utilisateur ajouté avec succès.<br>";
    }

    public static function connecterUtilisateur($pdo, $email, $password) {
        $sql = "SELECT * FROM utilisateurs WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            echo "✅ Connexion réussie. Bienvenue " . $user['username'] . " !";
            return new Utilisateur($user['username'], $user['email'], $user['password'], $user['id']);
        } else {
            echo "Identifiants incorrects.";
            return null;
        }
    }
    public function getId() { return $this->id; }
}
?>
