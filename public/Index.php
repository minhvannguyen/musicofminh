<?php
session_start();

require_once '../app/config/Autoload.php';
require_once '../app/config/Database.php';
require_once '../app/config/Router.php';

// Bắt đầu routing
$router = new Router();
$router->handleRequest();


?>  

