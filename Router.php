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
}
