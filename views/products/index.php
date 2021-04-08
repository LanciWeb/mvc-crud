<main>
  <h1>Products</h1>
  <div class="d-flex justify-content-end">
    <a href="create.php" class="btn btn-primary">Add Product</a>
  </div>
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
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>
</main>