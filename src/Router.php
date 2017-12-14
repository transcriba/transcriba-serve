<?php

namespace Transcriba\Routing;

require_once "Route.php";

class Router {

  private $context;

  /**
   * Registered routes
   *
   * @var array
   */
  private $routes;

  /**
   * Create a new Router instance.
   *
   * @return void
   */
  function __construct($context) {
    $this->routes = [];
    $this->context = $context;
  }

  /**
   * Register a GET route with the router.
   *
   * @param  array  $keys
   * @param  function  $action
   */
  public function get(array $keys, $action ) {
    $this->routes[] = new Route($keys,$action);
  }

  /**
   * Register a GET route with the router.
   *
   * @param  array  $keys
   * @param  function  $action
   */
  private function findRoute(array $keys ) {
    sort($keys);
    // try to find a matching route
    foreach($this->routes as $currentRoute){
      if($currentRoute->keys == $keys){
        return $currentRoute;
      }
    }

    // fallback case
    return new Route($keys, function($context) { echo "Error 404: Route not found"; });;
  }

  /**
   * Find a route by matching the passed keys
   * and execute the associated function
   *
   * @param array $keys
   */
  public function route($keys) {
    $route = $this->findRoute($keys);
    $action = $route->action;
    $action($this->context);
  }
}
