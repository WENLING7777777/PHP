<?php
require './parts/connect_db.php';
$pageName = 'list';
$title = '展覽管理列表';

$perPage = 20; # 一頁最多有幾筆


$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) {
  header('Location: ?page=1'); 
  exit; 
}

$t_sql = "SELECT COUNT(1) FROM exhibition";

# 總筆數
$totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0];

# 預設值
$totalPages = 0;
$rows = [];

// 有資料時
if ($totalRows > 0) {
  # 總頁數
  $totalPages = ceil($totalRows / $perPage);
  if ($page > $totalPages) {
    header('Location: ?page=' . $totalPages); # 頁面轉向最後一頁
    exit; 
  }


  $sql = sprintf(
    "SELECT * FROM exhibition ORDER BY exhibition_id DESC LIMIT %s, %s",
    ($page - 1) * $perPage,
    $perPage
  );
  $rows = $pdo->query($sql)->fetchAll();

  $sql = sprintf("SELECT * FROM exhibition 
    JOIN exhibition_type ON exhibition.exhibition_type_id = exhibition_type.exhibition_type_id
    ORDER BY exhibition_id DESC LIMIT %s, %s", ($page-1)*$perPage, $perPage);
    $rows = $pdo->query($sql)->fetchAll();

  $space_sql = sprintf("SELECT * FROM exhibition 
  JOIN space ON exhibition.space_id = space.space_id
  ORDER BY space_id DESC LIMIT %s, %s", ($page-1)*$perPage, $perPage);
  $rows = $pdo->query($sql)->fetchAll();
}



?>
<?php include './parts/html-head.php' ?>
<?php include './parts/navbar.php' ?>
<?php include './parts/side-navbar.php' ?>
<div class="col-9" >
<div class="container mt-4">
<div class="text-right"></div>
  <div class="row">
    <div class="col d-flex justify-content-between align-items-center">
      <nav aria-label="Page navigation example">
        <ul class="pagination">
          <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=1">
              <i class="fa-solid fa-angles-left"></i></a>
          </li>
          <?php for ($i = $page - 3; $i <= $page + 3; $i++) :
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
      <a href="exhibition-add.php" class="btn btn-primary btn-md">+ 展覽新增</a>
    </div>
  </div>
  <div><?= "$totalRows / $totalPages" ?></div>
  <div class="row">
    <div class="col">
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th scope="col"></th>
            <th scope="col">展覽編號</th>
            <th scope="col">展覽型態</th>
            <th scope="col">展覽名稱</th>
            <th scope="col">參展人數</th>
            <th scope="col">開始時間</th>
            <th scope="col">結束時間</th>
            <th scope="col">租借空間</th>
            <th scope="col" class="col-4">展覽內容</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $r) : ?>
            <tr>
              <td><a href="javascript: deleteItem('<?= $r['exhibition_id'] ?>')">
                  <i class="fa-solid fa-trash-can"></i>
                </a></td>
              <td><?= $r['exhibition_id'] ?></td>
              <td><?= $r['exhibition_type'] ?></td>
              <td><?= $r['exhibition_name'] ?></td>
              <td><?= $r['exhibition_people'] ?></td>
              <td><?= $r['start_time'] ?></td>
              <td><?= $r['end_time'] ?></td>
              <td><?= $r['space_id'] ?></td>
              <td><?= htmlentities($r['exhibition_desc']) ?>
            </td>
              <td><a href="exhibition-edit.php?exhibition_id=<?= $r['exhibition_id'] ?>">
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
  function deleteItem(exhibition_id) {
    if (confirm(`確定要刪除為 ${exhibition_id} 的資料嗎?`)) {
      location.href = 'exhibition-delete.php?exhibition_id=' + exhibition_id;
    }
  }
</script>
<?php include './parts/html-foot.php' ?>
