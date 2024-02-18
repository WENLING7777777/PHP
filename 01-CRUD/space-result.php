<?php
  require './parts/connect_db.php';
  $pageName = 'list';
  $title = '空間列表';

  $perPage = 20;

  $page = isset($_GET['page'])?intval($_GET['page']):1;
  if($page<1){
    header('Loctaion: ?page=1');
    exit;
  }

  $t_sql = "SELECT COUNT(*) FROM space";

  $totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0];

  $totalPages = 0;
  $rows = [];

  if($totalRows > 0){
    $totalPages = ceil($totalRows/$perPage);
    if ($page > $totalPages) {
      header('Location: ?page=' . $totalPages);
      exit;
    }

    $sql = sprintf("SELECT * FROM ((space JOIN place ON space.place_id = place.place_id) JOIN area ON place.area_id = area.area_id) JOIN city ON area.city_id = city.city_id JOIN category ON space.category_id = category.category_id ORDER BY space_id DESC LIMIT %s, %s", ($page-1)*$perPage, $perPage);
    $rows = $pdo->query($sql)->fetchAll();
  }

  $space_photo_sql = "SELECT * FROM space_photo";
  $space_photo_rows = $pdo->query($space_photo_sql)->fetchAll();
  
?>

<?php include './parts/html-head.php'?>
<?php include './parts/navbar.php'?>

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
      <div class="row">
        <?php foreach($rows as $r):
          if ($r['space_status_id'] != 0):
          $n = 1;
        ?>
          <div class="col-3 mb-3">
            <div class="card" style="width: 18rem;">
            <?php foreach($space_photo_rows as $p):
              if($p['space_id']==$r['space_id'] and $n==1):
                $n++;?>

              <img src="./uploads/<?= $p['space_photo'] ?>" class="card-img-top" alt="...">
              <?php endif;
            endforeach; ?>
              <div class="card-body">
                <h5 class="card-title"><?= $r['space'] ?></h5>
                <h6 class="card-title"><?= $r['category'] ?></h6>
                <p class="card-text"><?= $r['city'] ?><?= $r['area'] ?></p>
                <p class="card-text">建議人數：<?= $r['accommodate'] ?>人</p>
                <p class="card-text">$<?= $r['time_rate'] ?>/小時</p>

                <a href="space-detail.php?space_id=<?= $r['space_id'] ?>" class="btn btn-primary">查看詳情</a>
              </div>
            </div>
          </div>
          <?php endif;
              endforeach ?>
      </div>
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
    </div>
</div>

<?php include './parts/scripts.php'?>
<script>

</script>
<?php include './parts/html-foot.php'?>