<?php
require './parts/connect_db.php';
$pageName = 'course_list';
$title = '課程 | 列表卡片';

$perPage = 25; # 一頁最多有幾筆

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) {
  header('Location: ?page=1'); # 頁面轉向
  exit; # 直接結束這支 php
}

$t_sql = "SELECT COUNT(1) FROM course";

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
    exit; # 直接結束這支 php
  }

  // 列表的全部(可用fetchAll判斷)
  $sql = sprintf(
    "SELECT * FROM course
    join category on course.category_id = category.category_id
    join place on course.place_id = place.place_id
    join course_status on course.course_status_id = course_status.course_status_id
    join teacher on course.teacher_id = teacher.teacher_id
    join course_photo on course.course_id = course_photo.course_id
    ORDER BY course.course_id DESC LIMIT %s, %s",
    ($page - 1) * $perPage,
    $perPage
  );
  $rows = $pdo->query($sql)->fetchAll();
}



?>
<?php include './parts/html-head.php' ?>
<?php include './parts/navbar.php' ?>

<div class="col-9 mx-auto mt-5">
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

    <!-- 卡片 -->
    <div class="row">
      <?php foreach ($rows as $r) : ?>  <!-- 為什麼要包在這層 -->
        <div class="col-4 mb-5">
          
            <div class="card">
              <div class="card-body">
                <img src="./uploads/<?= htmlentities($r['course_photo']) ?>" class="card-img-top" alt="Placeholder Image">
                <h5 class="card-title mt-4"> <?= $r['course_name'] ?> </h5>
                <p>課程類別：<?= $r['category'] ?></p>
                <p>課程日期＆時間：<?= $r['course_time'] ?></p>
                <p>課程地點：<?= $r['place'] ?></p>
                <p>學生人數：<?= $r['people'] ?></p>
                <p>講師名稱：<?= $r['teacher_name'] ?></p>
                <h4>NT$<?= $r['course_price'] ?></h4>
                
                <a href="course-info-desc_add.php?course_id=<?= $r['course_id'] ?>" class="btn btn-primary">
                詳細說明
                </a>
                <!-- <button type="button" class="btn btn-primary"></button> -->

              </div>
            </div>
          
        </div>
      <?php endforeach ?>
    </div>



    <!-- 原本table的位置 -->


  </div>
</div>






<?php include './parts/scripts.php' ?>
<script>
  function deleteItem(course_id) {
    if (confirm(`確定要刪除編號為 ${course_id} 的資料嗎?`)) {
      location.href = 'course_delete.php?course_id=' + course_id;
    }
  }
</script>
<?php include './parts/html-foot.php' ?>