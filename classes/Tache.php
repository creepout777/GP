<?php
require_once __DIR__ . '/../includes/config.php';

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

    // Add task
    public function ajouterTache() {
        $query = "INSERT INTO " . $this->table_name . "
                  (titre, description, priorite, status, utilisateurId)
                  VALUES (:titre, :description, :priorite, :status, :utilisateurId)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':titre', $this->titre);
        // Bind description as NULL if empty
        $stmt->bindValue(':description', $this->description ?: null, PDO::PARAM_NULL);
        $stmt->bindParam(':priorite', $this->priorite);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':utilisateurId', $this->utilisateurId);

        return $stmt->execute();
    }

    // Edit task
    public function modifierTache() {
        $query = "UPDATE " . $this->table_name . "
                  SET titre = :titre, description = :description,
                      priorite = :priorite, status = :status
                  WHERE id = :id AND utilisateurId = :utilisateurId";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':titre', $this->titre);
        $stmt->bindValue(':description', $this->description ?: null, PDO::PARAM_NULL);
        $stmt->bindParam(':priorite', $this->priorite);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':utilisateurId', $this->utilisateurId);

        return $stmt->execute();
    }

    // Delete task
    public function supprimerTache() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id AND utilisateurId = :utilisateurId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':utilisateurId', $this->utilisateurId);
        return $stmt->execute();
    }

    // Get single task
    public function getTacheById() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id AND utilisateurId = :utilisateurId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':utilisateurId', $this->utilisateurId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get all tasks for user
    public function getAllTache() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE utilisateurId = :utilisateurId ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':utilisateurId', $this->utilisateurId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

