<?php

include 'models/Trip.php';
include 'controllers/NationController.php';
include 'Validator.php';

class TripController
{
  protected $trip;
  protected $db;

  private $nationController;

  public function __construct(Database $db, NationController $nationController)
  {
    $this->db = $db->getDb();
    $this->nationController = $nationController;
    $this->trip = new Trip($this->db);
  }

  public function getTrip() {
    return $this->trip;
  }

  public function create()
  {
    $data = json_decode(file_get_contents('php://input'), true);
    $places = $data['places'];

    try {
      Validator::validatePlaces($places);
      $trip = $this->trip->create($places);
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

  public function findAll()
  { 
    if (isset($_GET['places']) && isset($_GET['nation'])) {
      $places = $_GET['places'];
      $nation = $_GET['nation'];
      try {
        Validator::validatePlaces($places);
        Validator::validateNation($nation, $this->nationController);
        $trips = $this->trip->findAll($nation, $places);
        http_response_code(200);
        echo json_encode($trips);
      } catch (InvalidArgumentException $e) {
        http_response_code(400);
        echo json_encode(['error' => $e->getMessage()]);
      } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'An error occurred']);
      }
    } elseif (isset($_GET['places']) && !isset($_GET['nation'])) {
      $places = $_GET['places'];
      try {
        Validator::validatePlaces($places);
        $trips = $this->trip->findAll(null, $places);
        http_response_code(200);
        echo json_encode($trips);
      } catch (InvalidArgumentException $e) {
        http_response_code(400);
        echo json_encode(['error' => $e->getMessage()]);
      } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'An error occurred']);
      }
    } elseif (!isset($_GET['places']) && isset($_GET['nation'])) {
      $nation = $_GET['nation'];
      try {
        Validator::validateId($nation);
        $trips = $this->trip->findAll($nation, null);
        http_response_code(200);
        echo json_encode($trips);
      } catch (InvalidArgumentException $e) {
        http_response_code(400);
        echo json_encode(['error' => $e->getMessage()]);
      } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'An error occurred']);
      }
    } else {
      $trips = $this->trip->findAll();
      http_response_code(200);
      echo json_encode($trips);
    }

  }

  public function get($id)
  {
    try {
      Validator::validateId($id);
      $trip = $this->trip->getTripWithStops($id);
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
      echo json_encode(['error' => $e->getMessage()]);
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