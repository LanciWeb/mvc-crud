# 1 Fetch Products from Database

In the `Database` class we need to add a function to fetch all the products from the db.

```php
public function getProducts()
  {
    $statement = $this->pdo->prepare('SELECT * FROM products ORDER BY create_date DESC');
    $statement->execute();
    $products = $statement->fetchAll(PDO::FETCH_ASSOC);

    return $products;
  }
```

> `PDO::FETCH_ASSOC` Specifies that the fetch method shall return each row as an array indexed by column name as returned in the corresponding result set.

# 2 Add a View to list the products

In the `view` folder, we need to create the portion of the page that will represent the `$content` inside the layout.

Create a `product` folder with an `index.php` file inside.
This file will list all our products.

```php
<main>
  <h1>Products</h1>
  <div class="d-flex justify-content-end">
    <a href="create.php" class="btn btn-primary">Add Product</a>
  </div>
  <table class="table">
    <thead>
      <tr>
        <?php foreach ($products[0] as $k => $v) : ?>
          <th scope="col"><?= ucfirst($k) ?></th>
        <?php endforeach ?>
        <th scope="col"></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($products as $p) : ?>
        <tr>
          <?php foreach ($p as $k => $v) : ?>
            <?php if ($k === 'id') : ?>
              <th scope="row"><?= $p[$k] ?></th>
            <?php else : ?>
              <td><?= $p[$k] ?></td>
            <?php endif ?>
          <?php endforeach ?>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>
</main>
```

> **Note:** the view expects a `$products` array to loop on. We will provide it via controller.

# 3 Add the Controller method

Inside the `ProductController` class, we need to define the `index` method in order to get the products from the database and supply them to the view.

We also need to invoke the router's `renderView` function.

```php
public function index(Router $router)
  {
    $products = $router->db->getProducts();

    return $router->renderView('products/index', ['products' => $products]);
  }
```

# 4 Add method check and 404 page

## In the controller

To be sure the request is made properly, we can check the `$_SERVER['REQUEST_METHOD']` in the controller.

```php
if($_SERVER['REQUEST_METHOD'] !== 'GET') return $router->renderView('404');

$products = $router->db->getProducts();

return $router->renderView('products/index', ['products' => $products]);

```

If the method is not the one we expected, we can redirect to a 404 page.

## In the views folder

We can create a `404.php` page in the `views` folder.

```html
<div class="alert alert-danger" role="alert">Page not found!</div>
```
