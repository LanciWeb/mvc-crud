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
  <form method="post" action="">
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