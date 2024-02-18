<?php
  require './parts/connect_db.php';
  $pageName = 'list';
  $title = '列表';

  $perPage = 20;

  $page = isset($_GET['page'])?intval($_GET['page']):1;
  if($page<1){
    header('Loctaion: ?page=1');
    exit;
  }

  $t_sql = "SELECT COUNT(*) FROM member";

  $totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0];

  $totalPages = 0;
  $rows = [];

  if($totalRows > 0){
    $totalPages = ceil($totalRows/$perPage);
    if ($page > $totalPages) {
      header('Location: ?page=' . $totalPages);
      exit;
    }

    $sql = sprintf("SELECT * FROM ((member JOIN area ON member.area_id = area.area_id) JOIN city ON area.city_id = city.city_id) JOIN role ON member.role_id = role.role_id ORDER BY member_id DESC LIMIT %s, %s", ($page-1)*$perPage, $perPage);
    $rows = $pdo->query($sql)->fetchAll();
  }

  
?>

<?php include './parts/html-head.php'?>
<?php include './parts/navbar.php'?>
<?php include './parts/side-navbar.php' ?>

    <div class="col-9 mx-auto mt-5">
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th scope="col">
              <i class="fa-solid fa-trash-can"></i>
            </th>
            <th scope="col">#</th>
            <th scope="col">姓名</th>
            <th scope="col">暱稱</th>
            <th scope="col">生日</th>
            <th scope="col">性別</th>
            <th scope="col">電話</th>
            <th scope="col">縣市</th>
            <th scope="col">區域</th>
            <th scope="col">email</th>
            <?php if($_SESSION['role']['role_id']== 3): ?>
              <th scope="col">權限</th>
              <?php endif; ?>
            <th scope="col">
              <i class="fa-solid fa-file-pen"></i>
            </th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($rows as $r): ?>
            <tr>
              <td>
                <a href="javascript: deleteItem('<?= $r['member_id'] ?>')">
                  <i class="fa-solid fa-trash-can"></i>
                </a>
              </td>
              <td><?= $r['member_id'] ?></td>
              <td><?= $r['name'] ?></td>
              <td><?= $r['nickname'] ?></td>
              <td><?= $r['birthday'] ?></td>
              <td><?= $r['gender'] ?></td>
              <td><?= $r['phone'] ?></td>
              <td><?= $r['city'] ?></td>
              <td><?= $r['area'] ?></td>
              <td><?= $r['email'] ?></td>
              <?php if($_SESSION['role']['role_id']== 3): ?>
                <td><?= $r['role'] ?></td>
              <?php endif; ?>
              
              <td>
                <a href="member-edit.php?member_id=<?= $r['member_id'] ?>">
                  <i class="fa-solid fa-file-pen"></i>
                </a>
              </td>
            </tr>
          <?php endforeach ?>
        </tbody>
      </table>
</div>

<?php include './parts/scripts.php'?>
<script>
  function deleteItem(member_id) {
    if (confirm(`確定要刪除 ${member_id} 嗎?`)) {
      location.href = 'member-delete.php?member_id=' + member_id;
    }
  }
</script>
<?php include './parts/html-foot.php'?>