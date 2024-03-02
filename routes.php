<?php
// routes.php
include './controllers/TripController.php';
include './controllers/StopController.php';


$tripController = new TripController($db);
$nationController = new NationController($db);
$stopController = new StopController($db);


function route($req, $path, $controllerInstance, $method) {
  $request_method = $_SERVER["REQUEST_METHOD"];
  $path_info = $_SERVER['PATH_INFO'];
  $path = str_replace('{id}', '([^/]+)', $path);
  if ($request_method == $req && preg_match("@^$path$@", $path_info, $matches)) {
      $id = isset($matches[1]) ? $matches[1] : null;
      if ($id) {
          return $controllerInstance->$method($id);
      }
      return $controllerInstance->$method();
  }
}

route('POST', '/nations', $nationController, 'create');
route('PUT', '/nations/{id}', $nationController, 'update');
route('GET', '/nations', $nationController, 'getAll');
route('GET','/nations/{id}', $nationController, 'get');
route('DELETE', '/nations/{id}', $nationController, 'delete');

route('GET', '/trips', $tripController, 'findAll');
route('POST', '/trips', $tripController, 'create');
route('PUT', '/trips/{id}', $tripController, 'update');
route('GET','/trips/{id}', $tripController, 'get');
route('DELETE', '/trips/{id}', $tripController, 'delete');

route('POST', '/stops', $stopController, 'create');
route('PUT', '/stops/{id}', $stopController, 'update');
route('GET', '/stops', $stopController, 'get');
route('GET','/stops/{id}', $stopController, 'get');
route('DELETE', '/stops/{id}', $stopController, 'delete');
