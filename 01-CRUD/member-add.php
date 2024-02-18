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
<?php 
  if(!empty($_SESSION['role']))
  $_SESSION['role']['role_id']== 2 or 3 ? include './parts/side-navbar.php' : ''
?>

<style>
  form .form-text {
    color: red;
  }
</style>

<div class="col-6 mx-auto mt-5">
  <div class="container">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">新增資料</h5>
        <form name="form1" method="post" onsubmit="sendData(event)">
          <div class="mb-3">
            <label for="name" class="form-label">姓名</label>
            <input type="text" class="form-control" id="name" name="name">
            <div class="form-text"></div>
          </div>
          <div class="mb-3">
            <label for="nickname" class="form-label">暱稱</label>
            <input type="text" class="form-control" id="nickname" name="nickname">
            <div class="form-text"></div>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">密碼</label>
            <input type="password" class="form-control" id="password" name="password">
            <div class="form-text"></div>
          </div>
          <div class="mb-3">
            <label for="birthday" class="form-label">生日</label>
            <input type="date" class="form-control" id="birthday" name="birthday">
            <div class="form-text"></div>
          </div>
          <div class="mb-3">
            <label for="gender" class="form-label">性別</label></br>
            <input type="radio" class="form-check-input" id="gender" name="gender" value="M">男
            <input type="radio" class="form-check-input" id="gender" name="gender" value="F">女
            <div class="form-text"></div>
          </div>
          <div class="mb-3">
            <label for="phone" class="form-label">電話</label>
            <input type="text" class="form-control" id="phone" name="phone">
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
          <div class="mb-3">
            <label for="email" class="form-label">email</label>
            <input type="text" class="form-control" id="email" name="email">
            <div class="form-text"></div>
          </div>
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

    fetch('member-add-api.php', {
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

</script>
<?php include './parts/html-foot.php' ?>