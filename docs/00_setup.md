# 1 Setup Composer

Run `composer init` on terminal in the root folder

# 2 Add autoload

Add the following in `composer.json` to enable autoload.
We need to specify a root for our `app` namespace.

```json
"autoload": {
    "psr-4": {
      "app\\": "./"
    }
  }
```

After that run `composer update` in the terminal.
Now all files in our root folder will have the app namespace.

# 3 Create entry file

Create a `public` folder and an `index.php` file. This file will handle all requests through a Router class. For now just print a hello world message, then go into public folder in terminal and run `php -S localhost:8080` to run the PHP built-in server.

## Enable Autoload

In the entry file we need to require once the `autoload.php` file from the vendor folder.

```php
require_once('../vendor/autoload.php');
```

# 4 Setup Database Connection

Once a database and its table have been configured on mySQL, we need to connect to the database. First of all, create a `db` folder in the root.

## Create a config file

In the `db` folder, create a `config.php` file that serve as a container for all credentials. It will contain static variables used to establish connection to the database.

## Create a Database Class file

In the `db` folder, create a `Database.php` file which will represent the instance of a database and connect to MySQL.

It will use `PDO` and our `CONFIG` class to connect to the database.
It also creates a static instance of itself to be referred to in other classes.

```php
<?php
namespace app;

use PDO;
use app\CONFIG;

class Database
{
  public $pdo;

  public static $db;

  public function __construct()
  {
    $this->dbms = CONFIG::$dbms;
    $this->host = CONFIG::$host;
    $this->port = CONFIG::$port;
    $this->db_name = CONFIG::$db_name;
    $this->user = CONFIG::$user;
    $this->password = CONFIG::$password;

    $dsn = "$this->dbms:host=$this->host;port=$this->port;dbname=$this->db_name";
    $this->pdo = new PDO($dsn, $this->user, $this->password);
    $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    self::$db = $this;
  }
}
```

# 5 Prepare a Layout

In the root folder, create a `views` folder with a `_layout.php` folder which will serve as a template. It will contain a variable part inside, represented by the `$content` variable.

```html
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Bootstrap CSS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1"
      crossorigin="anonymous"
    />
    <link rel="stylesheet" href="/app.css" />
    <title>Product CRUD</title>
  </head>

  <body>
    <?= $content ?>
  </body>
</html>
```
