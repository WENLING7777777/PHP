<?php
require './parts/connect_db.php';
$pageName = 'edit';
$title = '設備';

$device_id = isset($_GET['device_id']) ? strval($_GET['device_id']) : 0;

if (!empty($device_id)) {
  $sql = "SELECT * FROM (((device
  JOIN category ON device.category_id = category.category_id)
  JOIN brand ON device.brand_id = brand.brand_id)
  JOIN device_status ON device.device_status_id = device_status.device_status_id)
  WHERE device.device_id = '{$device_id}'";
  $row = $pdo->query($sql)->fetch();
} else {
  header('Location: space-list.php');
}

$photo_sql = "SELECT * FROM device_photo WHERE device_id = '{$device_id}'";
$photo_rows = $pdo->query($photo_sql)->fetchAll();

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
              <?php foreach ($photo_rows as $r) : ?>
                <img src="./uploads/<?= $r['device_photo'] ?>" class="d-block w-100" alt="...">
              <?php endforeach; ?>
        <?php endif; ?>
        <h4 class="card-title mt-4">【<?= htmlentities($row['brand']) ?>】<?= htmlentities($row['model']) ?><?= htmlentities($row['device_name']) ?></h4>
        <form name="form1" method="post" onsubmit="sendData(event)">
          <input type="hidden" name="device_id" value="<?= $row['device_id'] ?>">
          <div class="mb-3">
            <span>類別: </span>
            <span><?= htmlentities($row['category']) ?></span>
          </div>
          <div class="mb-3">
            <span>介紹: </span>
            <span><?= htmlentities($row['device_intro']) ?></span>
          </div>
          <div class="mb-3">
            <span>價格: </span>
            </br><span>基本費: $<?= htmlentities($row['basic_fee']) ?>元</span>

            </br><span>$<?= htmlentities($row['time_rate']) ?>元 / 小時</span>
            </br><span>日租 $<?= htmlentities($row['day_rate']) ?>元 </span>
          </div>
          <div id="total"></div>
          <button type="submit" class="btn btn-primary mt-3">確定租借</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include './parts/scripts.php' ?>
<script>
  const row = <?= json_encode($row, JSON_UNESCAPED_UNICODE) ?>;
  const device_photo = <?= json_encode($photo_rows, JSON_UNESCAPED_UNICODE) ?>;
  const total = document.querySelector('#total');



  function sendData(e) {
    e.preventDefault();

    let isPass = true;

    if (!isPass) {
      return;
    }

    const fd = new FormData(document.form1);

    fetch('device-cart-api.php', {
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
</script>
<?php include './parts/html-foot.php' ?>