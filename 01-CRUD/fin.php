<?php
require './parts/connect_db.php';
$pageName = 'list';
$title = '購物車';

$member_id = $_SESSION['role']['member_id'];

$space_sql = sprintf("SELECT * FROM space_cart JOIN space ON space_cart.space_id = space.space_id WHERE member_id = '{$member_id}'");
$space_rows = $pdo->query($space_sql)->fetchAll();

$course_sql = sprintf(
  "SELECT * FROM course_cart 
    JOIN course ON course_cart.course_id = course.course_id 
    join place on course.place_id = place.place_id
    WHERE member_id = '{$member_id}'"
);
$course_rows = $pdo->query($course_sql)->fetchAll();
?>

<?php include './parts/html-head.php' ?>
<?php include './parts/navbar.php' ?>

<div class="col-12 mx-auto mt-5">
      <h1 class="position-absolute top-50 start-50 translate-middle" style=" font-size:200px">Fin.</h1>
</div>
</div>
</div>

<?php include './parts/scripts.php' ?>
<script>
  function addOrder() {
      if (confirm(`確定要購買嗎?`)) {
        location.href = 'cart-api.php';
      }
    }
</script>
<?php include './parts/html-foot.php' ?>