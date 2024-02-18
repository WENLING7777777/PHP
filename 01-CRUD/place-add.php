<?php
require './parts/connect_db.php';
$pageName = 'add';
$title = '新增';

$city_sql = "SELECT * FROM city";
$city_rows = $pdo->query($city_sql)->fetchAll();

$area_sql = "SELECT * FROM area";
$area_rows = $pdo->query($area_sql)->fetchAll();

?>

<?php include './parts/html-head.php' ?>
<?php include './parts/navbar.php' ?>
<?php include './parts/side-navbar.php' ?>

<style>
  form .form-text {
    color: red;
  }
  .upload-photo{
    width: 100px;
    height: 100px;
  }
</style>

<div class="col-6 mx-auto mt-5">
  <div class="container">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">新增空間資料</h5>
          <div class="position-relative upload-photo mt-3 mb-3 align-middle bg-secondary-subtle" style="cursor: pointer" onclick="photos.click()">
            <div class="position-absolute top-50 start-50 translate-middle fs-1 text-light">+</div>
          </div>
            <div class="mb-4">可點選上傳多張圖片</div>
          <form name="photoform" hidden>
            <input
              type="file"
              id="photos"
              name="photos[]"
              multiple
              accept="image/*"
              onchange="uploadFile()"
            />
          </form>
          <div class="row card-container">
          </div>
        <form name="form1" method="post" onsubmit="sendData(event)">
          <div class="mb-3">
            <label for="name" class="form-label">地點名稱</label>
            <input type="text" class="form-control" id="name" name="name">
            <div class="form-text"></div>
          </div>
          <div class="mb-3">
            <label for="city" class="form-label">縣市</label>
            <select class="form-select" name="city" id="city" onchange="generateArea()">
            <?php foreach ($city_rows as $r) : ?>
                    <option value="<?= $r['city_id'] ?>"><?= $r['city'] ?></option>
                <?php endforeach ?>
            </select>
            <div class="form-text"></div>
          </div>
          <div class="mb-3">
            <label for="areaSelect" class="form-label">鄉鎮(市)區</label>
              <select class="form-select" name="areaSelect" id="areaSelect">

              </select>
            <div class="form-text"></div>
          </div>
          <div class="mb-3">
            <label for="address" class="form-label">address</label>
            <textarea class="form-control" name="address" id="address" cols="30" rows="2"></textarea>
          </div>
          <label for="start_time" class="form-label">開館天數</label>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="week1">
            <label class="form-check-label" for="flexCheckDefault">日</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" value="2" id="flexCheckDefault" name="week2">
            <label class="form-check-label" for="flexCheckDefault">ㄧ</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" value="3" id="flexCheckDefault" name="week3">
            <label class="form-check-label" for="flexCheckDefault">二</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" value="4" id="flexCheckDefault" name="week4">
            <label class="form-check-label" for="flexCheckDefault">三</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" value="5" id="flexCheckDefault" name="week5">
            <label class="form-check-label" for="flexCheckDefault">四</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" value="6" id="flexCheckDefault" name="week6">
            <label class="form-check-label" for="flexCheckDefault">五</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" value="7" id="flexCheckDefault" name="week7">
            <label class="form-check-label" for="flexCheckDefault">六</label>
          </div>


          <label for="start_time" class="form-label">開館時間</label>

              <select class="form-select" name="start_time" id="start_time"  onchange="generateEnd()">
              </select>
            <label for="end_time" class="form-label">閉館時間</label>

              <select class="form-select" name="end_time" id="end_time">
              </select>
          <div class="photo-container"></div>
          <button type="submit" class="btn btn-primary">新增</button>
        </form>
      </div>
    </div>
  </div>
</div>
</div>

<?php include './parts/scripts.php' ?>
<script>

  const initVals = {city: 1, area: 100};

  const area = <?= json_encode($area_rows, JSON_UNESCAPED_UNICODE) ?>;
  
  const city = document.querySelector('#city');
  const areaSelect = document.querySelector('#areaSelect');

  const container = document.querySelector(".card-container");
  const photo = document.querySelector(".photo-container");

  const start_time = document.querySelector('#start_time');
  const end_time = document.querySelector('#end_time');


  function generateStart() {
    let str1 = "";
    for (let h=0; h<24; h++) {
      let hour = String(h).padStart(2,'0');
        str1 += `<option value="${hour}:00">${hour}:00</option>`;
        str1 += `<option value="${hour}:30">${hour}:30</option>`;
    }
    start_time.innerHTML = str1;
  }

  
  function generateEnd() {
    const startVal = start_time.value;
    const endVal = end_time.value;
    let str2 = "";
    for (let h=0; h<24; h++) {
      let hour = String(h).padStart(2,'0');
      if(hour == startVal.substr(0,2)){
        if(startVal.substr(3,2)==30){
          hour = String(h+1).padStart(2,'0');
          str2 += `<option value="${hour}:00">${hour}:00</option>`;
          h++;
        }
        for (let t=h; t<24; t++){
          let end_hour = String(t).padStart(2,'0');
          str2 += `<option value="${end_hour}:30">${end_hour}:30</option>`;
          end_hour = String(t+1).padStart(2,'0');
          str2 += `<option value="${end_hour}:00"`;
          if(t==23){
            str2 += `selected>${end_hour}:00</option>`;
          }else{
            str2 += `>${end_hour}:00</option>`;
          }
        }
      }
    }
    end_time.innerHTML = str2;
  }

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


  function generateArea() {
    const cityVal = city.value;
    let str = "";
    for (let item of area) {
      if (+item.city_id === +cityVal) {
        str += `<option value="${item.area_id}">${item.area}</option>`;
      }
    }
    areaSelect.innerHTML = str;
  }
  
  function sendData(e) {
    e.preventDefault();

    let isPass = true;

    if (!isPass) {
      return;
    }

    const fd = new FormData(document.form1);

    fetch('place-add-api.php', {
      method: 'POST',
      body: fd,
    }).then(r => r.json()).then(data => {
      console.log({data});
      if (data.success) {
        alert('新增成功');
        history.go(-1);
      } else {
        for (let n in data.error) {
          console.log(`n: ${n}`);
          if (document.form1[n]) {
            const input = document.form1[n];
            input.style.border = '2px solid red';
            input.nextElementSibling.innerHTML = data.error[n];
          }
        }
      };
    }).catch(ex => console.log(ex));
  }

  city.value = initVals.city;
  generateArea(); 
  generateStart();
  generateEnd();

</script>
<?php include './parts/html-foot.php' ?>