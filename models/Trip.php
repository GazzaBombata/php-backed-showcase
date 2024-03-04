<?php
class Trip
{
  protected $db;

  public function __construct(PDO $db)
  {
    $this->db = $db;
  }


  public function create($places)
  {
    $stmt = $this->db->prepare("INSERT INTO trip (places) VALUES (:places)");
    $stmt->bindParam(':places', $places);
    $stmt->execute();
    $tripId = $this->db->lastInsertId();
    // Fetch the trip with its associated nations
    $trip = $this->get($tripId);

    return $trip;
  }

  public function getTripWithStops($id)
  {
    $stmt = $this->db->prepare("SELECT * FROM itinerary_stop WHERE trip_id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $stops = $stmt->fetchAll(PDO::FETCH_ASSOC);
  
    if (empty($stops)) {
      return $this->get($id);
    }

    $stmt = $this->db->prepare("
    SELECT t.*, n.*
    FROM trip t
    INNER JOIN itinerary_stop it_stop ON t.id = it_stop.trip_id
    INNER JOIN nation n ON it_stop.nation_id = n.id
    WHERE t.id = :trip_id
  ");
    $stmt->bindParam(':trip_id', $id);
    $stmt->execute();
    $trip = $stmt->fetchAll(PDO::FETCH_ASSOC);

  
    return $trip;
  }

  public function get($id)
  {
    $stmt = $this->db->prepare("SELECT * FROM trip WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $trip = $stmt->fetch(PDO::FETCH_ASSOC);
    return $trip;
  }

  public function update($id, $places)
  {
    $stmt = $this->db->prepare("UPDATE trip SET places = :places WHERE id = :id");
    $stmt->bindParam(':places', $places);
    $stmt->bindParam(':id', $id);
    $trip = $stmt->execute();
    return $trip;
  }


  public function delete($id)
  {
    $stmt = $this->db->prepare("DELETE FROM trip WHERE id = :id");
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
  }

  public function findAll($nation = null, $places = null)
  {

    $query = "SELECT * FROM trip";
    $params = [];

    if ($nation !== null && $places !== null) {
      $query .= " INNER JOIN itinerary_stop ON trip.id = itinerary_stop.trip_id";
      $query .= " WHERE itinerary_stop.nation_id = :nation AND trip.places = :places";
      $params[':nation'] = $nation;
      $params[':places'] = $places;
    } elseif ($nation !== null) {
      $query .= " INNER JOIN itinerary_stop ON trip.id = itinerary_stop.trip_id";
      $query .= " WHERE itinerary_stop.nation_id = :nation";
      $params[':nation'] = $nation;
    } elseif ($places !== null) {
      $query .= " WHERE trip.places = :places";
      $params[':places'] = $places;
    }

    $stmt = $this->db->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);

  }

}