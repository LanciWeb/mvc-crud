<?php

namespace app\controllers;

use app\Router;
use app\models\Product;

class ProductController
{
  public function index(Router $router)
  {

    if ($_SERVER['REQUEST_METHOD'] !== 'GET') return $router->renderView('404');

    $search = $_GET['search'] ?? '';
    $products = $router->db->getProducts($search);

    return $router->renderView('products/index', ['products' => $products]);
  }

  public function create(Router $router)
  {
    $errors = [];
    $productData = [
      'title' => '',
      'image' => '',
      'price' => '',
      'description' => '',
    ];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $productData['title'] = $_POST['title'];
      $productData['description'] = $_POST['description'];
      $productData['price'] = (float)$_POST['price'];


      $product = new Product();
      $product->load($productData);
      $errors = $product->save();
      if (empty($errors)) {
        header('Location: /products?success=created');
        exit;
      }
    }

    $router->renderView('products/create', [
      'errors' => $errors,
      'product' => $productData
    ]);
  }

  public function update(Router $router)
  {
    $id = $_GET['id'] ?? null;
    if (!$id) header('Location: /products');

    $errors = [];
    $productData = $router->db->getProductById($id);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $productData['title'] = $_POST['title'];
      $productData['description'] = $_POST['description'];
      $productData['price'] = (float)$_POST['price'];


      $product = new Product();
      $product->load($productData);
      $errors = $product->save();
      if (empty($errors)) {
        header('Location: /products?success=updated');
        exit;
      }
    }

    $router->renderView('products/update', [
      'errors' => $errors,
      'product' => $productData
    ]);
  }

  public function delete(Router $router)
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) return $router->renderView('404');

    $id = $_POST['id'];

    $router->db->deleteProduct($id);

    header('Location: /products');
  }
}
