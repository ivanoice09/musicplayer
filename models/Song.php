<?php
class Song {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllSongs() {
        $conn = $this->db->connect();
        $stmt = $conn->prepare('SELECT * FROM songs');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSongById($id) {
        $conn = $this->db->connect();
        $stmt = $conn->prepare('SELECT * FROM songs WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Add more methods as needed
}
?>