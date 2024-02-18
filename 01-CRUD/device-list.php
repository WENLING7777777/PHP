<?php
  require './parts/connect_db.php';
  $pageName = 'list';
  $title = '設備列表';

  $perPage = 20;

  $page = isset($_GET['page'])?intval($_GET['page']):1;
  if($page<1){
    header('Loctaion: ?page=1');
    exit;
  }

  $t_sql = "SELECT COUNT(*) FROM device";

  $totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0];

  $totalPages = 0;
  $rows = [];

  if($totalRows > 0){
    $totalPages = ceil($totalRows/$perPage);
    if ($page > $totalPages) {
      header('Location: ?page=' . $totalPages);
      exit;
    }

    $sql = sprintf("SELECT * FROM device 
    JOIN place ON device.place_id = place.place_id
    JOIN category ON device.category_id = category.category_id 
    JOIN brand ON device.brand_id = brand.brand_id 
    ORDER BY device_id DESC LIMIT %s, %s", ($page-1)*$perPage, $perPage);
    $rows = $pdo->query($sql)->fetchAll();
  }

  
?>

<?php include './parts/html-head.php'?>
<?php include './parts/navbar.php'?>
<?php include './parts/side-navbar.php' ?>

    <div class="col-9 mx-auto mt-5">
    <div class="row">
    <div class="col">
    <nav aria-label="Page navigation example">
      <ul class="pagination">
        <li class="page-item <?= $page ==1?'disabled':'' ?>">
          <a class="page-link" href="?page=1">
            <i class="fa-solid fa-angles-left"></i>
          </a>
        </li>
        <?php for ($i = $page-3; $i <= $page+3; $i++) :
        if($i>=1 and $i<=$totalPages): ?>
        <li class="page-item <?= $i==$page? 'active': '' ?>">
          <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
        </li>
        <?php endif; endfor; ?>
        <li class="page-item <?= $page == $totalPages?'disabled':'' ?>">
          <a class="page-link" href="?page=<?= $totalPages ?>">
          <i class="fa-solid fa-angles-right"></i>
          </a>
        </li>
      </ul>
    </nav>
    </div>
  </div>
  <div><?= "$totalRows / $perPage" ?></div>
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th scope="col">
              <i class="fa-solid fa-trash-can"></i>
            </th>
            <th scope="col">#</th>
            <th scope="col">設備名稱</th>
            <th scope="col">分類</th>
            <th scope="col">品牌</th>
            <th scope="col">型號</th>
            <th scope="col">設備介紹</th>
            <th scope="col">設備位置</th>
            <th scope="col">基本費</th>
            <th scope="col">時數價格</th>
            <th scope="col">日租價格</th>
            <th scope="col">
              <i class="fa-solid fa-file-pen"></i>
            </th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($rows as $r):?>
            <tr class="<?= $r['device_status_id'] == 0 ? 'table-danger' :'' ?><?= $r['device_status_id'] == 2 ? 'table-warning' :'' ?>">
              <td>
                <a href="javascript: deleteItem('<?= $r['device_id'] ?>')">
                  <i class="fa-solid fa-trash-can"></i>
                </a>
              </td>
              <td><?= $r['device_id'] ?></td>
              <td><?= $r['device_name'] ?></td>
              <td><?= $r['category'] ?></td>
              <td><?= $r['brand'] ?></td>
              <td><?= $r['model'] ?></td>
              <td><?= $r['device_intro'] ?></td>
              <td><?= $r['place'] ?></td>
              <td><?= $r['basic_fee'] ?></td>
              <td><?= $r['time_rate'] ?></td>
              <td><?= $r['day_rate'] ?></td>
              <td>
                <a href="device-edit.php?device_id=<?= $r['device_id'] ?>">
                  <i class="fa-solid fa-file-pen"></i>
                </a>
              </td>
            </tr>
          <?php endforeach ?>
        </tbody>
      </table>
</div>

<?php include './parts/scripts.php'?>
<script>
  function deleteItem(device_id) {
    if (confirm(`確定要刪除 ${device_id} 嗎?`)) {
      location.href = 'device-delete.php?device_id=' + device_id;
    }
  }
</script>
<?php include './parts/html-foot.php'?>