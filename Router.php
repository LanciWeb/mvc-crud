<?php

namespace app;

use app\Database;

/**
 * Class Router
 * 
 * @package app
 */

class Router
{
  public $getRoutes = [];
  public $postRoutes = [];

  public $db;

  public function __construct()
  {
    $this->db = new Database();
  }

  public function get($url, $fn)
  {
    $this->getRoutes[$url] = $fn;
  }

  public function post($url, $fn)
  {
    $this->postRoutes[$url] = $fn;
  }

  public function resolve()
  {
    $currentUrl = $_SERVER["PATH_INFO"] ?? "/";
    $currentMethod = $_SERVER["REQUEST_METHOD"];
    if ($currentMethod === "GET") $fn = $this->getRoutes[$currentUrl] ?? null;
    else $fn = $this->postRoutes[$currentUrl] ?? null;

    if (!$fn) echo "Page not found";

    //! accepts array($className, $methodName) as first param
    else call_user_func($fn, $this);
  }

  public function renderView($view, $params = [])
  {
    foreach ($params as $key => $value) {
      $$key = $value;
    }

    ob_start();
    include_once __DIR__ . "/views/$view.php";
    $content = ob_get_clean(); // $content viene usato in layout.php

    include_once __DIR__ . "/views/_layout.php";
  }
}
