<?php

namespace app\models;

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

  //Some logics to load the data into a Product instance
  public function load($data)
  {
  }

  //Some logics to load the data into a Product instance
  public function save()
  {
  }
}
