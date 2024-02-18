<?php
require './parts/connect_db.php';

$pageName = 'add';
$title = '新增產品';

$category_sql = "SELECT * FROM category";
$category_rows = $pdo->query($category_sql)->fetchAll();

$product_status_sql = "SELECT * FROM product_status";
$product_status_rows = $pdo->query($product_status_sql)->fetchAll();

$stock_sql = "SELECT * FROM stock";
$stock_rows = $pdo->query($stock_sql)->fetchAll();

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
      <div class="card"id="card2">
        <div class="card-body">
          <h5 class="card-title">新增產品</h5></br>
          <div class="photo_container"style="cursor: pointer" 
                      onclick="photos.click()">
                      <i class="fa-solid fa-photo-film fa-2xl"></i></div></br>
          <div class="mb-4">可點選上傳多張圖片</div>
          <form name="photo_form" hidden>
            <input
              type="file"
              id="photos"
              name="photos[]"
              multiple
              accept="image/*"
              onchange="uploadFile()"
            />
          </form>
          <div class="row card-container">
          </div>
          <form name="form1" onsubmit="sendData(event)">
              <div class="mb-3">
              <label for="product_name" class="form-label">產品名稱</label>
              <input type="text" class="form-control" id="product_name" name="product_name">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
            <label for="category" class="form-label">產品類型</label>
            <select class="form-select" id="category" name="category">
              <?php foreach ($category_rows as $r) : ?>
              <option value="<?= $r['category_id'] ?>">
              <?= $r['category'] ?>
            </option>
                <?php endforeach ?>
            </select>
            </div>
            <div class="mb-3">
            <label for="spec" class="form-label">產品規格</label>
            <input type="text" class="form-control" id="spec" name="spec"placeholder="請輸入M.L.XL">
            <div class="form-text"></div>
            </div>
            <div class="mb-3">
            <label for="price" class="form-label">價格</label>
            <input type="number" class="form-control" id="price" name="price">
            <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="desc" class="form-label">產品描述</label>
              <input type="text" class="form-control" id="desc" name="desc">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="discount" class="form-label">產品折扣</label>
              <input type="number" class="form-control" id="discount" name="discount">
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
            <div class="photo-container"></div>
            <button type="submit" class="btn btn-primary">提交</button>
            
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</div>

<?php

include './parts/scripts.php';
?>

<script>
  const product_id_in = document.form1.product_id;
  const product_name_in = document.form1.product_name;
  const category_in = document.form1.category;
  const desc_in = document.form1.desc;
  const discount_in = document.form1.discount;
  const product_status_id_in = document.form1.product_status_id;
  const spec_in = document.form1.spec;
  const price_in = document.form1.price;
  
  const fields = [product_id_in, product_name_in, category_in, desc_in, discount_in, product_status_id_in, spec_in, price_in];
  
  const container = document.querySelector(".card-container");
  const photo = document.querySelector(".photo-container");

  function uploadFile() {
    const fd = new FormData(document.photo_form);

    fetch("upload-img-api.php", {
      method: "POST",
      body: fd, // enctype="multipart/form-data"
    })
      .then((r) => r.json())
      .then((data) => {
        console.log({ data });
        if (data.success && data.files.length) {
          let str1 = "";
          let str2 = "";
          let n = 1;
          for (let i of data.files) {
            str1 += `
            <div class="col-4 my-card">
              <img
                src="./uploads/${i}"
                alt=""
                class="w-100"
              />
            </div>
            `;
            str2 += `
            <input type="text" id="photo${n}" name="photo${n}" value="${i}"
              hidden
            >
            `;
            n++;
          }
          // str2 += `<input type="text" id="photo_num" name="photo_num" value="${n}"hidden>`;
          container.innerHTML = str1;
          photo.innerHTML = str2;
        }
      });
  }
  function sendData(e) {
    e.preventDefault(); 
    // fields.forEach(field => {
    //   field.style.border = '1px solid #CCCCCC';
    //   field.nextElementSibling.innerHTML = '';
    // });

    let isPass = true;

  //   if (product_id_input.value.trim() === '') {
  //     isPass = false;
  //     product_id_input.style.border = '2px solid red';
  //     product_id_input.nextElementSibling.innerHTML = '請填寫產品編號';
  //   }

  //   const productIdRegex = /^[A-Z]\d{4}$/;
  //   if (!productIdRegex.test(product_id_input.value.trim())) {
  //   isPass = false;
  //   product_id_input.style.border = '2px solid red';
  //   product_id_input.nextElementSibling.innerHTML = '請填寫正確的產品編號（例如：P1234）';
  // }
  //   if (product_name_input.value.trim() === '') {
  //     isPass = false;
  //     product_name_input.style.border = '2px solid red';
  //     product_name_input.nextElementSibling.innerHTML = '請填寫產品名稱';
  //   }

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

    if (!isPass) {
      return;
    }
    
    const formData = new FormData(document.form1);

    fetch('product-add-api.php', {
      method: 'POST',
      body: formData, 
    })
    .then(response => response.json()) 
    .then(data => {
      console.log(data); 
    if (data.success) {
        alert('資料新增成功');
        document.form1.reset();
        location.href = "./product-list.php"
    }
    })
  }
</script>
<?php
include './parts/html-foot.php';
?>
