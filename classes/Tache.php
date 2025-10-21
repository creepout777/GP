<?php
// Tache.php
// Classe Tache : contient les attributs et les fonctions CRUD pour les tâches
require_once __DIR__ . '/../includes/config/Database.php';

class Tache {
    private $conn;
    private $table_name = "taches";

    public $id;
    public $titre;
    public $description;
    public $priorite;
    public $status;
    public $utilisateurId;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function ajouterTache() {
        $query = "INSERT INTO " . $this->table_name . "
                  (titre, description, priorite, status, utilisateurId)
                  VALUES (:titre, :description, :priorite, :status, :utilisateurId)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':titre', $this->titre);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':priorite', $this->priorite);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':utilisateurId', $this->utilisateurId);

        return $stmt->execute();
    }

    public function modifierTache() {
        $query = "UPDATE " . $this->table_name . "
                  SET titre = :titre, description = :description,
                      priorite = :priorite, status = :status
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':titre', $this->titre);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':priorite', $this->priorite);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

    public function supprimerTache() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    public function getTacheById() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllTache() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE utilisateurId = :utilisateurId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':utilisateurId', $this->utilisateurId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>
