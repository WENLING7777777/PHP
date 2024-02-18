<?php
require './parts/connect_db.php';
$pageName = 'list';
$title = '購物車';

$member_id = $_SESSION['role']['member_id'];

$space_sql = sprintf("SELECT * FROM space_cart JOIN space ON space_cart.space_id = space.space_id WHERE member_id = '{$member_id}'");
$space_rows = $pdo->query($space_sql)->fetchAll();

$course_sql = sprintf(
  "SELECT * FROM course_cart 
    JOIN course ON course_cart.course_id = course.course_id 
    join place on course.place_id = place.place_id
    WHERE member_id = '{$member_id}'"
);
$course_rows = $pdo->query($course_sql)->fetchAll();

$amount = 0;
?>

<?php include './parts/html-head.php' ?>
<?php include './parts/navbar.php' ?>

<div class="col-9 mx-auto mt-5">
    <div class="row">
      <h3 class="mt-5">空間預約</h3>
      <?php foreach ($space_rows as $r) :
      if ($r['space_status_id'] != 0) :
    ?>
        <div class="card mt-3 mb-3">
          <div class="row g-0">
            <div class="col-md-8">
              <div class="card-body">
                <h5 class="card-title"><?= $r['space'] ?></h5>
                <p class="card-text text-body-secondary fs-6">預約日期<?= $r['booking_date'] ?></p>
                <p class="card-text text-body-secondary fs-6">
                開始時間<?= $r['start_time'] ?> 結束時間<?= $r['end_time'] ?>
                </p>
                <h4 class="card-text">共<?= $r['time'] / 2 ?> 小時 $<?= $r['time'] * $r['time_rate'] ?> 元</h4>
                <?php $amount += $r['time'] * $r['time_rate'] ?>
                <a href="space-detail.php?space_id=<?= $r['space_id'] ?>" class="btn btn-primary">查看詳情</a>
              </div>
            </div>
          </div>
        </div>
        <?php endif;
    endforeach ?>
    <h3 class="mt-5">課程購買</h3>
      <?php foreach ($course_rows as $r) : ?>
        <div class="card mt-3 mb-3">
          <div class="row g-0">
            <div class="col-md-8">
              <div class="card-body">
                <h5 class="card-title"><?= $r['course_name'] ?></h5>
                <p class="card-text text-body-secondary fs-6">
                  課程日期＆時間：<?= $r['course_time'] ?>
                </p>
                <p class="card-text text-body-secondary fs-6">
                  課程地點：<?= $r['place'] ?>
                </p>
                <h4 class="card-text">NT$<?= $r['course_price'] ?></h4>
                <?php $amount += $r['course_price'] ?>
                <a href="course-info-desc_add.php?course_id=<?= $r['course_id'] ?>" class="btn btn-primary">查看詳情</a>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach ?>
      <h4 class="text-end mt-5">合計：NT$<?= $amount ?></h4>
      <button type="button" class="btn btn-warning my-5 mx-auto"onclick="addOrder()">確定購買</button>
    </div>
  </div>
</div>
</div>
</div>

<?php include './parts/scripts.php' ?>
<script>
  function addOrder() {
      if (confirm(`確定要購買嗎?`)) {
        location.href = 'cart-api.php';
      }
    }
</script>
<?php include './parts/html-foot.php' ?>