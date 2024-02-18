<?php
require './parts/connect_db.php';
$product_id = isset($_GET['product_id']) ? strval($_GET['product_id']) : 0;

// if (empty($product_id)) {
//   header('Location: product-list.php');
//   exit;
// }

$category_sql = "SELECT * FROM category";
$category_rows = $pdo->query($category_sql)->fetchAll();

$product_status_sql = "SELECT * FROM product_status";
$product_status_rows = $pdo->query($product_status_sql)->fetchAll();

$sql = "SELECT * FROM product WHERE product_id='{$product_id}'";

$stock_sql = "SELECT * FROM stock WHERE product_id='{$product_id}'";
$stock_rows = $pdo->query($stock_sql)->fetchAll();

$product_photo_sql = "SELECT * FROM product_photo WHERE product_id='{$product_id}'";
$product_photo_rows = $pdo->query($product_photo_sql)->fetchAll();


$row = $pdo->query($sql)->fetch();
if (empty($row)) {
  header('Location: product-list.php');
  exit; 
}

$title = '編輯資料';

?>
<?php include './parts/html-head.php'?>
<?php include './parts/navbar.php'?>
<style>
  form .form-text {
    color: red;
  }
</style>
<div class="col mx-auto">
<form name="form_info" method="post" action="product-info-api.php" onsubmit="sendData(event)">
<div class="container">
  <div class="row d-flex justify-content-center">
    <div class="col-6">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title"></h5>
          <li class="list-group-item">
          <?php foreach ($product_photo_rows as $r) : ?>
            <div style="width: 200px; margin: 0 auto;">
            <img src="uploads/<?= htmlentities($r['product_photo']) ?>" alt="產品圖片" width="200" height="200"><br>
            </div></br>
            <?php endforeach; ?>
            </li>
          <ul class="list-group list-group-flush">
            <li class="list-group-item">
              <strong>產品名稱：</strong> <?= htmlentities($row['product_name']) ?>
            </li>
            <li class="list-group-item">
              <strong>產品類型：</strong>
              <?php foreach ($category_rows as $r) {
                if($r['category_id'] == $row['category_id']) {
                  echo $r['category'];
                }
              } ?>
            </li>
            <li class="list-group-item">
              <?php foreach ($stock_rows as $r) : ?>
                <?php if (!empty($r['spec'])) : ?>
                  <strong>規格：</strong>
                  <!-- <button type="button" class="btn btn-secondary"><?= $r['spec'] ?></button> -->
                <?= $r['spec'] ?><br>
                <?php else : ?>
                  <strong>規格：</strong> one size
                <?php endif; ?>
              <?php endforeach; ?>
            </li>
            <li class="list-group-item">
            <?php foreach ($stock_rows as $r) : ?>
            <strong>價格：</strong> <?= $r['price'] ?><br>
              <?php endforeach; ?>
            </li>
            <li class="list-group-item">
              <strong>產品描述：</strong> <?= htmlentities($row['desc']) ?>
            </li>

            <li class="list-group-item">
              <strong>產品折扣：</strong> <?= htmlentities($row['discount']) ?>
            </li>
            <!-- <li class="list-group-item">
              <strong>產品評價：</strong>
            </li> -->
            <button type="submit" class="btn btn-primary">加到購物車</button>
          </ul>
        </div>
      </div>
    </div>
  </div>
  </form>
</div>

<?php include './parts/scripts.php' ?>
<script>


   function sendData(e) {
    e.preventDefault();

    const fd = new FormData(document.forms.form_info);

    fetch('product-info-api.php', {
      method: 'POST',
      body: fd, 
    }).then(r => r.json())
    .then(data => {
      console.log(data);
      if (data.success) {
        alert('已加入購物車');
        location.href = "./product-list.php";
      } else {
        alert('已加入購物車');
        for(let n in data.errors){
          console.log(`n: ${n}`);
          if(document.form_info[n]){
            const input = document.form_info[n];
            input.style.border = '2px solid red';
            input.nextElementSibling.innerHTML = data.errors[n];
          }
        }
      }
    })
    .catch(ex => console.error(ex));
  }
</script>
<?php include './parts/html-foot.php' ?>