<?php
require './parts/connect_db.php';

$pageName = 'add';
$title = '贊助內頁';



$exhibition_type_sql = "SELECT * FROM exhibition_type";
$exhibition_type_rows = $pdo->query($exhibition_type_sql)->fetchAll();

$exhibition_id = isset($_GET['exhibition_id']) ? $_GET['exhibition_id'] : null;
$r = null;

if ($exhibition_id) {
  $sql = "SELECT * FROM exhibition WHERE exhibition_id = :exhibition_id";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':exhibition_id', $exhibition_id, PDO::PARAM_INT);
  $stmt->execute();
  $r = $stmt->fetch(PDO::FETCH_ASSOC);
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
          <div class="card-body">
            <h5 class="card-title">贊助內容</h5>
            <?php if ($r) : ?>
              <div style="width: 200px; margin:15px;">
                <!-- 這裡加入展覽圖片 -->
                <?php
                $exhibition_id = $r['exhibition_id'];
                $exhibition_photo_sql = "SELECT * FROM exhibition_photo WHERE exhibition_id='{$exhibition_id}'";
                $exhibition_photo_rows = $pdo->query($exhibition_photo_sql)->fetchAll();
                foreach ($exhibition_photo_rows as $photo) :
                ?>
                  <img src="uploads/<?= htmlentities($photo['exhibition_photo']) ?>" alt="展覽圖片" width="260" height="260"><br>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
            <ul class="list-group list-group-flush">
              <li class="list-group-item">
                <strong>展覽編號：</strong> <?php echo $r['exhibition_id'] ?? ''; ?>
              </li>
              <li class="list-group-item">
                <strong>展覽型態：</strong>
                <?php
                if ($r) {
                  foreach ($exhibition_type_rows as $row) {
                    if ($r['exhibition_type_id'] == $row['exhibition_type_id']) {
                      echo $row['exhibition_type'];
                    }
                  }
                }
                ?>
              </li>
              <li class="list-group-item">
                <strong>展覽名稱：</strong> <?php echo $r['exhibition_name'] ?? ''; ?>
              </li>
              <li class="list-group-item">
                <strong>開始時間：</strong> <?php echo $r['start_time'] ?? ''; ?>
              </li>
              <li class="list-group-item">
                <strong>結束時間：</strong> <?php echo $r['end_time'] ?? ''; ?>
              </li>
              <li class="list-group-item">
                <strong>使用空間：</strong> <?php echo $r['space_id'] ?? ''; ?>
              </li>
            </ul>
            <div class="mb-3">
              <label for="exhibition_amount" class="form-label">贊助金額</label>
              <input type="number" class="form-control" id="exhibition_amount" name="exhibition_amount">
              <div class="form-text"></div>
            </div>
            <button type="submit" class="btn btn-primary" onclick="sendData(event)">加入購物車</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function sendData(e) {
  e.preventDefault();
  let isPass = true;
  if (!isPass) {
    return;
  }
  const fd = new FormData(document.form1);
  fetch('exhibition-orderlist-add-api.php', {
    method: 'POST',
    body: fd,
  })
    .then(r => r.json())
    .then(data => {
      console.log(data);
      if (data.success) {
        alert('新增成功');
        // location.href = "exhibition-cart-list.php";
      } else {
        for (let n in data.errors) {
          console.log(`n: ${n}`);
          if (document.form1[n]) {
            const input = document.form1[n];
            input.style.border = '2px solid red';
            input.nextElementSibling.innerHTML = data.errors[n];
          }
        }
      }
    })
    .catch(ex => console.log(ex));
}
</script>

<?php include './parts/scripts.php' ?>
<?php include './parts/html-foot.php' ?>
