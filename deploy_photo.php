<?php

require_once('../../tmp/conf.php');
require_once('./lib/function.php');

// セッション開始
session_start();
// セッションの切符も持っていない訪問者にログインページへリダイレクト処理。
if (!$_SESSION['email']) {
  $host = $_SERVER['HTTP_HOST'];
  $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
  header("Location: //$host$uri/login.php");
  exit;
}

$err = array();
$img = $_FILES['img'];
$type = exif_imagetype($img['tmp_name']);

if ($type !== IMAGETYPE_JPEG && $type !== IMAGETYPE_PNG) {
  $err['pic'] = '送信できるのはJPEGかPNGのみです。';
} elseif ($img['size'] > 4194304) {
  $err['pic'] = 'ファイルサイズは4MB以下にしてください。';
} else {
  $extension = pathinfo($img['name'], PATHINFO_EXTENSION);
  $new_img = md5(uniqid(mt_rand(), true)) . '.' . $extention;
  move_uploaded_file($img['tmp_name'], './images/album/' . $new_img);
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>写真のアップロード</title>
  <link rel="stylesheet" href="assets/css/fonts.css" />
  <link rel="stylesheet" href="./assets/css/fontawesome-all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="./assets/css/another-page.css">
</head>

<body>

  <header>
    <div class="collapse bg-dark" id="navbarHeader">
      <div class="container">
        <div class="row">
          <div class="col-sm-8 col-md-7 py-4">
            <!-- <h4 class="text-white">About</h4> -->
            <!-- <p class="text-muted"></p> -->
          </div>
          <div class="col-sm-4 offset-md-1 py-4">
            <ul class="list-unstyled">
              <li class="header-menu"><a href="./index.php">Home</a></li>
              <li class="header-menu"><a href="./photo.php">Photo</a></li>
              <li class="header-menu"><a href="./book.php">Book</a></li>
              <li class="header-menu"><a href="./blog.php" target="blank">Blog</a></li>
              <li class="header-menu"><a href="./board.php">BBS</a></li>
              <li class="header-menu" style="margin-top: 10px;"><a href="./contact.php">Contact</a></li>
              <li class="header-menu"><a href="./register.php">SignUp</a></li>
              <li class="header-menu"><a href="./login.php">LogIn</a></li>
              <li class="header-menu"><a href="./logout.php">LogOut</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="navbar navbar-dark bg-dark shadow-sm">
      <div class="container">
        <a href="#" class="navbar-brand d-flex align-items-center">
          <i class="fa-solid fa-cloud-arrow-up" style="margin-right: 5px; color: whitesmoke;"></i>
          <strong>Upload Photo</strong>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
      </div>
    </div>
  </header>

  <?php
  if ($toward === 'prepare_deploy') {
    // アップロードのためのフォーム
  } elseif ($toward === 'confirm')
  ?>

  <h3 style="text-align: center;">画像のアップロード</h3>
  <form action="./deploy_photo.php" method="POST" enctype="multipart/form-data">
    <p><input type="file" name="img"></p>
    <p><input type="submit" value="送信"></p>
  </form>
</body>

<h3 style="text-align: center;">送信エラー</h3>
<?php if (count($err) > 0) {
  foreach ($err as $mesg_line) {
    echo '<p>' . $mesg_line . '</p>';
  }
  echo '<a href="./deploy_photo.php">戻る</a>';
} else {
?>
  <div><img src="<?php echo SITE_URL . $new_img; ?>" alt=""></div>
<?php } ?>

<script src="https://kit.fontawesome.com/678cad97f5.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>

</html>