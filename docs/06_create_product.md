# 1 Add the create button

In the `products/index.php` we need to add the create button on top of the table.

```html
<div class="col col-md-2 col-lg-1">
  <div class="d-flex justify-content-end">
    <a href="/products/create" type="button" class="btn btn-success">Create</a>
  </div>
</div>
```

> The button is actually just a link to the create route

# 2 Create the file for the creation page

In the `views/products` folder, we add a page which will host the form to add a new product.

```html
<header><h1>Add a new product</h1></header>
<?php include_once('_form.php') ?>
```

> Since the form will be shared with the edit page, we define it in an external file and include it in both pages

# 3 Create the form

Inside the `views/product` folder we create a `_form.php` file which will host the form.

```html
<section>
  <form method="post" action="">
    <div class="mb-3">
      <label for="title" class="form-label">Title</label>
      <input
        type="text"
        class="form-control"
        name="title"
        id="title"
        value="<?= $product['title'] ?>"
      />
    </div>
    <div class="mb-3">
      <label for="description" class="form-label">Description</label>
      <input
        type="textarea"
        class="form-control"
        name="description"
        id="description"
        value="<?= $product['description'] ?>"
      />
    </div>
    <div class="mb-3 ">
      <label class="form-check-label" for="price">Price</label>
      <input
        type="number"
        step=".01"
        class="form-control"
        name="price"
        value="<?= $product['price'] ?>"
      />
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
  </form>
</section>
```

# 4 Create a Product in the Database

In the `Database` class we need to setup the query to create a new product.

```php
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
```

# 5 Handle the create route and data in Product Controller.

In the `ProductController`'s `create` method we need to first check for the method.

We then extract all the data coming in the request from the form and pass it to the **Product Model**' `load` method that will create a **Product instance** out of it.

Once the instance has been created, the controller will use the **Product Model** again and invoke its `save` method which, in turn, triggers the `Database` class' `createProduct` method and stores the newly created product in the database.

> The Product model's `save` method will return errors if any. If no errors are returned, then the `header` method programmatically redirects to index.php.

> If some error is present, then the controller renders the form view again, passing the `$errors` and the inserted `$product` data to pre-fill the form with the data previously inserted.

```php
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
        header('Location: /products');
        exit;
      }
    }

    $router->renderView('products/create', [
      'errors' => $errors,
      'product' => $productData
    ]);
  }
```

# 7 Create a Product instance from form data

In the `models/Product.php` file, create a `load` method that given the data coming from the form will populate the instance of a product.

```php
  public function load($data)
  {
    $this->id = $data['id'] ?? null;
    $this->title = $data['title'] ?? null;
    $this->price = $data['price'] ?? null;
    $this->description = $data['description'] ?? null;
  }
```

# 8 Saving the Model and persist it in the database

In the `models/Product.php` file, create a `save` method that will attempt to persist the instance of the model into the actual database by invoking the `Database` class' `createProduct` method.

```php
public function save()
  {
    $errors = [];
    if (!$this->title) $errors[] = 'Product title is required!';
    if (!$this->price) $errors[] = 'Product price is required!';

    if (empty($errors)) {
      try {
        $db = Database::$db;
        $db->createProduct($this);
      } catch (Exception $e) {
        var_dump($e->getMessage());
      }
    }
    return $errors;
  }
```

First, an `$errors` array is created and some checks on the fields are performed.

If `$errors` is empty, an attempt to save the model into the database is performed inside a `try/catch` block.

> Note that we use the static instance of the Database from the Product model.

in any case, the `$errors` array is returned by the function to be used in the controller and sent back to the view.

# 9 Handling errors in the view

Inside the `_form.php`, we need to take care of giving feedback to the users about the result of their operation.

We can check the presence of the `$errors` array and loop over it, displaying an alert with the errors on top of the form.

```php
<?php if (!empty($errors)) : ?>
  <div class="alert alert-danger">
    <?php foreach ($errors as $error) : ?>
      <?= $error  ?> <br />
    <?php endforeach; ?>
  </div>
<?php endif; ?>
```

# 10 Handling success message

When a product is successfully created, we are redirected to the `products/index.php` view by the product controller. To give a positive feedback to the user we may add a parameter of success to the route and display a success alert based on its presence.

## In the controller

We can add the parameter in the `create` method, before redirecting to the index if there are no errors.

```php
 // other code...
$product = new Product();
      $product->load($productData);
      $errors = $product->save();
      if (empty($errors)) {
        header('Location: /products?success=created');
        exit;
      }
// other code...
```

## In the view

To display a success message, we place an alert on the `products/index.php` page.

```html
<?php if ($_GET['success']) : ?>
<div class="alert alert-success" role="alert">
  The product was successfully
  <?= $_GET['success'] ?>!
</div>
<?php endif ?>
```
