<?php
require './parts/connect_db.php';
$pageName = 'edit';
$title = '會員編輯';

$city_sql = "SELECT * FROM city";
$city_rows = $pdo->query($city_sql)->fetchAll();

$area_sql = "SELECT * FROM area";
$area_rows = $pdo->query($area_sql)->fetchAll();

$role_sql = "SELECT * FROM `role`";
$role_rows = $pdo->query($role_sql)->fetchAll();

if($_SESSION['role']['role_id']==1){
  $member_id = $_SESSION['role']['member_id'];
}else if($_SESSION['role']['role_id']== 2 or 3){
  $member_id = isset($_GET['member_id']) ? strval($_GET['member_id']) : 0 ;
};


if(!empty($member_id)){
  $sql = "SELECT * FROM (member JOIN area ON member.area_id = area.area_id) JOIN city ON area.city_id = city.city_id WHERE member_id = '{$member_id}'";
  $row = $pdo->query($sql)->fetch();
}else{
  header('Location: member-list.php');
}

?>
<?php include './parts/html-head.php' ?>
<?php include './parts/navbar.php' ?>
<?php 
  if(!empty($_SESSION['role']))
  $_SESSION['role']['role_id']== 2 || $_SESSION['role']['role_id']== 3 ? include './parts/side-navbar.php' : ''
  ?>

    <div class="col-6 mx-auto mt-5">
    <div class="container">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">編輯資料</h5>
          <form name="form1" method="post" onsubmit="sendData(event)" >
          <input type="hidden" name="member_id" value="<?= $row['member_id'] ?>">
            <div class="mb-3">
              <label for="name" class="form-label">姓名</label>
              <input type="text
              " class="form-control" id="name" name="name" value="<?= htmlentities($row['name']) ?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="nickname" class="form-label">暱稱</label>
              <input type="text
              " class="form-control" id="nickname" name="nickname" value="<?= htmlentities($row['nickname']) ?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="birthday" class="form-label">生日</label>
              <input type="date" class="form-control" id="birthday" name="birthday" value="<?= htmlentities($row['birthday']) ?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="gender" class="form-label">性別</label>
              <input type="text
              " class="form-control" id="gender" name="gender" value="<?php if($row['gender']=='M'): ?>男<?php else: ?>女<?php endif; ?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="phone" class="form-label">電話</label>
              <input type="text
              " class="form-control" id="phone" name="phone" value="<?= htmlentities($row['phone']) ?>">
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
              <textarea class="form-control" name="address" id="address" cols="30" rows="2"><?= htmlentities($row['address']) ?></textarea>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">email</label>
              <input type="text
              " class="form-control" id="email" name="email" value="<?= htmlentities($row['email']) ?>">
              <div class="form-text"></div>
            </div>
            <?php if($_SESSION['role']['role_id']==3):?>
            <div class="mb-3">
              <label for="role" class="form-label">權限</label>
              <select class="form-select" name="role" id="role">
                <?php foreach ($role_rows as $r) : ?>
                    <option value="<?= $r['role_id'] ?>" 
                    <?= $r['role_id'] == $row['role_id'] ? 'selected' :''?>
                    ><?= $r['role'] ?></option>
                <?php endforeach ?>
              </select>
              <div class="form-text"></div>
            </div>
            <?php else: ?>
              <input type="text
              " class="form-control" id="role" name="role" value="<?= htmlentities($row['role_id']) ?>" hidden>
            <?php endif; ?>       
            <button type="submit" class="btn btn-primary">修改</button>
          </form>
        </div>
      </div>
  </div>
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

    fetch('member-edit-api.php',{
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

</script>
<?php include './parts/html-foot.php' ?>