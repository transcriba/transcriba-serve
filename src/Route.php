<?php

namespace Transcriba\Routing;

class Route {
  public $keys;
  public $action;

  function __construct(array $keys, $action) {
    sort($keys);
    $this->keys = $keys;
    $this->action = $action;
  }
}
