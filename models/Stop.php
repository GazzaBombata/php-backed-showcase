<?php
class Stop
{
  protected $db;

  public function __construct(PDO $db)
  {
    $this->db = $db;
  }
  public function create($trip_id, $nation_id)
  {
    $stmt = $this->db->prepare("INSERT INTO itinerary_stop (trip_id, nation_id) VALUES (:trip_id, :nation_id)");
    $stmt->bindParam(':trip_id', $trip_id);
    $stmt->bindParam(':nation_id', $nation_id);
    $stmt->execute();
    $stop = $this->get($this->db->lastInsertId());
    return $stop;
  }

  public function get($id)
  {
    $stmt = $this->db->prepare("SELECT * FROM itinerary_stop WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function update($id, $trip_id, $nation_id)
  {
    $stmt = $this->db->prepare('UPDATE itinerary_stop SET trip_id = :trip_id, nation_id = :nation_id WHERE id = :id');
    $stmt->bindParam(':trip_id', $trip_id);
    $stmt->bindParam(':nation_id', $nation_id);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $stop = $this->get($this->db->lastInsertId());
    return $stop;
  }

  public function delete($id)
  {
    $stmt = $this->db->prepare('DELETE FROM itinerary_stop WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->rowCount();
  }

  public function getAll()
  {
    $stmt = $this->db->query('SELECT * FROM itinerary_stop');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }


}