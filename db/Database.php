<?php

namespace app\db;

use PDO;
use Exception;
use app\db\CONFIG;
use app\models\Product;

class Database
{
  public $pdo;

  public static $db;

  public function __construct()
  {
    $this->dbms = CONFIG::$dbms;
    $this->host = CONFIG::$host;
    $this->port = CONFIG::$port;
    $this->db_name = CONFIG::$db_name;
    $this->user = CONFIG::$user;
    $this->password = CONFIG::$password;

    $dsn = "$this->dbms:host=$this->host;port=$this->port;dbname=$this->db_name";
    $this->pdo = new PDO($dsn, $this->user, $this->password);
    $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    self::$db = $this;
  }

  public function getProducts($search)
  {

    if ($search) {
      $statement = $this->pdo->prepare('SELECT * FROM products WHERE title LIKE :search ORDER BY create_date DESC');
      $statement->bindValue(':search', "%$search%");
    } else {
      $statement = $this->pdo->prepare('SELECT * FROM products ORDER BY create_date DESC');
    }

    try {
      $statement->execute();
      $products = $statement->fetchAll(PDO::FETCH_ASSOC);
      return $products;
    } catch (Exception $e) {
      var_dump($e->getMessage());
    }
  }

  public function deleteProduct($id)
  {
    $statement = $this->pdo->prepare('DELETE FROM products WHERE id = :id');
    $statement->bindValue('id', $id);

    try {
      $statement->execute();
    } catch (Exception $e) {
      var_dump($e->getMessage());
    }
  }

  public function createProduct(Product $product)
  {
    $statement = $this->pdo->prepare("INSERT INTO products (title, description, price, create_date) VALUES (:title, :description, :price, :create_date)");

    $statement->bindValue('title', $product->title);
    $statement->bindValue('description', $product->description);
    $statement->bindValue('price', $product->price);
    $statement->bindValue('create_date', date("Y-m-d: H:i:s"));

    try {
      $statement->execute();
      header('Location: index.php');
    } catch (Exception $e) {
      var_dump($e->getMessage());
    }
  }
}
