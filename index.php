<?php

require('vendor/autoload.php');

use Respect\Rest\Router;

$router = new Router();
$router->any('/contatos/', 'Aspecto\Controller\ContatoController');

echo $router->run();