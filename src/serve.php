<?php

/*
 * # Transcriba Serve main file
 * @author eisverticker <eisverticker@posteo.de>
 */

declare(strict_types=1);
use Transcriba\Routing\Router;
require_once "Router.php";

/*
 * ## Build Context
 */

$context = [
  '_get' => $_GET
];

// Check if preconditions are met and else throw errors
if(!isset($_SERVER['HTTP_HOST'])){
  throw new Exception('http host variable not set');
  exit;
} else {
  $context['serverHost'] = $_SERVER['HTTP_HOST'];
}

/*
 * ## Helper Functions
 */

require_once "url-helper.php";

/*
 * ## Execute code depending  on GET-Keys
 */
$router = new Router($context);
require_once "routes.php";
$router->route(array_keys($_GET));
