<?php
require './parts/connect_db.php';
$pageName = 'product';
$title = '列表';

$perPage = 20;

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) {
  header('Location: ?page=1');
  exit; 
}

$t_sql = "SELECT COUNT(1) FROM product";
$totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0];
$totalPages = 0;
$rows = [];

if ($totalRows > 0) {
  $totalPages = ceil($totalRows / $perPage);
  if ($page > $totalPages) {
    header('Location: ?page=' . $totalPages);
    exit;
  }

$sql = sprintf(
    "SELECT p.*, s.spec, s.price, c.category, ps.product_status
    FROM product p
    INNER JOIN stock s ON p.product_id = s.product_id
    INNER JOIN category c ON p.category_id = c.category_id
    INNER JOIN product_status ps ON p.product_status_id = ps.product_status_id
    ORDER BY p.product_id DESC LIMIT %s, %s",
    ($page - 1) * $perPage,
    $perPage
);

$rows = $pdo->query($sql)->fetchAll();
}

?>
<?php include './parts/html-head.php'?>
<?php include './parts/navbar.php'?>
<?php include './parts/side-navbar.php'?>
<div class="col-9">
<div class="container">
  <div class="row">
    <div class="col">
      <nav aria-label="Page navigation example">
        <ul class="pagination">
          <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=1">
              <i class="fa-solid fa-angles-left"></i></a>
          </li>

          <?php for ($i = $page - 5; $i <= $page + 5; $i++) :
            if ($i >= 1 and $i <= $totalPages) : ?>
              <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
              </li>
          <?php
            endif;
          endfor; ?>

          <li class="page-item <?= $page == $totalPages ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $totalPages ?>">
              <i class="fa-solid fa-angles-right"></i></a>
          </li>
        </ul>
      </nav>
    </div>
  </div>
  <div><?= "$totalRows / $totalPages" ?></div>
  <div class="row">
    <div class="col">
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th scope="col">
              <i class="fa-solid fa-trash-can"></i>
            </th>
            <th scope="col">product_id</th>
            <th scope="col">product_name</th>
            <th scope="col">category</th>
            <th scope="col">desc</th>
            <th scope="col">discount</th>
            <th scope="col">product_status_id</th>
            <th scope="col">spec</th>
            <th scope="col">price</th>
            <th scope="col">
            <th scope="col">
              <i class="fa-solid fa-file-pen"></i>
            </th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $r) : ?>
            <tr>
              <td><a href="javascript: deleteItem('<?= $r['product_id'] ?>')">
                  <i class="fa-solid fa-trash-can"></i>
                </a></td>
              <td><a href="product-info.php?product_id=<?= $r['product_id'] ?>">
              <?= $r['product_id'] ?>
            </a></td>
              <td><?= $r['product_name'] ?></td>
              <td><?= $r['category'] ?></td>
              <td><?= $r['desc'] ?></td>
              <td><?= $r['discount'] ?></td>
              <td><?= $r['product_status'] ?></td>
              <td><?= $r['spec'] ?></td>
              <td><?= $r['price'] ?></td>
              <td><?= htmlentities($r['product_id']) ?>
                  <!-- <?= strip_tags($r['product_id']) ?> -->
            </td>
              <td><a href="product-edit.php?product_id=<?= $r['product_id'] ?>">
                  <i class="fa-solid fa-file-pen"></i>
                </a></td>
            </tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</div>

<?php include './parts/scripts.php' ?>
<script>
  function deleteItem(product_id) {
    if (confirm(`確定要刪除編號為 ${product_id} 的資料嗎?`)) {
      location.href = 'product-delete.php?product_id=' + product_id;
    }
  }
</script>
<?php include './parts/html-foot.php' ?>