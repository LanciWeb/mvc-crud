<section>
  <?php if (!empty($errors)) : ?>
    <div class="alert alert-danger">
      <?php foreach ($errors as $error) : ?>
        <?= $error  ?> <br />
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>

<section>
  <form method="post" action="" enctype="multipart/form-data">
    <div class="mb3">
      <?php if ($product['image']) : ?><figure><img src="/<?= $product['image'] ?>" class="img-fluid" width="100" /></figure> <?php endif; ?>
      <label for="image" class="form-label">Image</label>
      <input type="file" name="image" id="image" />
    </div>
    <div class="mb-3">
      <label for="title" class="form-label">Title</label>
      <input type="text" class="form-control" name="title" id="title" value="<?= $product['title'] ?>" />
    </div>
    <div class="mb-3">
      <label for="description" class="form-label">Description</label>
      <input type="textarea" class="form-control" name="description" id="description" value="<?= $product['description'] ?>" />
    </div>
    <div class="mb-3 ">
      <label class="form-check-label" for="price">Price</label>
      <input type="number" step=".01" class="form-control" name="price" value="<?= $product['price'] ?>" />
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
  </form>
</section>