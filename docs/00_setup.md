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
