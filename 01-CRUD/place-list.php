<?php
  require './parts/connect_db.php';
  $pageName = 'list';
  $title = '地點列表';

  $perPage = 20;

  $page = isset($_GET['page'])?intval($_GET['page']):1;
  if($page<1){
    header('Loctaion: ?page=1');
    exit;
  }

  $t_sql = "SELECT COUNT(*) FROM place";
  $totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0];

  $totalPages = 0;
  $rows = [];

  if($totalRows > 0){
    $totalPages = ceil($totalRows/$perPage);
    if ($page > $totalPages) {
      header('Location: ?page=' . $totalPages);
      exit;
    }

    $sql = sprintf("SELECT * FROM (place JOIN area ON place.area_id = area.area_id) JOIN city ON area.city_id = city.city_id ORDER BY place_id DESC LIMIT %s, %s", ($page-1)*$perPage, $perPage);
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
            <th scope="col">地點名稱</th>
            <th scope="col">地點縣市</th>
            <th scope="col">地點區域</th>
            <th scope="col">地址</th>
            <!-- <th scope="col">地點狀態</th> -->
            <th scope="col">
              <i class="fa-solid fa-file-pen"></i>
            </th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($rows as $r):
            if($_SESSION['role']['role_id'] == 2):
            if($r['place_status_id'] != 0):?>
            <tr>
              <td>
                <a href="javascript: deleteItem('<?= $r['place_id'] ?>')">
                  <i class="fa-solid fa-trash-can"></i>
                </a>
              </td>
              <td><?= $r['place_id'] ?></td>
              <td><?= $r['place'] ?></td>
              <td><?= $r['city'] ?></td>
              <td><?= $r['area'] ?></td>
              <td><?= $r['place_address'] ?></td>
              <!-- <td><?= $r['place_status_id'] ?></td> -->
              <td>
                <a href="place-edit.php?place_id=<?= $r['place_id'] ?>">
                  <i class="fa-solid fa-file-pen"></i>
                </a>
              </td>
            </tr>
            <?php endif; else:?>
              <tr class="<?= $r['place_status_id'] == 0 ? 'table-danger' :'' ?>">
              <td>
                <a href="javascript: deleteItem('<?= $r['place_id'] ?>')">
                  <i class="fa-solid fa-trash-can"></i>
                </a>
              </td>
              <td><?= $r['place_id'] ?></td>
              <td><?= $r['place'] ?></td>
              <td><?= $r['city'] ?></td>
              <td><?= $r['area'] ?></td>
              <td><?= $r['place_address'] ?></td>
              <!-- <td><?= $r['place_status_id'] ?></td> -->
              <td>
                <a href="place-edit.php?place_id=<?= $r['place_id'] ?>">
                  <i class="fa-solid fa-file-pen"></i>
                </a>
              </td>
            </tr>
          <?php endif;endforeach ?>
        </tbody>
      </table>
</div>

<?php include './parts/scripts.php'?>
<script>
  function deleteItem(place_id) {
    if (confirm(`確定要刪除 ${place_id} 嗎?`)) {
      location.href = 'place-delete.php?place_id=' + place_id;
    }
  }
</script>
<?php include './parts/html-foot.php'?>