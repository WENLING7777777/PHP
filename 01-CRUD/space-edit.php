<?php
require './parts/connect_db.php';
$pageName = 'edit';
$title = '空間編輯';

$city_sql = "SELECT * FROM city";
$city_rows = $pdo->query($city_sql)->fetchAll();

$area_sql = "SELECT * FROM area";
$area_rows = $pdo->query($area_sql)->fetchAll();

$place_sql = "SELECT * FROM place JOIN area ON place.area_id = area.area_id JOIN city ON area.city_id = city.city_id";
$place_rows = $pdo->query($place_sql)->fetchAll();

$category_sql = "SELECT * FROM category";
$category_rows = $pdo->query($category_sql)->fetchAll();

$space_id = isset($_GET['space_id']) ? strval($_GET['space_id']) : 0;

if(!empty($space_id)){
  $sql = "SELECT * FROM ((space JOIN place ON space.place_id = place.place_id) JOIN area ON place.area_id = area.area_id) JOIN city ON area.city_id = city.city_id JOIN category ON space.category_id = category.category_id WHERE space_id = '{$space_id}'";
  $row = $pdo->query($sql)->fetch();
}else{
  header('Location: space-list.php');
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
          <input type="hidden" name="space_id" value="<?= $row['space_id'] ?>">
            <div class="mb-3">
              <label for="name" class="form-label">空間名稱</label>
              <input type="text
              " class="form-control" id="name" name="name" value="<?= htmlentities($row['space']) ?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="city" class="form-label">縣市</label>
              <select class="form-select" name="city" id="city" value="<?= htmlentities($row['city_id']) ?>" onchange="generatePlace()">
                <?php foreach ($city_rows as $r) : ?>
                    <option value="<?= $r['city_id'] ?>" 
                    <?php if($r['city_id'] == $row['city_id']): ?>selected<?php endif; ?>
                    ><?= $r['city'] ?></option>
                <?php endforeach ?>
            </select>
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="place" class="form-label">地點</label>
              <select class="form-select" name="place" id="place" value="<?= htmlentities($row['place_id']) ?>">
                <?php foreach ($place_rows as $r) : ?>
                    <option value="<?= $r['place_id'] ?>" 
                    <?php if($r['place_id'] == $row['place_id']): ?>selected<?php endif; ?>
                    ><?= $r['place'] ?></option>
                <?php endforeach ?>
            </select>
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
              <label for="accommodate" class="form-label">容納人數</label>
              <input type="number
              " class="form-control" id="accommodate" name="accommodate" value="<?= htmlentities($row['accommodate']) ?>">
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
              <label for="status" class="form-label">空間狀態</label>
              <input type="radio" class="form-check-input" id="status" name="status" value="1" <?php if($row['space_status_id'] == '1'): ?>checked<?php endif; ?>>開放中
              <input type="radio" class="form-check-input" id="status" name="status" value="2" <?php if($row['space_status_id'] == '2'): ?>checked<?php endif; ?>>維修中
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

  function sendData(e){
    e.preventDefault();

    let isPass = true;

    if (!isPass) {
      return;
    }

    const fd = new FormData(document.form1);

    fetch('space-edit-api.php',{
      method: 'POST',
      body: fd,
    }).then(r=>r.json()).then(data=>{
      console.log({data});
      if(data.success){
      alert('編輯成功');
      // location.href = "./space-list.php"
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
  city.value = initVals.city;
  generatePlace();

</script>
<?php include './parts/html-foot.php' ?>