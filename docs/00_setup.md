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
