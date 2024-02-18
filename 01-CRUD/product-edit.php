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

$stock_sql = "SELECT * FROM stock WHERE product_id='{$product_id}'";
$stock_row = $pdo->query($stock_sql)->fetch();

$product_photo_sql = "SELECT * FROM product_photo WHERE product_id='{$product_id}'";
$product_photo_rows = $pdo->query($product_photo_sql)->fetchAll();

$sql = "SELECT * FROM product WHERE product_id='{$product_id}'";
$row = $pdo->query($sql)->fetch();
if (empty($row)) {
  header('Location: product-list.php');
  exit; 
}

$title = '編輯資料';

?>
<?php include './parts/html-head.php'?>
<?php include './parts/navbar.php'?>
<?php include './parts/side-navbar.php' ?>
<style>
  form .form-text {
    color: red;
  }
</style>
<div class="col-9">
<div class="container">
  <div class="row d-flex justify-content-center">
    <div class="col-6">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">編輯資料</h5>
          <li class="list-group-item">
          <?php foreach ($product_photo_rows as $r) : ?>
            <div style="width: 200px; margin: 0 auto;">
            <img src="uploads/<?= htmlentities($r['product_photo']) ?>" alt="產品圖片" width="200" height="200"><br>
            </div></br>
            <?php endforeach; ?>
            </li>
          <form name="form1" onsubmit="sendData(event)">
            <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">
            <div class="mb-3">
              <label for="product_name" class="form-label">產品名稱</label>
              <input type="text" class="form-control" id="product_name" name="product_name"
              value="<?= htmlentities($row['product_name']) ?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
            <label for="category" class="form-label">產品類型</label>
            <select class="form-select" id="category" name="category">
              <?php foreach ($category_rows as $r) : ?>
              <option value="<?= $r['category_id'] ?>"
              <?php if($r['category_id'] == $row['category_id']): ?>selected<?php endif; ?>>
              <?= $r['category'] ?>
            </option>
                <?php endforeach ?>
            </select>
            </div>
            <div class="mb-3">
            <label for="spec" class="form-label">產品規格</label>
            <input type="text" class="form-control" id="spec" name="spec"
            value="<?= htmlentities($stock_row['spec'])?>">
            <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="price" class="form-label">價格</label>
              <input type="number" class="form-control" id="price" name="price" value="<?= htmlentities($stock_row['price']) ?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="desc" class="form-label">產品描述</label>
              <input type="text" class="form-control" id="desc" name="desc"
              value="<?= htmlentities($row['desc'])?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="discount" class="form-label">產品折扣</label>
              <input type="text" class="form-control" id="discount" name="discount"
              value="<?= htmlentities($row['discount']) ?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
            <label for="product_status" class="form-label">產品狀態</label>
            <select class="form-select" id="product_status" name="product_status">
              <?php foreach ($product_status_rows as $r) : ?>
              <option value="<?= $r['product_status_id'] ?>">
              <?= $r['product_status'] ?>
            </option>
                <?php endforeach ?>
            </select>
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
  const product_id_in = document.form1.product_id;
  const product_name_in = document.form1.product_name;
  const category_in = document.form1.category;
  const spec_in = document.form1.spec;
  const price_in = document.form1.price;
  const desc_in = document.form1.desc;
  const discount_in = document.form1.discount;
  const product_status_id_in = document.form1.product_status_id;


  function sendData(e) {
    e.preventDefault();

    const fields = [
        product_id_in,
        product_name_in,
        category_in,
        spec_in,
        price_in,
        desc_in,
        discount_in,
        product_status_id_in
    ];

//     fields.forEach(field => {
//     if (field.nextElementSibling) {
//     field.style.border = '1px solid #CCCCCC';
//     field.nextElementSibling.innerHTML = '';
//   }
// });

    let isPass = true;

   // // 非必填，檢查並驗證產品類別格式
    // if (category_input.value.trim() && !validateEmail(category_input.value)) {
    //   isPass = false;
    //   category_input.style.border = '2px solid red';
    //   category_input.nextElementSibling.innerHTML = '請填寫正確的產品類別';
    // }

    // // 非必填，檢查並驗證產品描述格式
    // if (desc_input.value.trim() && !validateEmail(desc_input.value)) {
    //   isPass = false;
    //   desc_input.style.border = '2px solid red';
    //   desc_input.nextElementSibling.innerHTML = '請填寫正確的產品描述';
    // }

    // // 非必填，檢查並驗證產品折扣格式
    // if (discount_input.value.trim() && isNaN(discount_input.value.trim())) {
    //   isPass = false;
    //   discount_input.style.border = '2px solid red';
    //   discount_input.nextElementSibling.innerHTML = '請填寫正確的產品折扣';
    // }

    // // 非必填，檢查並驗證產品狀態格式
    // if (product_status_id_input.value.trim() && isNaN(product_status_id_input.value.trim())) {
    //   isPass = false;
    //   product_status_id_input.style.border = '2px solid red';
    //   product_status_id_input.nextElementSibling.innerHTML = '請填寫正確的產品狀態';
    // }
    const fd = new FormData(document.form1);

    fetch('product-edit-api.php', {
        method: 'POST',
        body: fd, 
      }).then(r => r.json())
      .then(data => {
        console.log({
          data
        });
        if (data.success) {
          alert('資料編輯成功');
          location.href = "./product-list.php"
        } else {
          alert('資料已修改');
          location.href = "./product-list.php"
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
      .catch(ex => console.log(ex))
  }
</script>
<?php include './parts/html-foot.php' ?>