<?php
require './parts/connect_db.php';
$pageName = 'course_cart';
$title = '課程 | 購物車';

// 取得資料的 PK
// $course_cart_id = isset($_GET['course_cart_id']) ? intval($_GET['course_cart_id']) : 0;

// 會員登入的東西( 以會員id當 PK )
$member_id = $_SESSION['role']['member_id'];

// 課程購物車( 用會員 id 當 PK，去抓資料 )
$sql = sprintf(
"SELECT * FROM course_cart 
JOIN course ON course_cart.course_id = course.course_id 
join place on course.place_id = place.place_id
WHERE member_id = '{$member_id}'"
);
$rows = $pdo->query($sql)->fetchAll();
// 

// if (empty($course_id)) {
//   header('Location: course_list.php');
//   exit; // 結束程式
// }

// 列表的'當筆'資料(可用fetch判斷)
// $sql = "SELECT * FROM course WHERE course_id='{$course_id}'";
// $row = $pdo->query($sql)->fetch();
// if (empty($row)) {
//   header('Location: course_list.php');
//   exit; // 結束程式
// }

// 把join進來的表格和欄位，向右排在一起
// $sql = sprintf(
//   "SELECT * FROM course
//   join category on course.category_id = category.category_id
//   join place on course.place_id = place.place_id
//   join course_status on course.course_status_id = course_status.course_status_id
//   join teacher on course.teacher_id = teacher.teacher_id
//   WHERE course.course_id=%s
//   ", $pdo->quote($course_id)
// );
// echo $sql_all; exit;   //確認是什麼，並結束
// $row = $pdo->query($sql)->fetch();  //拿出單筆資料(橫的一整條)

#echo json_encode($row, JSON_UNESCAPED_UNICODE);
// $title = '課程 | 購物車';

#只要有下拉選單都要記得加!!!
// 課程類別 的下拉選單
// $cate_sql = "SELECT * FROM category";
// $cate_rows = $pdo->query($cate_sql)->fetchAll();

// //場地 的下拉選單
// $place_sql = "SELECT * FROM place";
// $place_rows = $pdo->query($place_sql)->fetchAll();

// // 課程狀態 的下拉選單
// $status_sql = "SELECT * FROM course_status";
// $status_rows = $pdo->query($status_sql)->fetchAll();

// // 講師名稱 的下拉選單
// $teacher_sql = "SELECT * FROM teacher";
// $teacher_rows = $pdo->query($teacher_sql)->fetchAll();

?>
<?php include './parts/html-head.php' ?>
<?php include './parts/navbar.php' ?>
<?php include './parts/side-navbar.php' ?>

<style>
  form .form-text {
    color: red;
  }
</style>

<div class="col-9">
  <div class="container">
    <div class="row">
      <?php foreach ($rows as $r) : ?>
        <div class="card mt-5 mb-3">
            <input type="hidden" name="member_id" value="<?= $r['member_id'] ?>">
            <div class="row g-0">
            
            <!-- 圖片 -->
            <!-- <div class="col-md-4">
              <img src="..." class="img-fluid rounded-start" alt="...">
            </div> -->

            <!-- 內容 -->
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
              </div>
            </div>
            
          </div>
        </div>
      <?php endforeach ?>
    </div>
  </div>
</div>

<?php include './parts/scripts.php' ?>
<script>
  function sendData(e) {
    e.preventDefault(); // 不要讓表單以傳統的方式送出

    // 建立只有資料的表單
    const fd = new FormData(document.form1);

    fetch('course-info-desc_add-api.php', {
        method: 'POST',
        body: fd, // 送出的格式會自動是 multipart/form-data
      }).then(r => r.json())
      .then(data => {
        console.log({
          data
        });
        if (data.success) {
          alert('課程已加入購物車');
          location.href = "./course-info-card.php"
        } else {
          alert('課程未加入購物車');
          for (let n in data.errors) {
            console.log(`n: ${n}`);
            if (document.form1[n]) {
              const input = document.form1[n];
              input.style.border = '2px solid red';
              input.nextElementSibling.innerHTML = data.errors[n];
            }
          }
        }

      })
      .catch(ex => console.log(ex))
  }
</script>
<?php include './parts/html-foot.php' ?>