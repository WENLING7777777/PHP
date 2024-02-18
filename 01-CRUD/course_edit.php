<?php
require './parts/connect_db.php';

// 取得資料的 PK
$course_id = isset($_GET['course_id']) ? strval($_GET['course_id']) : 0;

if (empty($course_id)) {
  header('Location: course_list.php');
  exit; // 結束程式
}

// 列表的'當筆'資料(可用fetch判斷)
$sql = "SELECT * FROM course WHERE course_id='{$course_id}'";
$row = $pdo->query($sql)->fetch();
if (empty($row)) {
  header('Location: course_list.php');
  exit; // 結束程式
}

#echo json_encode($row, JSON_UNESCAPED_UNICODE);
$title = '編輯課程資料';

#只要有下拉選單都要記得加!!!
// 課程類別 的下拉選單
$cate_sql = "SELECT * FROM category";
$cate_rows = $pdo->query($cate_sql)->fetchAll();

//場地 的下拉選單
$place_sql = "SELECT * FROM place";
$place_rows = $pdo->query($place_sql)->fetchAll();

// 課程狀態 的下拉選單
$status_sql = "SELECT * FROM course_status";
$status_rows = $pdo->query($status_sql)->fetchAll();

// 講師名稱 的下拉選單
$teacher_sql = "SELECT * FROM teacher";
$teacher_rows = $pdo->query($teacher_sql)->fetchAll();

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
      <div class="col-6">
        <div class="card">

          <div class="card-body">
            <h5 class="card-title">編輯課程資料</h5>

            <form name="form1" onsubmit="sendData(event)">
              <input type="hidden" name="course_id" value="<?= $row['course_id'] ?>"> <!--  -->
              
              <!-- edit.php 要記得加 value 才撈的到列表原本的值 !!!!! -->

              <!-- 課程類別 -->
              <div class="mb-3">
                <label for="category_id" class="form-label">課程類別</label>
                <!-- 課程類別的下拉選單 -->
                <select class="form-select" name="category_id" id="category_id">
                  <?php 
                  foreach($cate_rows as $cate_r):
                  ?>
                  <option value="<?= $cate_r['category_id'] ?>" <?php if($cate_r['category_id'] === $row['category_id']): ?> selected <?php endif; ?>>
                    <?= $cate_r['category_id'] . $cate_r['category'] ?>
                  </option>
                  <?php endforeach ?>
                </select>
                <div class="form-text"></div>
              </div>

              <!-- 課程名稱 -->
              <div class="mb-3">
                <label for="course_name" class="form-label">課程名稱</label>
                <input type="text" class="form-control" id="course_name" name="course_name" 
                value="<?= $row['course_name'] ?>">
                <div class="form-text"></div>
              </div>
              <!-- htmlentities($row['course_name']) -->

              <!-- 課程日期＆時間 -->
              <div class="mb-3">
                <label for="course_time" class="form-label">課程日期＆時間</label>
                <input type="datetime-local" class="form-control" id="course_time" name="course_time" value="<?= $row['course_time'] ?>">
                <div class="form-text"></div>
              </div>


              <!-- 場地 -->
              <div class="mb-3">
                <label for="place_id" class="form-label">課程場地</label>
                <!-- 課程類別的下拉選單 -->
                <select class="form-select" name="place_id" id="place_id">
                  <?php 
                  foreach($place_rows as $place_r):
                  ?>
                  <option value="<?= $place_r['place_id'] ?>" <?php if($place_r['place_id'] === $row['place_id']): ?> selected <?php endif; ?>>
                    <?= $place_r['place'] ?>
                  </option>
                  <?php endforeach ?>
                </select>
                <div class="form-text"></div>
              </div>

              
              <!-- 課程學生人數 -->
              <div class="mb-3">
                <label for="people" class="form-label">人數上限</label>
                <input type="number" class="form-control" id="people" name="people" value="<?= $row['people'] ?>">
                <div class="form-text"></div>
              </div>

              <!-- 課程狀態 -->
              <div class="mb-3">
                <label for="course_status_id" class="form-label">課程狀態</label>
                <select class="form-select" name="course_status_id" id="course_status_id">
                  <?php 
                  foreach($status_rows as $status_r):
                  ?>
                  
                  <option value="<?= $status_r['course_status_id'] ?>" <?php if($status_r['course_status_id'] === $row['course_status_id']): ?> selected <?php endif; ?>>
                    <?= $status_r['course_status'] ?>
                  </option>
                  <?php endforeach ?>
                </select>
                <div class="form-text"></div>
              </div>

              <!-- 課程費用 -->
              <div class="mb-3">
                <label for="course_price" class="form-label">課程費用</label>
                <input type="number" class="form-control" id="course_price" name="course_price" value="<?= $row['course_price'] ?>">
                <div class="form-text"></div>
              </div>

              <!-- 方案詳情 -->
              <div class="mb-3">
                <label for="course_plan" class="form-label">方案詳情</label>
                <textarea class="form-control" name="course_plan" id="course_plan" cols="30" rows="5"><?= $row['course_plan'] ?></textarea>
                <div class="form-text"></div>
              </div>

              <!-- 課程介紹 -->
              <div class="mb-3">
                <label for="course_intro" class="form-label">課程介紹</label>
                <textarea class="form-control" name="course_intro" id="course_intro" cols="30" rows="5"><?= $row['course_intro'] ?></textarea>
                <div class="form-text"></div>
              </div>

              <!-- 講師名稱 -->
              <div class="mb-3">
                <label for="teacher_id" class="form-label">講師名稱</label>
                <!-- 講師的下拉選單 -->
                <select class="form-select" name="teacher_id" id="teacher_id">
                  <?php 
                  foreach($teacher_rows as $teacher_r):
                  ?>
                  <option value="<?= $teacher_r['teacher_id'] ?>" <?php if($teacher_r['teacher_id'] === $row['teacher_id']): ?> selected <?php endif; ?>>
                    <?= $teacher_r['teacher_name'] ?>
                  </option>
                  <?php endforeach ?>
                </select>
                <div class="form-text"></div>
              </div>

              <!-- 課程注意事項 -->
              <div class="mb-3">
                <label for="notice" class="form-label">課程注意事項</label>
                <textarea class="form-control" name="notice" id="notice" cols="30" rows="5"><?= $row['notice'] ?></textarea>
                <div class="form-text"></div>
              </div>

              <!-- <div class="mb-3">
                <label for="email" class="form-label">email</label>
                <input type="text" class="form-control" id="email" name="email"
                value="<?= htmlentities($row['email']) ?>">
                <div class="form-text"></div>
                <button class="btn btn-danger" type="button">btn 預設的 type 為 submit</button>
              </div> -->
              <!-- <div class="mb-3">
                <label for="mobile" class="form-label">mobile</label>
                <input type="text" class="form-control" id="mobile" name="mobile"
                value="<?= htmlentities($row['mobile']) ?>">
                <div class="form-text"></div>
              </div> -->
              <!-- <div class="mb-3">
                <label for="birthday" class="form-label">birthday</label>
                <input type="date" class="form-control" id="birthday" name="birthday"
                value="<?= $row['birthday'] ?>">
                <div class="form-text"></div>
              </div> -->
              <!-- <div class="mb-3">
                <label for="address" class="form-label">address</label>
                <textarea class="form-control" name="address" id="address" cols="30" 
                rows="3"><?= htmlentities($row['address']) ?></textarea>
                <div class="form-text"></div>
              </div> -->

              <button type="submit" class="btn btn-primary">修改</button>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include './parts/scripts.php' ?>
