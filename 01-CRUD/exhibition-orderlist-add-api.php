<?php
require './parts/connect_db.php';

$output = [
    'postData' => $_POST,
    'success' => false,
    'errors' => [],
];

header('Content-Type: application/json');


$member_id = $_SESSION['role']['member_id'];
//  照著購物車的欄位去寫//

$exhibition_id = isset($_GET['exhibition_id']) ? $_GET['exhibition_id'] : null;

$sql = "INSERT INTO exhibition (exhibition_id, exhibition_amount) VALUES (:exhibition_id, :exhibition_amount)";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':exhibition_id', $exhibition_id, PDO::PARAM_INT);
$stmt->bindParam(':exhibition_amount', $exhibition_amount, PDO::PARAM_INT);


$photo_sql = "INSERT INTO `exhibition_photo`( `exhibition_id`, `exhibition_photo`)VALUES('$exhibition_id', ?)";

$exhibition_id = $_POST['exhibition_id'] ?? '';
$exhibition_type_id = $_POST['exhibition_type_id'] ?? '';
$exhibition_name = $_POST['exhibition_name'] ?? '';
$start_time = $_POST['start_time'] ?? '';
$end_time = $_POST['end_time'] ?? '';
$space_id = $_POST['space_id'] ?? '';
$exhibition_amount = $_POST['exhibition_amount'] ?? '';

$exhibition_type_id = intval($exhibition_type_id);
$exhibition_amount = intval($exhibition_amount);

function generateUniqueExhibitionID($pdo) {
    $prefix = 'E';
    $id = 0;
  
    $query = "SELECT MAX(CAST(SUBSTRING(exhibition_id, 2) AS SIGNED)) AS max_id FROM exhibition";
    $result = $pdo->query($query);
    $row = $result->fetch(PDO::FETCH_ASSOC);

    if ($row['max_id']) {
        $id = intval($row['max_id']) + 1;
    }

    return $prefix . str_pad($id, 4, '0', STR_PAD_LEFT);
}

$exhibition_id = generateUniqueExhibitionID($pdo);

$sql = "INSERT INTO `exhibition` (`exhibition_id`, `exhibition_type_id`, `exhibition_name`, `start_time`, `end_time`, `space_id`, `exhibition_amount`, `create_date`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, now() )";
$stmt = $pdo->prepare($sql);

if ($stmt->execute([
    $exhibition_id, 
    $exhibition_type_id,
    $exhibition_name,
    $start_time,
    $end_time,
    $space_id,
    $exhibition_amount,
])) {
    $output['success'] = true;
} else {
    $output['errors']['database'] = '新增失敗';
}

echo json_encode($output);
?>
