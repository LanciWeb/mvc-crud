<?php

namespace app\models;

use Exception;
use app\db\Database;
use app\helpers\UtilHelper;

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
    $this->imageFile = $data['imageFile'] ?? null;
    $this->imagePath = $data['imagePath'] ?? null;
  }

  public function save()
  {
    $errors = [];
    if (!$this->title) $errors[] = 'Product title is required!';
    if (!$this->price) $errors[] = 'Product price is required!';

    //check whether an image folder exists.
    if (!is_dir(__DIR__ . '/../public/images')) {
      mkdir(__DIR__ . '/../public/images');
    }

    if (empty($errors)) {
      try {

        if ($this->imageFile && $this->imageFile['tmp_name']) {

          if ($this->imagePath) unlink(__DIR__ . '/../public/' . $this->imagePath);

          $this->imagePath = "images/" . UtilHelper::randomString(8) . "/" . $this->imageFile['name'];
          mkdir(dirname(__DIR__ . '/../public/' . $this->imagePath));
          move_uploaded_file($this->imageFile["tmp_name"], __DIR__ . '/../public/' . $this->imagePath);
        }

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
