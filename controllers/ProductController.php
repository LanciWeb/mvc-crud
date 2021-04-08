<?php

namespace app\controllers;

use app\Router;

class ProductController
{
  public function index(Router $router)
  {

    if ($_SERVER['REQUEST_METHOD'] !== 'GET') return $router->renderView('404');

    $search = $_GET['search'] ?? '';
    $products = $router->db->getProducts($search);

    return $router->renderView('products/index', ['products' => $products]);
  }

  public function create()
  {
  }

  public function update()
  {
  }

  public function delete(Router $router)
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) return $router->renderView('404');

    $id = $_POST['id'];

    $router->db->deleteProduct($id);

    header('Location: /products');
  }
}
