# 1 Adding the search bar

In the `products/index.php` view, we need to add the markup for the search bar.

```html
<form action="" method="get">
  <div class="input-group mb-3">
    <input
      type="text"
      name="search"
      placeholder="search"
      value="<?= $search ?>"
    />
    <button class="btn btn-outline-secondary" type="submit">Search</button>
  </div>
</form>
```

# 2 Include the term in the query

In the `Database` class we need to modify the `getProducts` method to expect a term to search for and insert it in the query:

```php
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
```

# 3 Get the term and pass it to the db query

In the `ProductController` we need to read the search term from the `$_GET` parameters and pass it to the Database `getProducts` function.

```php
public function index(Router $router)
  {

    if ($_SERVER['REQUEST_METHOD'] !== 'GET') return $router->renderView('404');

    $search = $_GET['search'] ?? '';
    $products = $router->db->getProducts($search);

    return $router->renderView('products/index', ['products' => $products]);
  }

```
