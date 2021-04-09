# 1 Add the edit button

In the `products/index.php` we need to add the edit button in the actions column.

```html
<td>
  <form action="/products/update" method="get">
    <input type="hidden" name="id" value="<?= $p['id'] ?>" />
    <button type="submit" class="btn btn-primary">Edit</button>
  </form>
</td>
```

# 2 Create the file for the edit page

In the `views/products` folder, we add a page which will host the form to edit the product.

```html
<header><h1>Edit product</h1></header>
<?php include_once('_form.php') ?>
```

> Since the form will be shared with the create page, we define it in an external file and include it in both pages

# 3 Create the form

The form had already been created for the creation, we will use the exact same one.

# 4 Retrieve the product from the Database

We need to retrieve the product we want to update from the db.
To do so, we need a `getProductById` method in the `Database` class.

```php
public function getProductById($id)
  {
    $statement = $this->pdo->prepare("SELECT * from products WHERE id = :id");
    $statement->bindValue("id", $id);

    try {
      $statement->execute();
      $product = $statement->fetch(PDO::FETCH_ASSOC);
      return $product;
    } catch (Exception $e) {
      echo '<pre>';
      var_dump($e->getMessage());
      echo '</pre>';
    }
  }
```

# 5 Display and pre-fill the edit form

We need the `ProductController` class to handle the `GET` request to the edit page and display the form with the properties of the selected product inside its fields.

To do so, we define the `update` method in the ProductController like so:

```php
 public function update(Router $router)
  {
    $id = $_GET['id'] ?? null;
    if (!$id) header('Location: /products');

    $errors = [];
    $productData = $router->db->getProductById($id);

    $router->renderView('products/update', [
      'errors' => $errors,
      'product' => $productData
    ]);
  }
```

> We used the `Database` class' `getProductById` method to retrieve the product data from the db and passed it to the view with the `Router`'s `renderView` method.

# 6 Updating a Product in the database

In order to update an existing product in the database we need to define an `updateProduct` method inside the `Database` class.

```php
 public function updateProduct(Product $product)
  {
    $statement = $this->pdo->prepare("UPDATE products SET title=:title, description=:description, price=:price WHERE id=:id");

    $statement->bindValue('id', $product->id);
    $statement->bindValue('title', $product->title);
    $statement->bindValue('price', $product->price);
    $statement->bindValue('description', $product->description);

    try {
      $statement->execute();
    } catch (Exception $e) {
      echo '<pre>';
      var_dump($e->getMessage());
      echo '</pre>';
    }
  }
```

# 7 Handling the update request in the Product Controller

Inside the product controller, we need to handle the update `POST` request in the `update` function.

> We just place some logics to be applied only if the request method is `POST`.

In this case, we create a new instance of the Product using the model's `load` method and then persist it to the database with the `save` method.

```php
public function update(Router $router)
  {
    $id = $_GET['id'] ?? null;
    if (!$id) header('Location: /products');

    $errors = [];
    $productData = $router->db->getProductById($id);

    //new piece
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
    //end new piece

    $router->renderView('products/update', [
      'errors' => $errors,
      'product' => $productData
    ]);
  }
```

# 8 Handling update case in the model

Inside the `Product.php` model's `save` function, we need to take into consideration the update case and call the `Database` class' `updateProduct` method when necessary.

> We can easily detect an update case by verifying the presence of the `id`.

```php
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
```
