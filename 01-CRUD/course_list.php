<?php
require './parts/connect_db.php';
$pageName = 'course_list';
$title = '課程列表';

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
    ORDER BY course_id DESC LIMIT %s, %s",
    ($page - 1) * $perPage,
    $perPage
  );
  $rows = $pdo->query($sql)->fetchAll();
}



?>
<?php include './parts/html-head.php' ?>
<?php include './parts/navbar.php' ?>
<?php include './parts/side-navbar.php' ?>

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
              <th scope="col">課程編號</th>
              <th scope="col">課程類別</th>
              <th scope="col">課程名稱</th>
              <th scope="col">課程日期＆時間</th>
              <th scope="col">課程地點</th>
              <th scope="col">人數上限</th>
              <th scope="col">課程狀態</th>
              <th scope="col">課程費用</th>
              <!-- <th scope="col">方案詳情</th> -->
              <!-- <th scope="col">課程介紹</th> -->
              <th scope="col">講師名稱</th>
              <!-- <th scope="col">課程注意事項</th> -->
              <th scope="col">
                <i class="fa-solid fa-file-pen"></i>
              </th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($rows as $r) : ?>
              <tr>
                <td><a href="javascript: deleteItem('<?= $r['course_id'] ?>')">
                    <i class="fa-solid fa-trash-can"></i>
                  </a></td>
                <td><?= $r['course_id'] ?></td>
                <td><?= $r['category'] ?></td>  <!-- 要記得改 -->
                <td><?= $r['course_name'] ?></td>
                <td><?= $r['course_time'] ?></td>
                <td><?= $r['place'] ?></td>  <!-- 要記得改 -->
                <td><?= $r['people'] ?></td>
                <td><?= $r['course_status'] ?></td>  <!-- 要記得改 -->
                <td><?= $r['course_price'] ?></td>
                <!-- <td><?= $r['course_plan'] ?></td> -->
                <!-- <td><?= $r['course_intro'] ?></td> -->
                <td><?= $r['teacher_name'] ?></td>  <!-- 要記得改 -->
                <!-- <td><?= $r['notice'] ?></td> -->
                <td><a href="course_edit.php?course_id=<?= $r['course_id'] ?>">
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
  function deleteItem(course_id) {
    if (confirm(`確定要刪除編號為 ${course_id} 的資料嗎?`)) {
      location.href = 'course_delete.php?course_id=' + course_id;
    }
  }
</script>
<?php include './parts/html-foot.php' ?>