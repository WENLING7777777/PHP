<?php
if(! isset($pageName)){
  $pageName ='';
};


if(!empty($_SESSION['role'])){
  $member_id = $_SESSION['role']['member_id'];
  $spaceCart_sql = "SELECT COUNT(*) FROM space_cart WHERE member_id = '{$member_id}'";
  $spaceRows = $pdo->query($spaceCart_sql)->fetch(PDO::FETCH_NUM)[0];

  $courseCart_sql = "SELECT COUNT(*) FROM course_cart WHERE member_id = '{$member_id}'";
  $courseRows = $pdo->query($courseCart_sql)->fetch(PDO::FETCH_NUM)[0];
}

?>
<div class="row">
  <div class="col-12">
  <nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Navbar</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="space-result.php">空間</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="course-info-card.php">課程</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="exhibition-listcard.php">展覽</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="product-list2.php">商品</a>
        </li>
      </ul>
      <ul class="navbar-nav mb-2 mb-lg-0">
        <?php if (isset($_SESSION['role'])) : ?>
          <a href="cart.php">
          <button type="button" class="btn btn-primary">
            購物車 <span class="badge text-bg-info"><?= $spaceRows+$courseRows ?></span>
          </button>
          </a>
          <li class="nav-item mx-4"><?= $_SESSION['role']['role'] ?></li>
          <li class="nav-item mx-4"><?= $_SESSION['role']['nickname'] ?></li>
            <?php if($_SESSION['role']['role_id']== 2 || $_SESSION['role']['role_id']== 3): ?>
          <li class="nav-item mx-4"><a href="space-list.php">後台</a></li>
          <?php elseif($_SESSION['role']['role_id']== 1): ?>
            <li class="nav-item mx-4"><a href="member-edit.php">會員中心</a></li>
          <?php endif; ?>
          <li class="nav-item mx-4"><a href="logout.php">登出</a></li>
        <?php else : ?>
          <li class="nav-item mx-4"><a href="login.php">登入</a></li>
          <li class="nav-item mx-4"><a href="member-add.php">註冊</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>


    <!-- <nav class="navbar bg-body-tertiary">
      <div class="container-fluid">
        <span class="navbar-brand mb-0 h1">Navbar</span>
        <?php if (isset($_SESSION['role'])) : ?>
          <span><?= $_SESSION['role']['role'] ?></span>
          <span><?= $_SESSION['role']['nickname'] ?><a href="logout.php">登出</a></span>
        <?php else : ?>
          <span><a href="login.php">登入</a></span>
          <span><a href="member-add.php">註冊</a></span>
        <?php endif; ?>
      </div>
    </nav> -->
  </div>