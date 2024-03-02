<?php

include 'models/Trip.php';
include 'controllers/NationController.php';
include 'Validator.php';

class TripController
{
  protected $trip;
  protected $db;

  public function __construct(Database $db)
  {
    $this->db = $db->getDb();
    $this->trip = new Trip($this->db);
  }

  public function create()
  {
    $data = json_decode(file_get_contents('php://input'), true);
    $places = $data['places'];

    try {
      Validator::validatePlaces($places);
      $this->trip->create($places);
      $trip = $this->trip->get($this->db->lastInsertId());
      http_response_code(200);
      echo json_encode($trip);
    } catch (InvalidArgumentException $e) {
      http_response_code(400);
      echo json_encode(['error' => $e->getMessage()]);
    } catch (Exception $e) {
      http_response_code(500);
      echo json_encode(['error' => 'An error occurred']);
    }

  }

  public function update($id)
  {
    $data = json_decode(file_get_contents('php://input'), true);
    $places = $data['places'];
    try {
      Validator::validatePlaces($places);
      $this->trip->update($id, $places);
      $trip = $this->trip->get($id);
      if (!$trip) {
        http_response_code(404);
        echo json_encode(['error' => 'Trip not found']);
        return;
      }
      http_response_code(200);
      echo json_encode($trip);
    } catch (InvalidArgumentException $e) {
      http_response_code(400);
      echo json_encode(['error' => $e->getMessage()]);
    } catch (Exception $e) {
      http_response_code(500);
      echo json_encode(['error' => 'An error occurred']);
    }
  }

  public function getAll()
  {
    $this->trip->getAll();
    return $this->trip;
  }

  public function get($id)
  {
    try {
      Validator::validateId($id);
      $trip = $this->trip->get($id);
      if (!$trip) {
        http_response_code(404);
        echo json_encode(['error' => 'Trip not found']);
        return;
      }
      http_response_code(200);
      echo json_encode($trip);
    } catch (InvalidArgumentException $e) {
      http_response_code(400);
      echo json_encode(['error' => $e->getMessage()]);
    } catch (Exception $e) {
      http_response_code(500);
      echo json_encode(['error' => 'An error occurred']);
    }
  }

  public function delete($id)
  {
    try {
      Validator::validateId($id);
      $trip = $this->trip->get($id);
      if (!$trip) {
        http_response_code(404);
        echo json_encode(['error' => 'Trip not found']);
        return;
      }
      $this->trip->delete($id);
      http_response_code(200);
      echo json_encode(['message' => 'trip deleted']);
    } catch (InvalidArgumentException $e) {
      http_response_code(400);
      echo json_encode(['error' => $e->getMessage()]);
    } catch (Exception $e) {
      http_response_code(500);
      echo json_encode(['error' => 'An error occurred']);
    }
  }
}