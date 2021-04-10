# 1 Handle image inside the product list

Inside the products table we need to display the image. We can then add an `elseif` statement in our column loop.

```html
<?php foreach ($p as $k =>
$v) : ?>
<?php if ($k === 'id') : ?>
<th scope="row"><?= $p[$k] ?></th>
<?php elseif ($k === 'image') : ?>
<td>
  <img
    src="<?= $p['image'] ?>"
    alt="<?= $p['title'] ?>"
    width="80"
    class="img-fluid"
  />
</td>
<?php else : ?>
<td><?= $p[$k] ?></td>
<?php endif ?>
<?php endforeach ?>
```

# 2 Adding the Image Field in the form

As a first step we need to provide a way to the user to upload an image.

We add a file input in the form.

```html
<form method="post" action="" enctype="multipart/form-data">
  <!-- ...rest of the form -->
  <div class="mb3">
    <?php if ($product['image']) : ?>
    <figure>
      <img src="/<?= $product[image] ?>" class="img-fluid" width="100" />
    </figure>
    <?php endif; ?>
    <label for="image" class="form-label">Image</label>
    <input type="file" name="image" id="image" />
  </div>
  <!-- ...rest of the form -->
</form>
```

> Since we now have a file input, we also need to set the `enctype` attribute of the form to `multipart/form-data`.

> Also, in order to display the image in the edit form, we add an `img` tag above the file input.

# 3 Add image field to Database queries

In the `Database` queries we need to add the `image` fields to both the `createProduct` and the `updateProduct` methods.

> We just need to add them in the statement and bind its value to the `imagePath`.

```php
  public function createProduct(Product $product){
    //...
      $statement = $this->pdo->prepare("INSERT INTO products (image, title, description, price, created_at) VALUES (:image, :title, :description, :price, :created_at)");
    //...
    $statement->bindValue(':image', $product->imagePath);
  }

  public function updateProduct($product){
    //...
      $statement = $this->pdo->prepare("UPDATE products SET image=:image, title=:title, description=:description, price=:price WHERE id=:id");
    //...
      $statement->bindValue(':image', $product->imagePath);
  }


```

# 4 Add image field to Product Controller methods

In the `ProductController` we need to add the `image` field in both the `create` and `edit` methods.

```php
public function create(Router $router)
  {
    $errors = [];
    $productData = [
      'title' => '',
      'image' => '',
      'price' => '',
      'description' => '',
      'imageFile' => ''
    ];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $productData['title'] = $_POST['title'];
      $productData['description'] = $_POST['description'];
      $productData['price'] = (float)$_POST['price'];
      $productData['imageFile'] = $_FILES['image'] ?? null;


      $product = new Product();
      $product->load($productData);
      $errors = $product->save();
      if (empty($errors)) {
        header('Location: /products?success=created');
        exit;
      }
    }

    public function update(Router $router)
  {
    $id = $_GET['id'] ?? null;
    if (!$id) header('Location: /products');

    $errors = [];
    $productData = $router->db->getProductById($id);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $productData['title'] = $_POST['title'];
      $productData['description'] = $_POST['description'];
      $productData['price'] = (float)$_POST['price'];
      $productData['imageFile'] = $_FILES['image'] ?? null;


      $product = new Product();
      $product->load($productData);
      $errors = $product->save();
      if (empty($errors)) {
        header('Location: /products?success=updated');
        exit;
      }
    }

    $router->renderView('products/update', [
      'errors' => $errors,
      'product' => $productData
    ]);
  }
```

> Note that the files are included in the global `$_FILES` array, rather than the `$_POST` / `$_GET` variables.

# 5 Add image to Product Model

In the product model we need to take into consideration the image in both the `load` and `save` methods.

## Load method

In the load method, we just need to add 2 properties for the image: the `imagePath` and the `imageFile`. We create them during the upload process, in the `save` method.

```php
// ...other properties
$this->imageFile = $data['imageFile'] ?? null;
$this->imagePath = $data['imagePath'] ?? null;
```

## Save method

Is the most important part of the uploading process.

### Creating a folder to store the files

As a first thing, we need to check if a folder for storing the files is present. if not, we need to create it.

> The `image` folder is created in the `public` folder

```php
// ...initialited errors array and made check for errors
if (!is_dir(__DIR__ . '/../public/images')) {
  mkdir(__DIR__ . '/../public/images');
}
```

### Actually storing a file

If no error is present, we need to work to recover the image.

> **Note**: when an image is uploaded, it is passed in the `$_FILES` array which has several keys:

- `tmp_name` is a temporary path assigned by the server to the file.
- `name` is the actual name of the file we uploaded (without the path)
- `type` contains the mime type

If the `tmp_name` is present, it means that the file has been temporary stored successfully so this is our first check.

```php
if (empty($errors)) {
  try {
    if ($this->imageFile && $this->imageFile['tmp_name']) {
      if ($this->imagePath) unlink(__DIR__ . '/../public/' . $this->imagePath);

      $this->imagePath = "images/" . UtilHelper::randomString(8) . "/" . $this->imageFile['name'];
      mkdir(dirname(__DIR__ . '/../public/' . $this->imagePath));
      move_uploaded_file($this->imageFile["tmp_name"], __DIR__ . '/../public/' . $this->imagePath);
    }

    $db = Database::$db;
    if ($this->id) $db->updateProduct($this);
    else $db->createProduct($this);

  } catch (Exception $e) {
    var_dump($e->getMessage());
  }
}
```

> if an `imagePath` is already present when we get here in the save function, it means we are editing. So we delete the existing file by using the`unlink` function on that path.

We then create a new path for the uploaded image using a random generated string for the name of its folder.

> To randomly generate a folder name, we use a helper class, described later in this doc.

We create an actual folder with the path already created using both `mkdir` and `dirname` function.

> the `dirname` function extract the folder path from the complete path of a file (leaving out the name of the file.)

As a last step, we use the `move_uploaded_file` function, to move the image from the temporary folder where the server initally stored it to the newly created path.

# Creating a random string generator and an helper class.

Create a `helpers` folder in the root of the project with a `UtilHelper.php` file inside.

This will host a utility class with a static function to randomly generate a string like so;

```php
<?php

namespace app\helpers;

class UtilHelper
{

  public static function randomString($n)
  {
    $characters  = '0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
    $str = '';
    for ($i = 0; $i < $n; $i++) {
      $index = rand(0, strlen($characters) - 1);
      $str .= $characters[$index];
    }
    return $str;
  }
}

```

We can then import the helper in the `models\Product` class:

```php
use app\helpers\UtilHelper
```
