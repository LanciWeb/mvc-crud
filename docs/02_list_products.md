# 1 Fetch Products from Database

In the `Database` class we need to add a function to fetch all the products from the db.

```php
public function getProducts()
  {
    $statement = $this->pdo->prepare('SELECT * FROM products ORDER_BY create_date DESC');
    $statement->execute();
    $products = $statement->fetchAll(PDO::FETCH_ASSOC);

    return $products;
  }
```

> `PDO::FETCH_ASSOC` Specifies that the fetch method shall return each row as an array indexed by column name as returned in the corresponding result set.
