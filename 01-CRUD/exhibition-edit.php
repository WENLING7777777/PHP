<?php

require './parts/connect_db.php';
$pageName ='edit';
$title = '展覽編輯';

$exhibition_id = isset($_GET['exhibition_id']) ? strval($_GET['exhibition_id']) : 0;

if(!empty($exhibition_id)){
  $sql = "SELECT * FROM exhibition JOIN exhibition_type ON exhibition.exhibition_type_id = exhibition_type.exhibition_type_id WHERE exhibition_id = '{$exhibition_id}'";
  $row = $pdo->query($sql)->fetch();
  // 整理開始時間與結束時間 ＝> 變成 Array ( [0] => 2023-10-01 [1] => 00:00:00 ) 格式
  $row['start_time'] = str_split($row['start_time'],10);
  $row['end_time'] = str_split($row['end_time'],10);
  // print_r($row);
}else{
  header('Location: exhibition-list.php');
}


if (empty($exhibition_id)) {
  header('Location: exhibition-list.php');
  exit;
}

$exhibition_type_sql = "SELECT * FROM exhibition_type ";
$exhibition_type_rows = $pdo->query($exhibition_type_sql)->fetchAll();

$space_sql = "SELECT * FROM space ";
$space_rows = $pdo->query($space_sql)->fetchAll();

if (empty($row)) {
  header('Location: exhibition-list.php');
  exit; 
}


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

          <div style="width: 200px; margin:15px;">
                            <!-- 這裡加入展覽圖片 -->
                            <?php
                            $exhibition_id = $row['exhibition_id'];
                            $exhibition_photo_sql = "SELECT * FROM exhibition_photo WHERE exhibition_id='{$exhibition_id}'";
                            $exhibition_photo_rows = $pdo->query($exhibition_photo_sql)->fetchAll();
                            foreach ($exhibition_photo_rows as $photo) :
                            ?>
                                <img src="uploads/<?= htmlentities($photo['exhibition_photo']) ?>" alt="展覽圖片" width="260" height="260"><br>
                            <?php endforeach; ?>
                        </div>

        <div class="card-body">
          <h5 class="card-title">展覽編輯</h5>
          <form name="form1" onsubmit="sendData(event)">
            <input type="hidden" name="exhibition_id" value="<?= $row['exhibition_id'] ?>">
            <div class="mb-3">
              <label for="exhibition_type_id" class="form-label">展覽型態</label>
              <div>
              <select class="form-select" name="exhibition_type_id" id="exhibition_type_id">
              <?php foreach ($exhibition_type_rows as $r) : ?>
              <option value="<?= $r['exhibition_type_id'] ?>" 
              <?= ($r['exhibition_type_id'] == $row['exhibition_type_id']) ? 'selected' : '' ?>>
              <?= $r['exhibition_type'] ?>
              </option>
              <?php endforeach ?>
              </select>
                <div class="form-text"></div>
              </div>
            </div>
            <div class="mb-3">
              <label for="exhibition_name" class="form-label">展覽名稱</label>
              <input type="text" class="form-control" id="exhibition_name" name="exhibition_name" 
              value="<?= htmlentities($row['exhibition_name']) ?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="exhibition_people" class="form-label">參展人數</label>
              <input type="number" class="form-control" id="exhibition_people" name="exhibition_people" 
              value="<?= $row['exhibition_people'] ?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="start_time" class="form-label">開始時間</label>
              <input type="date" class="form-control" id="start_time" name="start_time"
              value="<?= $row['start_time'][0] ?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="end_time" class="form-label">結束時間</label>
              <input type="date" class="form-control" id="end_time" name="end_time"
              value="<?= $row['end_time'][0] ?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="space_id" class="form-label">租借空間</label>
              <div>
              <select class="form-select" name="space_id" id="space_id">
              <?php foreach ($space_rows as $r) : ?>
              <option value="<?= $r['space_id'] ?>" 
              <?= ($r['space_id'] == $row['space_id']) ? 'selected' : '' ?>>
              <?= $r['space'] ?>
              </option>
              <?php endforeach ?>
              </select>
                <div class="form-text"></div>
              </div>
            </div>
            <div class="mb-3">
              <label for="exhibition_desc" class="form-label">展覽內容</label>
              <textarea class="form-control" name="exhibition_desc" id="exhibition_desc" cols="30" 
              rows="3"><?= htmlentities($row['exhibition_desc']) ?></textarea>
              <div class="form-text"></div>
            </div>
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
  const exhibition_type = <?= json_encode($exhibition_type_rows, JSON_UNESCAPED_UNICODE) ?>;
  const exhibition_type_id = document.querySelector('#exhibition_type_id');
  const initVals = {exhibition_type: exhibition_type.value, exhibition_type_id: exhibition_type_id.value};
  const exhibition_name_in = document.form1.exhibition_name;
  const exhibition_people_in = document.form1.exhibition_people;
  const start_time_in = document.form1.start_time;
  const end_time_in = document.form1.end_time;
  const fields = [ exhibition_name, exhibition_people, start_time, end_time ];

  
  

  function sendData(e) {
  e.preventDefault();

  let isPass = true;

  if (!isPass) {
    return; 
  }
  
  const fd = new FormData(document.form1);

  fetch('exhibition-edit-api.php', {
    method: 'POST',
    body: fd,
  }).then(r => r.json())
    .then(data => {
      console.log({
        data
      });
      if (data.success) {
        alert('資料編輯成功');
        location.href = "exhibition-list.php"
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
    .catch(ex => console.log(ex));
}

exhibition_type.value = initVals.exhibition_type;
exhibition_type_id.value = initVals.exhibition_type_id;



</script>
<?php include './parts/html-foot.php' ?>