<?php

$dir = __DIR__. '/uploads/';

$exts = [
  'image/jpeg' => '.jpg',
  'image/png' => '.png',
  'image/webp' => '.webp',
];

header('Content-Type: application/json');
$output = [
  'success' => false,
  'files' => []
];

if(!empty($_FILES) and !empty($_FILES['photos']) and is_array($_FILES['photos']['name'])){
    foreach($_FILES['photos']['name'] as $index => $name){
      if(!empty($exts[$_FILES['photos']['type'][$index]])){
        $ext = $exts[$_FILES['photos']['type'][$index]];
    
        $f = sha1($name. uniqid().rand());
    
        if(move_uploaded_file($_FILES['photos']['tmp_name'][$index], $dir.$f.$ext)){
          $output['files'][] = $f. $ext; //array push
        }
      }
    }
  
  if(count($output['files'])){
    $output['success'] = true;
  }
}

echo json_encode($output);

?>