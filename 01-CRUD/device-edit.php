<?php
require './parts/connect_db.php';
$pageName = 'edit';
$title = '設備編輯';

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

$status_sql = "SELECT * FROM device_status";
$status_rows = $pdo->query($status_sql)->fetchAll();

$device_id = isset($_GET['device_id']) ? strval($_GET['device_id']) : 0;

if(!empty($device_id)){
  $sql = "SELECT * FROM ((device JOIN place ON device.place_id = place.place_id) JOIN area ON place.area_id = area.area_id) JOIN city ON area.city_id = city.city_id JOIN category ON device.category_id = category.category_id WHERE device_id = '{$device_id}'";
  $row = $pdo->query($sql)->fetch();
}else{
  header('Location: device-list.php');
}

?>
<?php include './parts/html-head.php' ?>
<?php include './parts/navbar.php' ?>
<?php include './parts/side-navbar.php' ?>

    <div class="col-6 mx-auto mt-5">
    <div class="container">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">編輯資料</h5>
          <form name="form1" method="post" onsubmit="sendData(event)" >
          <input type="hidden" name="device_id" value="<?= $row['device_id'] ?>">
            <div class="mb-3">
              <label for="name" class="form-label">設備名稱</label>
              <input type="text
              " class="form-control" id="name" name="name" value="<?= htmlentities($row['device_name']) ?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="category" class="form-label">類別</label>
              <select class="form-select" name="category" id="category" value="<?= htmlentities($row['category_id']) ?>">
                <?php foreach ($category_rows as $r) : ?>
                    <option value="<?= $r['category_id'] ?>" 
                    <?php if($r['category_id'] == $row['category_id']): ?>selected<?php endif; ?>
                    ><?= $r['category'] ?></option>
                <?php endforeach ?>
            </select>
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="brand" class="form-label">品牌</label>
              <select class="form-select" name="brand" id="brand" value="<?= htmlentities($row['brand_id']) ?>">
                <?php foreach ($brand_rows as $r) : ?>
                    <option value="<?= $r['brand_id'] ?>" 
                    <?php if($r['brand_id'] == $row['brand_id']): ?>selected<?php endif; ?>
                    ><?= $r['brand'] ?></option>
                <?php endforeach ?>
            </select>
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="model" class="form-label">型號</label>
              <input type="text" class="form-control" id="model" name="model" value="<?= htmlentities($row['model']) ?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="intro" class="form-label">設備敘述</label>
              <textarea class="form-control" name="intro" id="intro" cols="30" rows="5"><?= htmlentities($row['device_intro']) ?></textarea>
            </div>
            <div class="mb-3">
              <label for="city" class="form-label">縣市</label>
              <select class="form-select" name="city" id="city" value="<?= htmlentities($row['city_id']) ?>" onchange="generatePlace()">
                <?php foreach ($city_rows as $r) : ?>
                    <option value="<?= $r['city_id'] ?>" 
                    <?= ($r['city_id'] == $row['city_id']) ? 'selected' : '' ?>
                    ><?= $r['city'] ?></option>
                <?php endforeach ?>
            </select>
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="place" class="form-label">地點</label>
              <select class="form-select" name="place" id="place" value="<?= htmlentities($row['place_id']) ?>">
                <?php foreach ($place_rows as $r) :
                  if( $r['city_id'] == $row['city_id'] ) : ?>
                    <option value="<?= $r['place_id'] ?>" 
                    <?= ($r['place_id'] == $row['place_id']) ? 'selected' :'' ?>
                    ><?= $r['place'] ?></option>
                <?php endif;
                endforeach ?>
            </select>
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="basic" class="form-label">基本費</label>
              <input type="number
              " class="form-control" id="basic" name="basic" value="<?= htmlentities($row['basic_fee']) ?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="time_rate" class="form-label">時數價格</label>
              <input type="number
              " class="form-control" id="time_rate" name="time_rate" value="<?= htmlentities($row['time_rate']) ?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="day_rate" class="form-label">日租價格</label>
              <input type="number
              " class="form-control" id="day_rate" name="day_rate" value="<?= htmlentities($row['day_rate']) ?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="status" class="form-label">設備狀態</label>
              <select class="form-select" name="status" id="status" value="<?= htmlentities($row['device_status_id']) ?>">
                <?php foreach ($status_rows as $r) : 
                  if($r['device_status_id']!=0):?>
                    <option value="<?= $r['device_status_id'] ?>" 
                    <?php if($r['device_status_id'] == $row['device_status_id']): ?>selected<?php endif; ?>
                    ><?= $r['device_status'] ?></option>
                <?php 
                endif;
                endforeach ?>
            </select>
              <div class="form-text"></div>
            </div>
            <button type="submit" class="btn btn-primary">修改</button>
          </form>
        </div>
      </div>
  </div>
</div>

<?php include './parts/scripts.php' ?>
<script>

  const place_rows = <?= json_encode($place_rows, JSON_UNESCAPED_UNICODE) ?>;
  const city = document.querySelector('#city');
  const areaSelect = document.querySelector('#areaSelect');
  const initVals = {city: city.value, areaSelect: areaSelect.value};

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

  function sendData(e){
    e.preventDefault();

    let isPass = true;

    if (!isPass) {
      return;
    }

    const fd = new FormData(document.form1);

    fetch('device-edit-api.php',{
      method: 'POST',
      body: fd,
    }).then(r=>r.json()).then(data=>{
      console.log({data});
      if(data.success){
      alert('編輯成功');
      // location.href = "./device-list.php"
      history.go(-1);
      }else{
        alert('資料沒有修改');
        for(let n in data.error){
          console.log(`n: ${n}`);
          if(document.form1[n]){
            const input =document.form1[n];
            input.style.border = '2px solid red';
            input.nextElementSibling.innerHTML = data.error[n];
          }
        }
      };
    }).catch(ex=>console.log(ex));
  }

  city.value = initVals.city;

</script>
<?php include './parts/html-foot.php' ?>