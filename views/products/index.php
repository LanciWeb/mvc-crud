<main>
  <header>
    <h1>Products</h1>
  </header>
  <div class="row">
    <div class="col offset-md-6 offset-lg-8 col-lg-3">
      <form action="" method="get">
        <div class="input-group mb-3">
          <input type="text" name="search" placeholder="search" value="<?= $search ?>" />
          <button class="btn btn-outline-secondary" type="submit">Search</button>
        </div>
      </form>
    </div>
  </div>
  <?php if ($products && count($products)) : ?>
    <table class="table">
      <thead>
        <tr>
          <?php foreach ($products[0] as $k => $v) : ?>
            <th scope="col"><?= ucfirst($k) ?></th>
          <?php endforeach ?>
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