<?php

namespace app\controllers;

use app\Router;

class ProductController
{
  public function index(Router $router)
  {
    $products = $router->db->getProducts();

    return $router->renderView('products/index', ['products' => $products]);
  }

  public function create()
  {
  }

  public function update()
  {
  }

  public function delete()
  {
  }
}
