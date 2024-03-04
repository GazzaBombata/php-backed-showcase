<?php

require_once 'Database.php';
$config = require_once 'config.php';

$db = new Database($config);

include 'routes.php';