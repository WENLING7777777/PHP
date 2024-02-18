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

// 有資料时
if ($totalRows > 0) {
  # 總頁數
  $totalPages = ceil($totalRows / $perPage);
  if ($page > $totalPages) {
    header('Location: ?page=' . $totalPages); # 頁面轉向最後一頁
    exit; 
  }

  $sql = sprintf(
    "SELECT exhibition.*, space.space_id, space.space AS space_name FROM exhibition LEFT JOIN space ON exhibition.space_id = space.space_id ORDER BY exhibition.exhibition_id DESC LIMIT %s, %s",
    ($page - 1) * $perPage,
    $perPage
  );
  $rows = $pdo->query($sql)->fetchAll();


  
}

?>
<?php include './parts/html-head.php' ?>
<?php include './parts/navbar.php' ?>

<div class="col-9 mx-auto mt-5">
    <div class="container m-4">
        <div class="row">
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
            <?php foreach ($rows as $r) : ?>
                <div class="col-4">
                    <div class="card mb-4">
                        <!-- 加圖片的卡片 -->
                        <div style="width: 200px; margin:15px;">
                            <!-- 這裡加入展覽圖片 -->
                            <?php
                            $exhibition_id = $r['exhibition_id'];
                            $exhibition_photo_sql = "SELECT * FROM exhibition_photo WHERE exhibition_id='{$exhibition_id}'";
                            $exhibition_photo_rows = $pdo->query($exhibition_photo_sql)->fetchAll();
                            foreach ($exhibition_photo_rows as $photo) :
                            ?>
                                <img src="uploads/<?= htmlentities($photo['exhibition_photo']) ?>" alt="展覽圖片" width="260" height="260"><br>
                            <?php endforeach; ?>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?= $r['exhibition_name'] ?></h5>
                            <p class="card-text card-text-custom"><?= htmlentities($r['exhibition_desc']) ?></p>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>展覽編號：</strong><?= $r['exhibition_id'] ?></li>
                            <li class="list-group-item"><strong>展覽型態：</strong><?= $r['exhibition_type_id'] ?></li>
                            <li class="list-group-item"><strong>展覽人數：</strong><?= $r['exhibition_people'] ?></li>
                            <li class="list-group-item"><strong>開始時間：</strong><?= $r['start_time'] ?></li>
                            <li class="list-group-item"><strong>結束時間：</strong><?= $r['end_time'] ?></li>
                            <li class="list-group-item"><strong>租借空間：</strong><?= $r['space_name'] ?></li>
                        </ul>
                        <!-- 贊助按鈕 -->
                        <div class="card-footer">
                            <a href="exhibition-orderlist-add.php?exhibition_id=<?= $r['exhibition_id'] ?>" class="btn btn-outline-primary">贊助</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<style>
    .card-text-custom {
        height: 43px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
</style>

<?php include './parts/scripts.php' ?>
<?php include './parts/html-foot.php' ?>
