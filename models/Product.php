<?php

namespace app\models;

use Exception;
use app\db\Database;

/**
 * Class Product  
 * 
 * @package app\models
 */

class Product
{
  public $id = null;
  public $title = null;
  public $price = null;
  public $description = null;
  public $imagePath = null;
  public $imageFile = null;

  public function load($data)
  {
    $this->id = $data['id'] ?? null;
    $this->title = $data['title'] ?? null;
    $this->price = $data['price'] ?? null;
    $this->description = $data['description'] ?? null;
  }

  public function save()
  {
    $errors = [];
    if (!$this->title) $errors[] = 'Product title is required!';
    if (!$this->price) $errors[] = 'Product price is required!';

    if (empty($errors)) {
      try {
        $db = Database::$db;
        if ($this->id) $db->updateProduct($this);
        else $db->createProduct($this);
      } catch (Exception $e) {
        var_dump($e->getMessage());
      }
    }
    return $errors;
  }
}
