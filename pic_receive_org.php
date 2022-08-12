<?php
$err = array();
$img = $_FILES['img'];
var_dump($img);

move_uploaded_file($img['tmp_name'], './images/'.$img['name']);
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
  <div><img src="https://kumihan.com/images/<?php echo $img['name']; ?>" alt=""></div>
  <?php } ?>
</body>
</html>