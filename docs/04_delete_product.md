# 1 Add a delete button to the Product list

In the `products/index.php` we need to add a column for the action buttons and add the delete button.

```html
<td>
  <form action="/products/delete" method="post">
    <input type="hidden" name="id" value="<?= $p['id'] ?>" />
    <button type="submit" class="btn btn-danger">Delete</button>
  </form>
</td>
```

# 2 Delete a product from the DB

Inside the `Database` class we need to set up the query to delete a single product.

```php
public function deleteProduct($id)
  {
    $statement = $this->pdo->prepare('DELETE * FROM products WHERE id = :id');
    $statement->bindValue('id', $id);
    $statement->execute();
  }
```

# 3 Handle the delete route in the ProductController

Inside the `ProductController` we need to insert the logics to handle the deletion.

First we check the method and if the id is present, if so, the database query is triggered.

> If no error occurs, the `header` method programmatically redirects to the

```php
public function delete(Router $router)
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) return $router->renderView('404');

    $id = $_POST['id'];

    $router->db->deleteProduct($id);

    header('Location: /products');
  }
```
