<?php

include 'models/Nation.php';

class NationController
{
  protected $nation;
  protected $db;

  public function __construct(Database $db)
  {
    $this->db = $db->getDb();
    $this->nation = new Nation($this->db);
  }

  public function create()
  { 
    $data = json_decode(file_get_contents('php://input'), true);
    try {
      Validator::validateTitle($data);
      $title = $data['title'];
      $this->nation->create($title);
      $nation = $this->nation->get($this->db->lastInsertId());
      http_response_code(200);
      echo json_encode($nation);
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
    try {
      Validator::validateId($id);
      Validator::validateTitle($data);
      $title = $data['title'];
      $this->nation->update($id, $title);
      $nation = $this->nation->get($id);
      if (!$nation) {
        http_response_code(404);
        echo json_encode(['error' => 'Nation not found']);
        return;
      }
      http_response_code(200);
      echo json_encode($nation);
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
    $nations = $this->nation->getAll();
    http_response_code(200);
    echo json_encode($nations);
  }

  public function get($id)
  {
    try {
      Validator::validateId($id);
      $nation = $this->nation->get($id);
      if (!$nation) {
        http_response_code(404);
        echo json_encode(['error' => 'Nation not found']);
        return;
      }
      http_response_code(200);
      echo json_encode($nation);
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
      $nation = $this->nation->get($id);
      if (!$nation) {
        http_response_code(404);
        echo json_encode(['error' => 'Nation not found']);
        return;
      }
      $this->nation->delete($id);
      http_response_code(200);
      echo json_encode(['message'=> 'Nation deleted']);
  } catch (InvalidArgumentException $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
  } catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'An error occurred']);
  }
  }

}