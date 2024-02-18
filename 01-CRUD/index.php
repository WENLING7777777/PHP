<?php
  require './parts/connect_db.php';
  $pageName = 'home';
  $title = '首頁';



  $space_sql = sprintf("SELECT * FROM ((space JOIN place ON space.place_id = place.place_id) JOIN area ON place.area_id = area.area_id) JOIN city ON area.city_id = city.city_id JOIN category ON space.category_id = category.category_id ORDER BY space_id DESC");
  $space_rows = $pdo->query($space_sql)->fetchAll();

  $space_photo_sql = "SELECT * FROM space_photo";
  $space_photo_rows = $pdo->query($space_photo_sql)->fetchAll();

  $course_sql = sprintf(
    "SELECT * FROM course
    join category on course.category_id = category.category_id
    join place on course.place_id = place.place_id
    join course_status on course.course_status_id = course_status.course_status_id
    join teacher on course.teacher_id = teacher.teacher_id
    join course_photo on course.course_id = course_photo.course_id
    ORDER BY course.course_id DESC");
  $course_rows = $pdo->query($course_sql)->fetchAll();
  

  $exhibition_sql = sprintf(
    "SELECT exhibition.*, space.space_id, space.space AS space_name FROM exhibition LEFT JOIN space ON exhibition.space_id = space.space_id ORDER BY exhibition.exhibition_id DESC");
  $exhibition_rows = $pdo->query($exhibition_sql)->fetchAll();

  $product_sql = sprintf(
    "SELECT p.*, c.category, pp.product_photo 
    FROM product p
    INNER JOIN category c ON p.category_id = c.category_id
    LEFT JOIN product_photo pp ON p.product_id = pp.product_id
    ORDER BY p.product_id DESC");

$product_rows = $pdo->query($product_sql)->fetchAll(PDO::FETCH_ASSOC);

?>

<?php include './parts/html-head.php'?>
<?php include './parts/navbar.php'?>

    <div class="col-9 mx-auto mt-5">
      <div class="row">
        <h3>空間</h3>
        <?php 
          $space_num = 1;
          foreach($space_rows as $r):
          if ($r['space_status_id'] != 0 and $space_num < 5):
          $n = 1;
        ?>
          <div class="col-3 mb-3">
            <div class="card" style="width: 18rem;">
            <?php foreach($space_photo_rows as $p):
              if($p['space_id']==$r['space_id'] and $n==1):
                $n++;$space_num++;?>

              <img src="./uploads/<?= $p['space_photo'] ?>" class="card-img-top" alt="...">
              <?php endif;
            endforeach; ?>
              <div class="card-body">
                <h5 class="card-title"><?= $r['space'] ?></h5>
                <h6 class="card-title"><?= $r['category'] ?></h6>
                <p class="card-text"><?= $r['city'] ?><?= $r['area'] ?></p>
                <p class="card-text">建議人數：<?= $r['accommodate'] ?>人</p>
                <p class="card-text">$<?= $r['time_rate'] ?>/小時</p>

                <a href="space-detail.php?space_id=<?= $r['space_id'] ?>" class="btn btn-primary">查看詳情</a>
              </div>
            </div>
          </div>
          <?php endif;
              endforeach ?>
      <h3 class="mt-5">課程</h3>
      <?php 
      $course_num = 1;
      foreach ($course_rows as $r) : 
      if($course_num<4): $course_num++;?>
        <div class="col-4 mb-5">
          
            <div class="card">
              <div class="card-body">
                <img src="./uploads/<?= htmlentities($r['course_photo']) ?>" class="card-img-top" alt="Placeholder Image">
                <h5 class="card-title mt-4"> <?= $r['course_name'] ?> </h5>
                <p>課程類別：<?= $r['category'] ?></p>
                <p>課程日期＆時間：<?= $r['course_time'] ?></p>
                <p>課程地點：<?= $r['place'] ?></p>
                <p>學生人數：<?= $r['people'] ?></p>
                <p>講師名稱：<?= $r['teacher_name'] ?></p>
                <h4>NT$<?= $r['course_price'] ?></h4>
                
                <a href="course-info-desc_add.php?course_id=<?= $r['course_id'] ?>" class="btn btn-primary">
                詳細說明
                </a>
              </div>
            </div>
        </div>
      <?php endif;endforeach ?>
      <h3 class="mt-5">展覽</h3>
      <?php 
      $exhibition_num = 1;
      foreach ($exhibition_rows as $r) : 
      if($exhibition_num <4): $exhibition_num++?>
                <div class="col-4">
                    <div class="card mb-4">
                        <div style="width: 200px; margin:15px;">
                            <?php
                            $exhibition_id = $r['exhibition_id'];
                            $exhibition_photo_sql = "SELECT * FROM exhibition_photo WHERE exhibition_id='{$exhibition_id}'";
                            $exhibition_photo_rows = $pdo->query($exhibition_photo_sql)->fetchAll();
                            foreach ($exhibition_photo_rows as $photo) :
                            ?>
                                <img src="uploads/<?= htmlentities($photo['exhibition_photo']) ?>" alt="展覽圖片" width="260" height="260"><br>
                            <?php endforeach; ?>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?= $r['exhibition_name'] ?></h5>
                            <p class="card-text card-text-custom"><?= htmlentities($r['exhibition_desc']) ?></p>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>展覽編號：</strong><?= $r['exhibition_id'] ?></li>
                            <li class="list-group-item"><strong>展覽型態：</strong><?= $r['exhibition_type_id'] ?></li>
                            <li class="list-group-item"><strong>展覽人數：</strong><?= $r['exhibition_people'] ?></li>
                            <li class="list-group-item"><strong>開始時間：</strong><?= $r['start_time'] ?></li>
                            <li class="list-group-item"><strong>結束時間：</strong><?= $r['end_time'] ?></li>
                            <li class="list-group-item"><strong>租借空間：</strong><?= $r['space_name'] ?></li>
                        </ul>
                        <!-- 贊助按鈕 -->
                        <div class="card-footer">
                            <a href="exhibition-orderlist-add.php?exhibition_id=<?= $r['exhibition_id'] ?>" class="btn btn-outline-primary">贊助</a>
                        </div>
                    </div>
                </div>
            <?php endif;endforeach; ?>
      <h3 class="mt-5">商品</h3>
      <?php
      $product_num = 1;
      foreach ($product_rows as $r) : 
      if($product_num<4):$product_num++;?>
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body">
                <div style="width: 200px; margin: 0 auto;">
                    <img src="uploads/<?= htmlentities($r['product_photo']) ?>" alt="產品圖片" width="200" height="200">
                </div></br>
                <h5 class="card-title">產品名稱: <?= $r['product_name'] ?></h5>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">產品簡介: <?= $r['desc'] ?></li>
                <li class="list-group-item">產品代號: <?= $r['product_id'] ?></li>
                <li class="list-group-item">類別: <?= $r['category'] ?></li>
            </ul>
            <div class="card-footer d-flex justify-content-center">
                <a href="product-info.php?product_id=<?= $r['product_id'] ?>" class="btn btn-primary">
                    <i class="fa-solid fa-file-pen "></i>詳細資訊
                </a>
            </div>
        </div>
    </div>
    <?php endif;endforeach; ?>
      </div>
    </div>
</div>

<?php include './parts/scripts.php'?>
<script>

</script>
<?php include './parts/html-foot.php'?>