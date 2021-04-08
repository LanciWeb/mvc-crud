<main>
  <header>
    <h1>Products</h1>
  </header>
  <?php if (count($products)) : ?>
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
  <?php else : ?>
    <div class="alert alert-info" role="alert">
      No products found
    </div>
  <?php endif ?>
</main>