<?php

namespace app\db;

use PDO;
use app\db\CONFIG;

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
    $statement->execute();
    $products = $statement->fetchAll(PDO::FETCH_ASSOC);

    return $products;
  }
}
