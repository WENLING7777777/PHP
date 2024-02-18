<?php
$pageName = 'login';
$title = '登入';
require './parts/connect_db.php';

if (isset($_SESSION['role'])) {
  header('Location: member-list.php');
  exit;
}
?>

<?php include './parts/html-head.php' ?>
<?php include './parts/navbar.php' ?>


<div class="container">
  <div class="row">
    <div class="col-4 mx-auto mt-5">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">會員登入</h5>
          <form name="form1" method="post" onsubmit="sendData(event)">
            <div class="mb-3">
              <label for="account" class="form-label">Account</label>
              <input type="text" class="form-control" id="account" name="account">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" id="password" name="password">
              <div class="form-text"></div>
            </div>
            <div class="alert alert-danger" role="alert" id="infoBar" style="display:none"></div>
            <button type="submit" class="btn btn-primary">Login</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>


</div>
<?php include './parts/scripts.php' ?>
<script>
  const infoBar = document.querySelector('#infoBar');

  function sendData(event) {
    const fd = new FormData(document.form1); // 沒有外觀的表單
    event.preventDefault();
    fetch('login-api.php', {
        method: 'POST',
        body: fd, // Content-Type 省略, multipart/form-data
      }).then(r => r.json())
      .then(obj => {
        if (obj.success) {
          infoBar.innerHTML = '登入成功'
          infoBar.style.display = 'block';
          location.href = "./index.php"
        } else {
          infoBar.classList.remove('alert-success')
          infoBar.classList.add('alert-danger')
          infoBar.innerHTML = '帳號或密碼錯誤'
          infoBar.style.display = 'block';
        }
      })
      .catch(ex => {
        console.log(ex);
        infoBar.classList.remove('alert-success')
        infoBar.classList.add('alert-danger')
        infoBar.innerHTML = '新增發生錯誤'
        infoBar.style.display = 'block';
      })


  }
</script>
<?php include './parts/html-foot.php' ?>