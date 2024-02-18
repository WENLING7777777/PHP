<?php
require './parts/connect_db.php';
$pageName = 'product';
$title = '列表';

$perPage = 9;

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
    "SELECT p.*, c.category, pp.product_photo 
    FROM product p
    INNER JOIN category c ON p.category_id = c.category_id
    LEFT JOIN product_photo pp ON p.product_id = pp.product_id
    ORDER BY p.product_id DESC LIMIT %s, %s",
    ($page - 1) * $perPage,
    $perPage
);

$rows = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

?>
<?php include './parts/html-head.php'?>
<?php include './parts/navbar.php'?>
<div class="col mx-auto">
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
    <?php foreach ($rows as $r) : ?>
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body">
                <div style="width: 200px; margin: 0 auto;">
                    <img src="uploads/<?= htmlentities($r['product_photo']) ?>" alt="產品圖片" width="200" height="200">
                </div></br>
                <h5 class="card-title">產品名稱: <?= $r['product_name'] ?></h5>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">產品簡介: <?= $r['desc'] ?></li>
                <li class="list-group-item">產品代號: <?= $r['product_id'] ?></li>
                <li class="list-group-item">類別: <?= $r['category'] ?></li>
            </ul>
            <div class="card-footer d-flex justify-content-center">
                <a href="product-info.php?product_id=<?= $r['product_id'] ?>" class="btn btn-primary">
                    <i class="fa-solid fa-file-pen "></i>詳細資訊
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php include './parts/scripts.php'?>
<?php include './parts/html-foot.php' ?>