<script>
  // const initcateVals = {category_id: `${category_id}`};
  // const category_id = document.querySelector('#category_id');
  // category_id.value = initcateVals.category_id;
  
  // category_id.value = "<?= $cate_r['category_id'] ?>";
  // category_id.value = initcateVals.category_id;

  // const selectedValue = document.form1.options.value; 
  // category_id.value = selectedValue;
  // console.log(selectedValue);

  // const a = document.form1.option.value;
  // console.log(initcateVals_test);
  // category_id.value = a;

  // const category_id = document.querySelector('#category_id');
  // const index = category_id.selectIndex;
  // category_id.value = category_id.option[index].value;

  // const category_id = document.querySelector('#category_id');
  
  // if( category_id.value === '<?= $cate_r['category_id'] ?>' ){
  //   category_id.value = '<?= $cate_r['category_id'] ?>';
  // } else {
  //   category_id.value = '';
  // }

  // const category_id = document.querySelector('#category_id');
  // const selected = '<?= $cate_r['category_id'] ?>'; // 將欲選擇的 category_id 存為變數

  // category_id.value = selected; 



  /*
  const name_in = document.form1.name;
  const email_in = document.form1.email;
  const mobile_in = document.form1.mobile;
  const fields = [name_in, email_in, mobile_in];

  function validateEmail(email) {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
  }

  function validateMobile(mobile) {
    const re = /^09\d{2}-?\d{3}-?\d{3}$/;
    return re.test(mobile);
  }
  */

  function sendData(e) {
    e.preventDefault(); // 不要讓表單以傳統的方式送出

    // 外觀要回復原來的狀態
    // fields.forEach(field => {
    //   field.style.border = '1px solid #CCCCCC';
    //   field.nextElementSibling.innerHTML = '';
    // })

    // TODO: 資料在送出之前, 要檢查格式
    // let isPass = true; // 有沒有通過檢查

    // if (name_in.value.length < 2) {
    //   isPass = false;
    //   name_in.style.border = '2px solid red';
    //   name_in.nextElementSibling.innerHTML = '請填寫正確的姓名';
    // }

    // if (!validateEmail(email_in.value)) {
    //   isPass = false;
    //   email_in.style.border = '2px solid red';
    //   email_in.nextElementSibling.innerHTML = '請填寫正確的 Email';
    // }

    // 非必填
    // if (mobile_in.value && !validateMobile(mobile_in.value)) {
    //   isPass = false;
    //   mobile_in.style.border = '2px solid red';
    //   mobile_in.nextElementSibling.innerHTML = '請填寫正確的手機號碼';
    // }


    // if (!isPass) {
    //   return; // 沒有通過就不要發送資料
    // }
    // 建立只有資料的表單
    const fd = new FormData(document.form1);

    fetch('course_edit-api.php', {
        method: 'POST',
        body: fd, // 送出的格式會自動是 multipart/form-data
      }).then(r => r.json())
      .then(data => {
        console.log({
          data
        });
        if (data.success) {
          alert('資料編輯成功');
          location.href = "./course_list.php"
        } else {
          alert('資料沒有修改');
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