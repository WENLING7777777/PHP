<?php
require './parts/connect_db.php';

// 取得資料的 PK
$course_id = isset($_GET['course_id']) ? strval($_GET['course_id']) : 0;

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
$sql = sprintf(
  "SELECT * FROM course
  join category on course.category_id = category.category_id
  join place on course.place_id = place.place_id
  join course_status on course.course_status_id = course_status.course_status_id
  join teacher on course.teacher_id = teacher.teacher_id
  WHERE course.course_id=%s
  ", $pdo->quote($course_id)
);


//撈course_photo表的全部圖片
$photo_sql = "SELECT * FROM course_photo WHERE course_id = '{$course_id}'";
$photo_rows = $pdo->query($photo_sql)->fetchAll();


// echo $sql_all; exit;   //確認是什麼，並結束
$row = $pdo->query($sql)->fetch();  //拿出單筆資料(橫的一整條)

#echo json_encode($row, JSON_UNESCAPED_UNICODE);
$title = '課程 | 詳細頁面';

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

<style>
  form .form-text {
    color: red;
  }
</style>

<div class="col-9">
  <div class="container">
    <div class="row">
      <div class="col-6">
        <div class="card">

          <div class="card-body">
            <h5 class="card-title">課程專區</h5>

            <form name="form1" onsubmit="sendData(event)">
              <input type="hidden" name="course_id" value="<?= $row['course_id'] ?>"> 

              <!-- 課程的資訊們 -->
              <div class="mb-3">

              
                <!-- 放圖片 -->
                <?php foreach ($photo_rows as $photo_r) : ?>
                  <img src="./uploads/<?= $photo_r['course_photo'] ?>" class="card-img-top" alt="Placeholder Image">
                <?php endforeach; ?>


                <h5><?= $row['course_name'] ?></h5>
                <p>課程類別：<?= $row['category'] ?></p>
                <p>課程日期＆時間：<?= $row['course_time'] ?></p>
                <p>課程地點：<?= $row['place'] ?></p>
                <p>人數上限：<?= $row['people'] ?></p>
                
                <p>方案詳情：<?= $row['course_plan'] ?></p>
                <p>課程介紹：<?= $row['course_intro'] ?></p>
                <p>講師名稱：<?= $row['teacher_name'] ?></p>
                <p>課程注意事項：<?= $row['notice'] ?></p>

                <h4>NT$<?= $row['course_price'] ?></h4>

              </div>

              <button type="submit" class="btn btn-primary">加入購物車</button>
            </form>

          </div>
        </div>
      </div>
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
          for(let n in data.errors){
            console.log(`n: ${n}`);
            if(document.form1[n]){
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