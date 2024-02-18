<?php
require './parts/connect_db.php';
$pageName = 'add';
$title = '新增設備';

$city_sql = "SELECT * FROM city";
$city_rows = $pdo->query($city_sql)->fetchAll();

$area_sql = "SELECT * FROM area";
$area_rows = $pdo->query($area_sql)->fetchAll();

$place_sql = "SELECT * FROM place JOIN area ON place.area_id = area.area_id JOIN city ON area.city_id = city.city_id";
$place_rows = $pdo->query($place_sql)->fetchAll();

$category_sql = "SELECT * FROM category";
$category_rows = $pdo->query($category_sql)->fetchAll();

$brand_sql = "SELECT * FROM brand";
$brand_rows = $pdo->query($brand_sql)->fetchAll();

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
        <h5 class="card-title">新增設備</h5>
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
            <label for="name" class="form-label">設備名稱</label>
            <input type="text" class="form-control" id="name" name="name">
            <div class="form-text"></div>
          </div>
          <div class="mb-3">
            <label for="category" class="form-label">類別</label>
            <select class="form-select" name="category" id="category">
            <?php foreach ($category_rows as $r) : ?>
                    <option value="<?= $r['category_id'] ?>"><?= $r['category'] ?></option>
                <?php endforeach ?>
            </select>
            <div class="form-text"></div>
          </div>
          <div class="mb-3">
            <label for="brand" class="form-label">品牌</label>
            <select class="form-select" name="brand" id="brand">
            <?php foreach ($brand_rows as $r) : ?>
                    <option value="<?= $r['brand_id'] ?>"><?= $r['brand'] ?></option>
                <?php endforeach ?>
            </select>
            <div class="form-text"></div>
          </div>
          <div class="mb-3">
            <label for="model" class="form-label">型號</label>
            <input type="text" class="form-control" id="model" name="model">
            <div class="form-text"></div>
          </div>
          <div class="mb-3">
            <label for="intro" class="form-label">設備敘述</label>
            <textarea class="form-control" name="intro" id="intro" cols="30" rows="2"></textarea>
          </div>
          <div class="mb-3">
            <label for="city" class="form-label">縣市</label>
            <select class="form-select" name="city" id="city" onchange="generatePlace()">
            <?php foreach ($city_rows as $r) : ?>
                    <option value="<?= $r['city_id'] ?>"><?= $r['city'] ?></option>
                <?php endforeach ?>
            </select>
            <div class="form-text"></div>
          </div>
          <div class="mb-3">
            <label for="place" class="form-label">地點</label>
            <select class="form-select" name="place" id="place">
            
            </select>
            <div class="form-text"></div>
          </div>
          <div class="mb-3">
            <label for="basic" class="form-label">基本費</label>
            <input type="number" class="form-control" id="basic" name="basic">
            <div class="form-text"></div>
          </div>
          <div class="mb-3">
            <label for="time_rate" class="form-label">時數價格</label>
            <input type="number" class="form-control" id="time_rate" name="time_rate">
            <div class="form-text"></div>
          </div>
          <div class="mb-3">
            <label for="day_rate" class="form-label">日租價格</label>
            <input type="number" class="form-control" id="day_rate" name="day_rate">
          </div>
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

  const place_rows = <?= json_encode($place_rows, JSON_UNESCAPED_UNICODE) ?>;
  
  const city = document.querySelector('#city');
  const areaSelect = document.querySelector('#areaSelect');

  const container = document.querySelector(".card-container");
  const photo = document.querySelector(".photo-container");
  
  const place = document.querySelector('#place');


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
          container.innerHTML = str1;
          photo.innerHTML = str2;
        }
      });
  }
  
  function generatePlace() {
    const cityVal = city.value;
    let str = "";
    for (let item of place_rows) {
      if (+item.city_id == +cityVal) {
        str += `<option value="${item.place_id}">${item.place}</option>`;
      }
    }
    place.innerHTML = str;
  }

  function sendData(e) {
    e.preventDefault();

    let isPass = true;

    if (!isPass) {
      return;
    }

    const fd = new FormData(document.form1);

    fetch('device-add-api.php', {
      method: 'POST',
      body: fd,
    }).then(r => r.json()).then(data => {
      console.log({data});
      if (data.success) {
        alert('新增成功');
        location.href = "./device-list.php"
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

  generatePlace();


</script>
<?php include './parts/html-foot.php' ?>