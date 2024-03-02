<?php

include 'models/Stop.php';

class StopController
{
  protected $stop;
  protected $db;

  public function __construct(Database $db)
  {
    $this->db = $db->getDb();
    $this->stop = new Stop($this->db);
  }

  public function get($id)
  {
    try {
      Validator::validateId($id);
      $stop = $this->stop->get($id);
      if (!$stop) {
        http_response_code(404);
        echo json_encode(['error' => 'Stop not found']);
        return;
      }
      http_response_code(200);
      echo json_encode($stop);
    } catch (InvalidArgumentException $e) {
      http_response_code(400);
      echo json_encode(['error' => $e->getMessage()]);
    } catch (Exception $e) {
      http_response_code(500);
      echo json_encode(['error' => 'An error occurred']);
    }
  }

  public function create()
  {
    $data = json_decode(file_get_contents('php://input'), true);
    $trip_id = $data['trip_id'];
    $nation_id = $data['nation_id'];
    try {
      Validator::validateId($trip_id);
      Validator::validateId($nation_id);
      $this->stop->create($trip_id, $nation_id);
      $stop = $this->stop->get($this->db->lastInsertId());
      http_response_code(200);
      echo json_encode($stop);
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
    $trip_id = $data['trip_id'];
    $nation_id = $data['nation_id'];
    try {
      Validator::validateId($id);
      Validator::validateId($trip_id);
      Validator::validateId($nation_id);
      $this->stop->update($id, $trip_id, $nation_id);
      $stop = $this->stop->get($id);
      if (!$stop) {
        http_response_code(404);
        echo json_encode(['error' => 'Stop not found']);
        return;
      }
      http_response_code(200);
      echo json_encode($stop);
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
      $stop = $this->stop->get($id);
      if (!$stop) {
        http_response_code(404);
        echo json_encode(['error' => 'Stop not found']);
        return;
      }
      $message = $this->stop->delete($id);
      http_response_code(200);
      echo json_encode($message);
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
    $stops = $this->stop->getAll();
    http_response_code(200);
    echo json_encode($stops);
  }

}