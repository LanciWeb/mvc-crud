# 1 Handling DB Exceptions

We can wrap sensible parts of code inside a `try/catch` block.

In the `catch` block, we can use the `Exception` class to return the error message.

So inside the `Database` class we can `use Exception` and then use a `try/catch` on the query execution:

```php
use Exception

// ... other code

try{
  $statement->execute();
} catch(Exception $e){
  var_dump($e->getMessage());
}

```
