<?php
require './parts/connect_db.php';

$pageName = 'course_add';
$title = '新增課程';

// 為了place的'當筆'資料(可用fetch判斷)
// $sql = "SELECT * FROM course 
// -- 把area_id跟city_id加進來
// join place on course.place_id = place.place_id
// join area on place.area_id = area.area_id 
// join city on area.city_id = city.city_id
// WHERE course_id='{$course_id}'";

// 課程類別的下拉選單
$cate_sql = "SELECT * FROM category";
$cate_rows = $pdo->query($cate_sql)->fetchAll();

# 縣市的'當筆'資料(可用fetch判斷)
# $city_sql = "SELECT * FROM city WHERE city_id={$city_id}";
# $city_row = $pdo->query($city_sql)->fetch();

//縣市 的下拉選單
// $city_sql = "SELECT * FROM city";
// $city_rows = $pdo->query($city_sql)->fetchAll();

//場地 的下拉選單
$place_sql = "SELECT * FROM place";
$place_rows = $pdo->query($place_sql)->fetchAll();
//join area on place.area_id = area.area_id

// 課程狀態的下拉選單
$status_sql = "SELECT * FROM course_status";
$status_rows = $pdo->query($status_sql)->fetchAll();

// 講師名稱的下拉選單
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
  .upload-photo {
    width: 100px;
    height: 100px;
  }
</style>

<div class="col-9">
  <div class="container">
    <div class="row">
      <div class="col-6">
        <div class="card">

          <div class="card-body">


            <!-- 新增圖片 -->
            <h5 class="card-title">新增課程圖片</h5>
            <div class="position-relative upload-photo mt-3 mb-3 align-middle bg-secondary-subtle" style="cursor: pointer" onclick="photos.click()">
              <div class="position-absolute top-50 start-50 translate-middle fs-1 text-light">+</div>
            </div>
            <div class="mb-4">可點選上傳多張圖片</div>
            <form name="photoform" hidden>
              <input type="file" id="photos" name="photos[]" multiple accept="image/*" onchange="uploadFile()" />
            </form>
            <div class="row card-container">
            </div>


            <h5 class="card-title">新增課程資料</h5>
            <form name="form1" onsubmit="sendData(event)">
              <!-- 課程類別 -->
              <div class="mb-3">
                <label for="category_id" class="form-label">課程類別</label>
                <!-- 課程類別的下拉選單 -->
                <select class="form-select" name="category_id" id="category_id">
                  <?php
                  foreach ($cate_rows as $cate_r) :
                  ?>
                    <option value="<?= $cate_r['category_id'] ?>"><?= $cate_r['category_id'] . $cate_r['category'] ?></option>
                  <?php endforeach ?>
                </select>
                <div class="form-text"></div>
              </div>
              <!-- 課程名稱 -->
              <div class="mb-3">
                <label for="course_name" class="form-label">課程名稱</label>
                <input type="text" class="form-control" id="course_name" name="course_name">
                <div class="form-text"></div>
              </div>
              <!-- 課程日期＆時間 -->
              <div class="mb-3">
                <label for="course_time" class="form-label">課程日期＆時間</label>
                <input type="datetime-local" class="form-control" id="course_time" name="course_time">
                <div class="form-text"></div>
              </div>



              <!-- 課程場地(單層) -->
              <div class="mb-3">
                <label for="place_id" class="form-label">課程場地</label>
                <!-- 課程場地的下拉選單 -->
                <select class="form-select" name="place_id" id="place_id">
                  <?php
                  foreach ($place_rows as $place_r) :
                  ?>
                    <option value="<?= $place_r['place_id'] ?>"><?= $place_r['place_id'] . $place_r['place'] ?></option>
                  <?php endforeach ?>
                </select>
                <div class="form-text"></div>
              </div>




              <!-- 場地start(兩層選單)
              <div class="mb-3">
                課程場地標題
                <label for="place_id" class="form-label">課程場地</label>
                主選單:縣市
                <div class="input-group mb-3">
                  <span class="input-group-text">縣市</span>
                  <select class="form-select" name="city_id" id="city_id" onchange="generatePlace()">
                    <?php foreach ($city_rows as $city_r) : ?>
                      <option value="<?= $city_r['city_id'] ?>"><?= $city_r['city'] ?></option>
                    <?php endforeach ?>
                  </select>
                </div>
                次分類:場地
                <div class="input-group mb-3">
                  <span class="input-group-text">場地</span>
                  <select class="form-select" name="place_id" id="place_id">
                    
                  </select>
                </div>
              </div>
              場地end -->


              <!-- 課程學生人數 -->
              <div class="mb-3">
                <label for="people" class="form-label">人數上限</label>
                <input type="number" class="form-control" id="people" name="people">
                <div class="form-text"></div>
              </div>

              <!-- 課程狀態 -->
              <div class="mb-3">
                <label for="course_status_id" class="form-label">課程狀態</label>
                <select class="form-select" name="course_status_id" id="course_status_id">
                  <?php
                  foreach ($status_rows as $status_r) :
                  ?>
                    <option value="<?= $status_r['course_status_id'] ?>"><?= $status_r['course_status'] ?></option>
                  <?php endforeach ?>
                </select>
                <div class="form-text"></div>
              </div>

              <!-- 課程費用 -->
              <div class="mb-3">
                <label for="course_price" class="form-label">課程費用</label>
                <input type="number" class="form-control" id="course_price" name="course_price">
                <div class="form-text"></div>
              </div>

              <!-- 方案詳情 -->
              <div class="mb-3">
                <label for="course_plan" class="form-label">方案詳情</label>
                <textarea class="form-control" name="course_plan" id="course_plan" cols="30" rows="5"></textarea>
                <div class="form-text"></div>
              </div>

              <!-- 課程介紹 -->
              <div class="mb-3">
                <label for="course_intro" class="form-label">課程介紹</label>
                <textarea class="form-control" name="course_intro" id="course_intro" cols="30" rows="5"></textarea>
                <div class="form-text"></div>
              </div>

              <!-- 講師名稱 -->
              <div class="mb-3">
                <label for="teacher_id" class="form-label">講師名稱</label>
                <!-- 講師名稱的下拉選單 -->
                <select class="form-select" name="teacher_id" id="teacher_id">
                  <?php
                  foreach ($teacher_rows as $teacher_r) :
                  ?>
                    <option value="<?= $teacher_r['teacher_id'] ?>"><?= $teacher_r['teacher_name'] ?></option>
                  <?php endforeach ?>
                </select>
                <div class="form-text"></div>
              </div>

              <!-- 課程注意事項 -->
              <div class="mb-3">
                <label for="notice" class="form-label">課程注意事項</label>
                <textarea class="form-control" name="notice" id="notice" cols="30" rows="5"></textarea>
                <div class="form-text"></div>
              </div>

              <!-- 新增圖片 -->
              <div class="photo-container"></div>
              <button type="submit" class="btn btn-primary">新增</button>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include './parts/scripts.php' ?>
