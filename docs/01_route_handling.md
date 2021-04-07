# 1 Mapping Get and Post Routes

## Inside the Router class

Inside the `Router.php` file, we have `$getRoutes` and `$postRoutes` properties. These are arrays that are supposed to contain the GET and POST routes and their respective associated functions.

In order to store the routes inside these array we prepare 2 functions in the router:

```php
  public function get($url, $fn)
  {
    $this->getRoutes[$url] = $fn;
  }

  public function post($url, $fn)
  {
    $this->postRoutes[$url] = $fn;
  }
```

## Inside the entry file

Inside the `public/index.html` we can import the Router and list all the routes we want to handle:

```php
use app\Router;
use app\controllers\ProductController;

require_once '../vendor/autoload.php';

$router = new Router();

$router->get('/', [ProductController::class, 'index']);
$router->get('/products', [ProductController::class, 'index']);
$router->get('/products/create', [ProductController::class, 'create']);
$router->get('/products/update', [ProductController::class, 'update']);
$router->post('/products/create', [ProductController::class, 'create']);
$router->post('/products/update', [ProductController::class, 'update']);
$router->post('/products/delete', [ProductController::class, 'delete']);

```

The first parameter represents the URL, while the second one is an array whose first element is the target class and the second the name of the method of the given class.
