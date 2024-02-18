<?php
require './parts/connect_db.php';

$pageName = 'add';
$title = '展覽新增';

$exhibition_type_sql = "SELECT * FROM exhibition_type";
$exhibition_type_rows = $pdo->query($exhibition_type_sql)->fetchAll();

$space_sql = "SELECT * FROM space";
$space_rows = $pdo->query($space_sql)->fetchAll();

?>
<?php include './parts/html-head.php' ?>
<?php include './parts/navbar.php' ?>
<?php include './parts/side-navbar.php' ?>

<style>
  form .form-text {
    color: red;
  }
  .upload-photo{
    width: 100px;
    height: 100px;
  }
</style>

<div class="col-6 mx-auto mt-5">
<div class="container">
  <div class="card">
    <div class="card-body">
          <h5 class="card-title">新增展覽</h5>
          <div class="position-relative upload-photo mt-3 mb-3 align-middle bg-secondary-subtle" style="cursor: pointer" onclick="photos.click()">
          <div class="position-absolute top-50 start-50 translate-middle fs-1 text-light">+</div>
        </div>
        <form name="photoform" hidden>
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
          <form name="form1" method="post" onsubmit="sendData(event)">
            <div class="mb-3">
              <label for="exhibition_type_id" class="form-label">展覽型態</label>
              <div>
                <select class="form-select" name="exhibition_type_id" id="exhibition_type_id">
                  <?php foreach ($exhibition_type_rows as $row) : ?>
              <option value="<?= $row['exhibition_type_id'] ?>"><?= $row['exhibition_type'] ?></option>
                <?php endforeach ?>
                </select>
                <div class="form-text"></div>
              </div>
            </div>
            <div class="mb-3">
              <label for="exhibition_name" class="form-label">展覽名稱</label>
              <input type="text" class="form-control" id="exhibition_name" name="exhibition_name">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="exhibition_people" class="form-label">參展人數</label>
              <input type="number" class="form-control" id="exhibition_people" name="exhibition_people">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="start_time" class="form-label">開始時間</label>
              <input type="date" class="form-control" id="start_time" name="start_time">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for "end_time" class="form-label">結束時間</label>
              <input type="date" class="form-control" id="end_time" name="end_time">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="space_id" class="form-label">租借空間</label>
              <div>
                <select class="form-select" name="space_id" id="space_id">
                  <?php foreach ($space_rows as $row) : ?>
              <option value="<?= $row['space_id'] ?>"><?= $row['space'] ?></option>
                <?php endforeach ?>
                </select>
                <div class="form-text"></div>
              </div>
            </div>
            <div class="mb-3">
              <label for="exhibition_desc" class="form-label">展覽內容</label>
              <textarea class="form-control" name="exhibition_desc" id="exhibition_desc" cols="30" rows="3"></textarea>
              <div class="form-text"></div>
            </div>
            <div class="photo-container"></div>
          <button type="submit" class="btn btn-primary">新增</button>
          </form>
        </div>
      </div>
    </div>
</div>
</div>

<?php include './parts/scripts.php' ?>
<script>

const container = document.querySelector(".card-container");
  const photo = document.querySelector(".photo-container");

  function uploadFile() {
    const fd = new FormData(document.photoform);

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
  let isPass = true;
  if (!isPass) {
    return;
  }
  const fd = new FormData(document.form1);
  fetch('exhibition-add-api.php', {
    method: 'POST',
    body: fd,
  })
    .then(r => r.json())
    .then(data => {
      console.log(data);
      if (data.success) {
        alert('新增成功');
        location.href = "exhibition-list.php";
      } else {
        for (let n in data.error) {
          console.log(`n: ${n}`);
          if (document.form1[n]) {
            const input = document.form1[n];
            input.style.border = '2px solid red';
            input.nextElementSibling.innerHTML = data.error[n];
          }
        }
      }
    })
    .catch(ex => console.log(ex));
}
</script>
<?php include './parts/html-foot.php' ?>