<script>
  // 課程類別的下拉選單
  const initcateVals = {
    category_id: '3'
  };
  const category_id = document.querySelector('#category_id');
  category_id.value = initcateVals.category_id;

  const container = document.querySelector(".card-container");
  const photo = document.querySelector(".photo-container");

  function uploadFile() {
    const fd = new FormData(document.photoform);

    fetch("upload-img-api.php", {
      method: "POST",
      body: fd, // enctype="multipart/form-data"
    })
      .then((r) => r.json())
      .then((data) => {
        console.log({ data });
        if (data.success && data.files.length) {
          let str1 = "";
          let str2 = "";
          let n = 1;
          for (let i of data.files) {
            str1 += `
            <div class="col-4 my-card">
              <img
                src="./uploads/${i}"
                alt=""
                class="w-100"
              />
            </div>
            `;
            str2 += `
            <input type="text" id="photo${n}" name="photo${n}" value="${i}"
              hidden
            >
            `;
            n++;
          }
          // str2 += `<input type="text" id="photo_num" name="photo_num" value="${n}"hidden>`;
          container.innerHTML = str1;
          photo.innerHTML = str2;
        }
      });
  }




  //場地的下拉選單(兩層)
  // const initVals = {city_id: 1,place_id: p0003};
  // const place = <?= json_encode($place_rows, JSON_UNESCAPED_UNICODE) ?>;
  // const city_id = document.querySelector('#city_id');
  // const place_id = document.querySelector('#place_id');

  // function generatePlace() {
  //   const cityVal = city_id.value;
  //   let str = '';
  //   for(let item of place){
  //     if (+item.city_id === +cityVal) {
  //       str += `<option value="${item.place_id}">${item.place}</option>`;
  //     }
  //   }
  //   place_id.innerHTML = str;
  // }

  // generatePlace();

  // console.log(place);
  // console.log(city_id.value);
  // console.log(cityVal);


  // const name_in = document.form1.name;
  // const email_in = document.form1.email;
  // const mobile_in = document.form1.mobile;
  // const fields = [name_in, email_in, mobile_in];

  // function validateEmail(email) {
  //   const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  //   return re.test(email);
  // }

  // function validateMobile(mobile) {
  //   const re = /^09\d{2}-?\d{3}-?\d{3}$/;
  //   return re.test(mobile);
  // }


  function sendData(e) {
    e.preventDefault(); // 不要讓表單以傳統的方式送出

    // 外觀要回復原來的狀態
    // fields.forEach(field => {
    //   field.style.border = '1px solid #CCCCCC';
    //   field.nextElementSibling.innerHTML = '';
    // })

    // TODO: 資料在送出之前, 要檢查格式
    //let isPass = true; // 有沒有通過檢查
    /*
        if (name_in.value.length < 2) {
          isPass = false;
          name_in.style.border = '2px solid red';
          name_in.nextElementSibling.innerHTML = '請填寫正確的姓名';
        }

        if (!validateEmail(email_in.value)) {
          isPass = false;
          email_in.style.border = '2px solid red';
          email_in.nextElementSibling.innerHTML = '請填寫正確的 Email';
        }
    */
    // 驗證：非必填
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

    fetch('course_add-api.php', {
        method: 'POST',
        body: fd, // 送出的格式會自動是 multipart/form-data
      }).then(r => r.json())
      .then(data => {
        console.log({
          data
        });
        if (data.success) {
          alert('資料新增成功');
          location.href = "./course_list.php"
        } else {
          alert('發生問題');
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