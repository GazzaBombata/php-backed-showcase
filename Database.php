<?php

class Database
{
  protected $db;

  public function __construct($config)
  {
    $host = $config['DB_HOST'];
    $db   = $config['DB_NAME'];
    $user = $config['DB_USER'];
    $pass = $config['DB_PASS'];
    $this->db = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
  }

  public function getDb()
  {
    return $this->db;
  }
}
