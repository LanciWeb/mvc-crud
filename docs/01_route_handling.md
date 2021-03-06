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

# 2 Resolving routes

## In the Router

In `Router.php` we need a `resolve` function to actually call the controllers' functions associated with the routes.

The resolve function will read the current path and method and will find the corresponding value stored in its `$getRoutes` or `$postRoutes`.

> This value consists of an `array($classname, $methodname)` that we provided in the entry file.

When the value has been found, the `call_user_func` is called. It accepts a function to be executed as its first argument. The other optional arguments are passed as parameters to the function that will be executed.

> Class methods may also be invoked statically using `call_user_func` by passing an `array($classname, $methodname)` to this parameter. This is our case.

```php
public function resolve()
  {
    $currentUrl = $_SERVER["PATH_INFO"] ?? "/";
    $currentMethod = $_SERVER["REQUEST_METHOD"];
    if ($currentMethod === "GET") $fn = $this->getRoutes[$currentUrl] ?? null;
    else $fn = $this->postRoutes[$currentUrl] ?? null;

    if (!$fn) echo "Page not found";

    else call_user_func($fn, $this); //we pass the router
  }
```

> Note that we pass the instance of the router as the second parameter, in order to use it inside the controller methods.

## In the entry file

At the end of the entry file (`public/index.php`), after the list of all the routes, we invoke the `resolve` method, to resolve the current route.

```php
$router->resolve();
```

# 3 Rendering the routes

## In the Router

In order to actually render the route, we need a `renderView` function. It accepts the file name of the view, and an optional associative array of parameters.

As a first step, we loop over the parameters and create a variable per each item whose variable name corresponds to the item key in the original parameter array.

As a second step, we first create the path to the proper file indicated by the `$view` variable.

> If we use `include_once`, it would print the output directly in the browser, but we need to store it in the `$content` variable and make it visible to the layout file.

We can store the output of the file using caching methods.

> `ob_start()` starts the caching so any following `echo` or `include` will print its output inside a local buffer.

> `ob_get_clean()` returns the content of the buffer and cleans it afterwards.

We can assign `ob_get_clean()` to the `$content` variable and then include the `_layout.php` file so that it will return the layout and have access to the `$content`.

```php
public function renderView($view, $params = [])
  {
    foreach ($params as $key => $value) {
      $$key = $value;
    }

    ob_start();
    include_once __DIR__ . "/views/$view.php";
    $content = ob_get_clean(); // $content viene usato in layout.php

    include_once __DIR__ . "/views/_layout.php";
  }
```

This function will be invoked by the Controller's methods, since they are passed the Router instance in the `resolve` function
