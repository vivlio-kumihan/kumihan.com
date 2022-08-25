<?php
session_start();
$err = array();
$img = $_FILES['img'];
var_dump($img);
$type = exif_imagetype($img['tmp_name']);
if ($type !== IMAGETYPE_JPEG && $type !== IMAGETYPE_PNG) {
  $err['pic'] = '送信できるのはJPEGかPNGのみです。';
} elseif($img['size'] > 102400) {
  $err['pic'] = 'ファイルサイズは100KB以下にしてください。';
} else {
  $extension = pathinfo($img['name'], PATHINFO_EXTENSION);
  $new_img = md5(uniqid(mt_rand(), true)).'.'.$extention;
  move_uploaded_file($img['tmp_name'], './images/album/'.$new_img);
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>pic receive</title>
</head>
<body>
  <h1>写真受信のページ</h1>
  <?php if (count($err) > 0) {
    foreach($err as $row) {
      echo '<p>'.$row.'</p>';
    }
    echo '<a href="./pic_send.php">戻る</a>';
  } else {
  ?>
  <div><img src="https://kumihan.com/images/<?php echo $new_img; ?>" alt=""></div>
  <?php } ?>
</body>
</html>