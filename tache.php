<?php
class Tache {
    private $id;
    private $titre;
    private $description;
    private $priorite;
    private $status;
    private $utilisateurId;

    public function __construct($titre, $description, $priorite, $status, $utilisateurId, $id = null) {
        $this->id = $id;
        $this->titre = $titre;
        $this->description = $description;
        $this->priorite = $priorite;
        $this->status = $status;
        $this->utilisateurId = $utilisateurId;
    }

    public function ajouterTache($pdo) {
        $sql = "INSERT INTO taches (titre, description, priorite, status, utilisateurId)
                VALUES (:titre, :description, :priorite, :status, :utilisateurId)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':titre' => $this->titre,
            ':description' => $this->description,
            ':priorite' => $this->priorite,
            ':status' => $this->status,
            ':utilisateurId' => $this->utilisateurId
        ]);
        echo "✅ Tâche ajoutée avec succès.<br>";
    }

    public function modifierTache($pdo) {
        $sql = "UPDATE taches SET titre = :titre, description = :description, priorite = :priorite, status = :status
                WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':titre' => $this->titre,
            ':description' => $this->description,
            ':priorite' => $this->priorite,
            ':status' => $this->status,
            ':id' => $this->id
        ]);
        echo "Tâche modifiée avec succès.<br>";
    }

    public static function supprimerTache($pdo, $id) {
        $sql = "DELETE FROM taches WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        echo "Tâche supprimée.<br>";
    }

    public static function getTacheById($pdo, $id) {
        $sql = "SELECT * FROM taches WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getAllTache($pdo, $utilisateurId) {
        $sql = "SELECT * FROM taches WHERE utilisateurId = :utilisateurId";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':utilisateurId' => $utilisateurId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
