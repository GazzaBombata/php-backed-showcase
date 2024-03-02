<?php
class Nation {
    protected $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function create($title) {
        $stmt = $this->db->prepare("INSERT INTO nation (title) VALUES (:title)");
        $stmt->bindParam(':title', $title);
        $stmt->execute();
        $nation = $this->get($this->db->lastInsertId());
        return $nation;
    }

    public function get($id) {
        $stmt = $this->db->prepare("SELECT * FROM nation WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $title) {
      $stmt = $this->db->prepare('UPDATE nation SET title = :title WHERE id = :id');
      $stmt->bindParam(':title', $title);
      $stmt->bindParam(':id', $id);
      $stmt->execute();
      $nation = $this->get($this->db->lastInsertId());
      return $nation;
    }
    public function delete($id) {
      $stmt = $this->db->prepare('DELETE FROM nation WHERE id = :id');
      $stmt->bindParam(':id', $id);
      $stmt->execute();
      return $stmt->rowCount();
    }
    public function getAll() {
      $stmt = $this->db->query('SELECT * FROM nation');
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}
