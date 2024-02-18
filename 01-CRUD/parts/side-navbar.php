<?php
if(! isset($pageName)){
  $pageName ='';
};
if (!isset($_SESSION['role'])) {
  header('Location: login.php');
};
?>
  <div class="col-2">
    <div class="accordion accordion-flush" id="accordionFlushExample">
      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
            會員
          </button>
        </h2>
        <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
          <div class="list-group list-group-flush">
            <a href="member-add.php" class="list-group-item list-group-item-action">新增會員</a>
            <a href="member-list.php" class="list-group-item list-group-item-action">會員管理</a>
            <a href="#" class="list-group-item list-group-item-action">單位管理</a>
            <a href="#" class="list-group-item list-group-item-action">權限管理</a>
          </div>
        </div>
      </div>
      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
            空間設備
          </button>
        </h2>
        <div id="flush-collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
          <div class="list-group list-group-flush">
            <a href="place-add.php" class="list-group-item list-group-item-action">新增地點</a>
            <a href="place-list.php" class="list-group-item list-group-item-action">地點管理</a>
            <a href="space-add.php" class="list-group-item list-group-item-action">新增空間</a>
            <a href="space-list.php" class="list-group-item list-group-item-action">空間管理</a>
            <a href="device-add.php" class="list-group-item list-group-item-action">新增設備</a>
            <a href="device-list.php" class="list-group-item list-group-item-action">設備管理</a>
          </div>
        </div>
      </div>
      
      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
            課程
          </button>
        </h2>
        <div id="flush-collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
        <!-- show加上面的class -->
          <div class="list-group list-group-flush">
            
            <a href="course_add.php" class="list-group-item list-group-item-action">新增課程</a>
            <a href="course_list.php" class="list-group-item list-group-item-action">課程列表</a>
            

            <!-- <a href="#" class="list-group-item list-group-item-action">講師管理</a> -->
            <!-- <a href="#" class="list-group-item list-group-item-action">許願清單管理</a> -->
            <a href="course_cart.php" class="list-group-item list-group-item-action">課程購物車</a>
            <!-- <a href="#" class="list-group-item list-group-item-action">課程訂單管理</a> -->
          </div>
        </div>
      </div>

      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour">
            展覽
          </button>
        </h2>
        <div id="flush-collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
          <div class="list-group list-group-flush">
            <a href="exhibition-list.php" class="list-group-item list-group-item-action">展覽管理</a>
            <a href="exhibition-add.php" class="list-group-item list-group-item-action">展覽新增</a>
            <a href="#" class="list-group-item list-group-item-action">購物車</a>
            <a href="#" class="list-group-item list-group-item-action">訂單管理</a>
          </div>
        </div>
      </div>
      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFive" aria-expanded="false" aria-controls="flush-collapseFive">
            商城
          </button>
        </h2>
        <div id="flush-collapseFive" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
          <div class="list-group list-group-flush">
            <a href="product-list.php" class="list-group-item list-group-item-action">商品管理</a>
            <a href="product-add.php" class="list-group-item list-group-item-action">新增商品</a>
            <a href="product-cart.php" class="list-group-item list-group-item-action">購物車</a>
            <!-- <a href="#" class="list-group-item list-group-item-action">訂單管理</a> -->
          </div>
        </div>
      </div>
    </div>
  </div>