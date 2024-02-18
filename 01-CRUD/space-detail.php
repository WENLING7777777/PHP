<?php
require './parts/connect_db.php';
$pageName = 'edit';
$title = '空間';

$space_id = isset($_GET['space_id']) ? strval($_GET['space_id']) : 0;

if (!empty($space_id)) {
  $sql = "SELECT * FROM ((space JOIN place ON space.place_id = place.place_id) JOIN area ON place.area_id = area.area_id) JOIN city ON area.city_id = city.city_id JOIN category ON space.category_id = category.category_id WHERE space_id = '{$space_id}'";
  $row = $pdo->query($sql)->fetch();
} else {
  header('Location: space-list.php');
}

$photo_sql = "SELECT * FROM space_photo WHERE space_id = '{$space_id}'";
$photo_rows = $pdo->query($photo_sql)->fetchAll();

$place_id = $row['place_id'];

$photo1_sql = "SELECT * FROM place_photo WHERE place_id = '{$place_id}'";
$photo1_rows = $pdo->query($photo1_sql)->fetchAll();

$device_sql = "SELECT * FROM device JOIN brand ON device.brand_id = brand.brand_id WHERE place_id = '{$place_id}'";
$device_rows = $pdo->query($device_sql)->fetchAll();

$device_photo_sql = "SELECT * FROM device_photo";
$device_photo_rows = $pdo->query($device_photo_sql)->fetchAll();

?>
<?php include './parts/html-head.php' ?>
<?php include './parts/navbar.php' ?>
<div class="col-6 mx-auto mt-5">
  <div class="container">
    <div class="card">
      <div class="card-body">
        <?php if ($photo_rows == []) : ?>
          <img src="./uploads/default_photo.jpg" class="d-block w-100" alt="...">
        <?php else : ?>
          <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">

              <?php $n = 1;
              foreach ($photo_rows as $k => $r) : ?>
                <div class="carousel-item <?= $k == 0 ? 'active' : '' ?>">
                  <img src="./uploads/<?= $r['space_photo'] ?>" class="d-block w-100" alt="...">
                </div>
              <?php endforeach; ?>
              <?php foreach ($photo1_rows as $r) : ?>
                <div class="carousel-item">
                  <img src="./uploads/<?= $r['place_photo'] ?>" class="d-block w-100" alt="...">
                </div>
              <?php endforeach; ?>

            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Next</span>
            </button>
          </div>
        <?php endif; ?>
        <h4 class="card-title mt-4"><?= htmlentities($row['space']) ?></h4>
        <p><?= htmlentities($row['city']) ?><?= htmlentities($row['area']) ?><?= htmlentities($row['place_address']) ?></p>
        <form name="form1" method="post" onsubmit="sendData(event)">
          <input type="hidden" name="space_id" value="<?= $row['space_id'] ?>">
          <div class="mb-3">
            <span>空間類別: </span>
            <span><?= htmlentities($row['category']) ?></span>
          </div>
          <div class="mb-3">
            <span>建議人數: </span>
            <span><?= htmlentities($row['accommodate']) ?>人</span>
          </div>
          <div class="mb-3">
            <span>價格: </span>
            </br><span>$<?= htmlentities($row['time_rate']) ?>元 / 小時</span>
            </br><span>$<?= htmlentities($row['day_rate']) ?>元 / 天</span>
          </div>
          <input type="date" class="form-control mb-3" id="date" name="date" value="<?= date("Y-m-d", time()) ?>" onchange="generateStart()">
          <label for="start_time" class="form-label">開始時間</label>

          <select class="form-select mb-3" name="start_time" id="start_time" onchange="generateEnd()">

          </select>
          <label for="end_time" class="form-label">結束時間</label>

          <select class="form-select mb-3" name="end_time" id="end_time" onchange="generateDuring()">
          </select>
          <div id="total"></div>
          <div class="mb-3">
          <label for="deviceSelect" class="form-label">設備</label>
          <select class="form-select" name="deviceSelect" id="deviceSelect"  >
            <?php foreach ($device_rows as $r) : ?>
                <option value="<?= $r['device_id'] ?>" >【<?= $r['device_name'] ?>】 <?= $r['brand'] ?> <?= $r['model'] ?></option>
            <?php endforeach ?>
        </select>
        <input class="my-3" type="button" value="增加" onclick="addDevice()">
        </div>
        <div class="row" id="showDevice"></div>
          <button type="submit" class="btn btn-primary mt-3">確定預約</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include './parts/scripts.php' ?>
