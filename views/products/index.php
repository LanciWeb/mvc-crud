<main>
  <header>
    <h1>Products</h1>
  </header>

  <?php if ($_GET['success']) : ?>
    <div class="alert alert-success" role="alert">
      The product was successfully <?= $_GET['success'] ?>!
    </div>
  <?php endif ?>
  <div class="row">
    <div class="col offset-md-6 offset-lg-8 col-lg-3">
      <form action="" method="get">
        <div class="input-group mb-3">
          <input type="text" name="search" placeholder="search" value="<?= $search ?>" />
          <button class="btn btn-outline-secondary" type="submit">Search</button>
        </div>
      </form>
    </div>
    <div class="col col-md-2 col-lg-1">
      <div class="d-flex justify-content-end">
        <a href="/products/create" type="button" class="btn btn-success">Create</a>
      </div>
    </div>
  </div>
  <?php if ($products && !empty($products)) : ?>
    <table class="table">
      <thead>
        <tr>
          <?php foreach ($products[0] as $k => $v) : ?>
            <th scope="col"><?= ucfirst($k) ?></th>
          <?php endforeach ?>
          <th scope="col"></th>
          <th scope="col"></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($products as $p) : ?>
          <tr>
            <?php foreach ($p as $k => $v) : ?>
              <?php if ($k === 'id') : ?>
                <th scope="row"><?= $p[$k] ?></th>
              <?php else : ?>
                <td><?= $p[$k] ?></td>
              <?php endif ?>
            <?php endforeach ?>
            <td>
              <form action="/products/delete" method="post">
                <input type="hidden" name="id" value="<?= $p['id'] ?>" />
                <button type="submit" class="btn btn-danger">Delete</button>
              </form>
            </td>
            <td>
              <form action="/products/update" method="get">
                <input type="hidden" name="id" value="<?= $p['id'] ?>" />
                <button type="submit" class="btn btn-primary">Edit</button>
              </form>
            </td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  <?php else : ?>
    <div class="alert alert-info" role="alert">
      No products found
    </div>
  <?php endif ?>
</main>