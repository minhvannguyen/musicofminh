<?php
session_start();

require_once '../app/core/Autoload.php';
require_once '../app/Core/Database.php';
require_once '../app/Core/Router.php';




// Bắt đầu routing
$router = new Router();
$router->handleRequest();

if (isset($_SESSION['user'])) {
    echo "<br>Xin chào " . $_SESSION['user']['name'];
}
?>