<script>
  const row = <?= json_encode($row, JSON_UNESCAPED_UNICODE) ?>;
  const device = <?= json_encode($device_rows, JSON_UNESCAPED_UNICODE) ?>;
  const device_photo = <?= json_encode($device_photo_rows, JSON_UNESCAPED_UNICODE) ?>;
  const date = document.querySelector('#date');
  const start_time = document.querySelector('#start_time');
  const end_time = document.querySelector('#end_time');
  const total = document.querySelector('#total');
  const deviceSelect = document.querySelector('#deviceSelect');
  const showDevice = document.querySelector('#showDevice');


  let during = "";

  console.log(date.value);

  function generateStart() {
    let str1 = "";
    for (let h = 8; h < 23; h++) {
      let hour = String(h).padStart(2, '0');
      str1 += `<option value="${hour}:00">${hour}:00</option>`;
      str1 += `<option value="${hour}:30">${hour}:30</option>`;
    }
    start_time.innerHTML = str1;
    generateEnd();
  }


  function generateEnd() {
    const startVal = start_time.value;
    let str2 = "";
    for (let h = 8; h < 23; h++) {
      let hour = String(h).padStart(2, '0');
      if (hour == startVal.substr(0, 2)) {
        if (startVal.substr(3, 2) == 30) {
          hour = String(h + 1).padStart(2, '0');
          str2 += `<option value="${hour}:00">${hour}:00</option>`;
          h++;
        }
        for (let t = h; t < 23; t++) {
          let end_hour = String(t).padStart(2, '0');
          str2 += `<option value="${end_hour}:30">${end_hour}:30</option>`;
          end_hour = String(t + 1).padStart(2, '0');
          str2 += `<option value="${end_hour}:00">${end_hour}:00</option>`;
        }
      }
    }
    end_time.innerHTML = str2;
    generateDuring();
  }

  function generateDuring() {
    const startVal = start_time.value;
    const endVal = end_time.value;
    // console.log(startVal);
    // console.log(endVal);

    let str3 = "";
    hours = String(endVal.substr(0, 2)) - String(startVal.substr(0, 2));
    if (endVal.substr(3, 2) == 30) {
      if (startVal.substr(3, 2) == 00) {
        str3 += `估算時數:` + (hours * 2 + 1) / 2 + `小時 $` + row.time_rate * (hours * 2 + 1) / 2;
        during = hours * 2 + 1;
      } else {
        str3 += `估算時數:` + hours + `小時 $` + row.time_rate * hours;
        during = hours * 2;
      }
    } else {
      if (startVal.substr(3, 2) != 00) {
        str3 += `估算時數:` + (hours * 2 - 1) / 2 + `小時 $` + row.time_rate * (hours * 2 - 1) / 2;
        during = hours * 2 - 1;
      } else {
        str3 += `估算時數:` + hours + `小時 $` + row.time_rate * hours;
        during = hours * 2;
      }
    }

    total.innerHTML = str3;
  }

  let device_num = 1;
  let str = "";
  function addDevice(){
    const deviceVal = deviceSelect.value;

    for (let item of device) {
      if (item.device_id == deviceVal) {
        str += `
        <div class="col-6">
        <div class="card mb-3 mx-auto" style="width: 18rem;">
          <img src="./uploads/`
        for (let photo of device_photo) {
          if(photo.device_id == deviceVal)
            str += `${photo.device_photo}`
        };

        str +=  `" class="card-img-top" alt="...">
        <div class="card-body">
          <h5 class="card-title">${item.device_name}</h5>
          <p class="card-text">${item.brand} ${item.model}</p>
        </div>
        <ul class="list-group list-group-flush">
          <li class="list-group-item">基本費$${item.basic_fee}</li>
          <li class="list-group-item">每小時$${item.time_rate}</li>
        </ul>
        <div class="card-body">
          
          <a href="device-detail.php?device_id=${item.device_id}" class="card-link" target="_blank">詳細介紹</a>
        </div>
        </div>
        </div>`;
      }
    }
    device_num++;
    showDevice.innerHTML = str;
  }


  function sendData(e) {
    e.preventDefault();

    let isPass = true;

    if (!isPass) {
      return;
    }

    const fd = new FormData(document.form1);
    fd.append("time", during);

    fetch('space-cart-api.php', {
      method: 'POST',
      body: fd,
    }).then(r => r.json()).then(data => {
      console.log({
        data
      });
      if (data.success) {
        alert('加入成功');
        location.href = "./space-result.php"
      } else {
        alert('資料沒有修改');
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
  generateStart();
  generateEnd();
  generateDuring();
</script>
<?php include './parts/html-foot.php' ?>