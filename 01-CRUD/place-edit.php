<?php
require './parts/connect_db.php';
$pageName = 'edit';
$title = '地點編輯';

$city_sql = "SELECT * FROM city";
$city_rows = $pdo->query($city_sql)->fetchAll();

$area_sql = "SELECT * FROM area";
$area_rows = $pdo->query($area_sql)->fetchAll();

$place_id = isset($_GET['place_id']) ? strval($_GET['place_id']) : 0;

if(!empty($place_id)){
  $sql = "SELECT * FROM (place JOIN area ON place.area_id = area.area_id) JOIN city ON area.city_id = city.city_id JOIN place_status ON place.place_status_id = place_status.place_status_id WHERE place_id = '{$place_id}'";
  $row = $pdo->query($sql)->fetch();
}else{
  header('Location: place-list.php');
}

$perPage = 20;

  $page = isset($_GET['page'])?intval($_GET['page']):1;
  if($page<1){
    header('Loctaion: ?page=1');
    exit;
  }

  $t_sql = "SELECT COUNT(*) FROM space WHERE space.place_id = '{$place_id}'";

  $totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0];

  $totalPages = 0;
  $rows2 = [];

  if($totalRows > 0){
    $totalPages = ceil($totalRows/$perPage);
    if ($page > $totalPages) {
      header('Location: ?page=' . $totalPages);
      exit;
    }

    $space_sql = sprintf("SELECT * FROM ((space JOIN place ON space.place_id = place.place_id) JOIN area ON place.area_id = area.area_id) JOIN city ON area.city_id = city.city_id JOIN category ON space.category_id = category.category_id WHERE space.place_id = '{$place_id}' ORDER BY space_id DESC LIMIT %s, %s", ($page-1)*$perPage, $perPage);
    $space_rows = $pdo->query($space_sql)->fetchAll();
  }

  $device_sql = sprintf("SELECT * FROM device 
    JOIN place ON device.place_id = place.place_id
    JOIN category ON device.category_id = category.category_id 
    JOIN brand ON device.brand_id = brand.brand_id WHERE device.place_id = '{$place_id}'
    ORDER BY device_id DESC LIMIT %s, %s", ($page-1)*$perPage, $perPage);
    $device_rows = $pdo->query($device_sql)->fetchAll();
  

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
          <input type="hidden" name="place_id" value="<?= $row['place_id'] ?>">
            <div class="mb-3">
              <label for="name" class="form-label">地點名稱</label>
              <input type="text
              " class="form-control" id="name" name="name" value="<?= htmlentities($row['place']) ?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="city" class="form-label">縣市</label>
              <select class="form-select" name="city" id="city" value="<?= htmlentities($row['city_id']) ?>" onchange="generateArea()">
                <?php foreach ($city_rows as $r) : ?>
                    <option value="<?= $r['city_id'] ?>" 
                    <?php if($r['city_id'] == $row['city_id']): ?>selected<?php endif; ?>
                    ><?= $r['city'] ?></option>
                <?php endforeach ?>
            </select>
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="areaSelect" class="form-label">鄉鎮(市)區</label>
              <select class="form-select" name="areaSelect" id="areaSelect">
                <?php foreach ($area_rows as $r) : ?>
                    <option value="<?= $r['area_id'] ?>" 
                    <?php if($r['area_id'] == $row['area_id']): ?>selected<?php endif; ?>
                    ><?= $r['area'] ?></option>
                <?php endforeach ?>
              </select>
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="address" class="form-label">address</label>
              <textarea class="form-control" name="address" id="address" cols="30" rows="2"><?= htmlentities($row['place_address']) ?></textarea>
            </div> 
            <button type="submit" class="btn btn-primary">修改</button>
          </form>
        </div>
      </div>
  </div>
</div>
<div class="col-9 mx-auto mt-5">
<!-- <div class="row">
    <div class="col">
    <nav aria-label="Page navigation example">
      <ul class="pagination">
        <li class="page-item <?= $page ==1?'disabled':'' ?>">
          <a class="page-link" href="?page=1">
            <i class="fa-solid fa-angles-left"></i>
          </a>
        </li>
        <?php for ($i = $page-3; $i <= $page+3; $i++) :
        if($i>=1 and $i<=$totalPages): ?>
        <li class="page-item <?= $i==$page? 'active': '' ?>">
          <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
        </li>
        <?php endif; endfor; ?>
        <li class="page-item <?= $page == $totalPages?'disabled':'' ?>">
          <a class="page-link" href="?page=<?= $totalPages ?>">
          <i class="fa-solid fa-angles-right"></i>
          </a>
        </li>
      </ul>
    </nav>
    </div>
  </div>
  <div><?= "$totalRows / $perPage" ?></div> -->
      <h3 class="mt-5">空間</h3>
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th scope="col">
              <i class="fa-solid fa-trash-can"></i>
            </th>
            <th scope="col">#</th>
            <th scope="col">空間名稱</th>
            <th scope="col">分類</th>
            <th scope="col">容納人數</th>
            <th scope="col">時數價格</th>
            <th scope="col">日租價格</th>
            <th scope="col">地點狀態</th>
            <th scope="col">
              <i class="fa-solid fa-file-pen"></i>
            </th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($space_rows as $r):
            if ($r['space_status_id'] != 0):
            ?>
            <tr>
              <td>
                <a href="javascript: deleteItem('<?= $r['space_id'] ?>')">
                  <i class="fa-solid fa-trash-can"></i>
                </a>
              </td>
              <td><?= $r['space_id'] ?></td>
              <td><?= $r['space'] ?></td>
              <td><?= $r['category'] ?></td>
              <td><?= $r['accommodate'] ?></td>
              <td><?= $r['time_rate'] ?></td>
              <td><?= $r['day_rate'] ?></td>
              <td><?= $r['space_status_id'] ?></td>
              <td>
                <a href="space-edit.php?space_id=<?= $r['space_id'] ?>">
                  <i class="fa-solid fa-file-pen"></i>
                </a>
              </td>
            </tr>
          <?php endif;
          endforeach ?>
        </tbody>
      </table>
      <h3 class="mt-5">設備</h3>
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th scope="col">
              <i class="fa-solid fa-trash-can"></i>
            </th>
            <th scope="col">#</th>
            <th scope="col">設備名稱</th>
            <th scope="col">分類</th>
            <th scope="col">品牌</th>
            <th scope="col">型號</th>
            <!-- <th scope="col">設備介紹</th> -->
            <th scope="col">設備位置</th>
            <th scope="col">基本費</th>
            <th scope="col">時數價格</th>
            <th scope="col">日租價格</th>
            <th scope="col">
              <i class="fa-solid fa-file-pen"></i>
            </th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($device_rows as $r):?>
            <tr class="<?= $r['device_status_id'] == 0 ? 'table-danger' :'' ?><?= $r['device_status_id'] == 2 ? 'table-warning' :'' ?>">
              <td>
                <a href="javascript: deleteItem('<?= $r['device_id'] ?>')">
                  <i class="fa-solid fa-trash-can"></i>
                </a>
              </td>
              <td><?= $r['device_id'] ?></td>
              <td><?= $r['device_name'] ?></td>
              <td><?= $r['category'] ?></td>
              <td><?= $r['brand'] ?></td>
              <td><?= $r['model'] ?></td>
              <!-- <td><?= $r['device_intro'] ?></td> -->
              <td><?= $r['place'] ?></td>
              <td><?= $r['basic_fee'] ?></td>
              <td><?= $r['time_rate'] ?></td>
              <td><?= $r['day_rate'] ?></td>
              <td>
                <a href="device-edit.php?device_id=<?= $r['device_id'] ?>">
                  <i class="fa-solid fa-file-pen"></i>
                </a>
              </td>
            </tr>
          <?php endforeach ?>
        </tbody>
      </table>
</div>

<?php include './parts/scripts.php' ?>
<script>

  const area = <?= json_encode($area_rows, JSON_UNESCAPED_UNICODE) ?>;
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

    fetch('place-edit-api.php',{
      method: 'POST',
      body: fd,
    }).then(r=>r.json()).then(data=>{
      console.log({data});
      if(data.success){
      alert('編輯成功');
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
  generateArea(); 
  areaSelect.value = initVals.areaSelect;

  function deleteItem(space_id) {
    if (confirm(`確定要刪除 ${space_id} 嗎?`)) {
      location.href = 'space-delete.php?space_id=' + space_id;
    }
  }
  function deleteItem(device_id) {
    if (confirm(`確定要刪除 ${device_id} 嗎?`)) {
      location.href = 'device-delete.php?device_id=' + device_id;
    }
  }
</script>
<?php include './parts/html-foot.php' ?>