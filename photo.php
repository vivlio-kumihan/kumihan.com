<?php
// セッション開始
session_start();
// セッションの切符も持っていない訪問者にログインページへリダイレクト処理。
if (!$_SESSION['email']) {
  $host = $_SERVER['HTTP_HOST'];
  $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
  header("Location: //$host$uri/login.php");
  exit;
}
// 画像収集
$images = glob('./images/album/*.jpg');
?>

<!doctype html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
  <meta name="generator" content="Hugo 0.84.0">
  <title>写真ページ</title>
  <link rel="stylesheet" href="assets/css/fonts.css" />
  <link rel="stylesheet" href="./assets/css/fontawesome-all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/album/">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.css">
  <link rel="stylesheet" href="./assets/css/another-page.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.min.js"></script>

  <script>
    $(document).ready(function() {
      $('.slider').bxSlider();
    });
  </script>
  <style>
    .bd-placeholder-img {
      font-size: 1.125rem;
      text-anchor: middle;
      -webkit-user-select: none;
      -moz-user-select: none;
      user-select: none;
    }

    @media (max-width: 767px) {
      h1.page-title {
        margin-bottom: 0;
      }

      .bx-viewport {
        height: 694.25px;
      }

      .bx-pager {
        display: none;
      }

      #view {
        display: none;
      }
    }

    .bd-placeholder-img-lg {
      font-size: 3.5rem;
    }

    .py-5 {
      padding-top: 0rem !important;
      padding-bottom: 0rem !important;
    }
    
    .py-lg-5 {
      padding-top: 1.5rem !important;
      padding-bottom: 1rem !important;
    }

    .bx-viewport {
      height: 620px;
    }

    .bx-wrapper {
      margin-bottom: 30px !important;
    }

    .bx-pager {
      display: none;
    }
  </style>


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
              <li class="header-menu"><a href="./member.php">Member</a></li>
              <li class="header-menu"><a href="./book.php">Book</a></li>
              <li class="header-menu"><a href="./blog.php" target="blank">Blog</a></li>
              <li class="header-menu"><a href="./board.php">BBS</a></li>
              <li class="header-menu" style="margin-top: 10px;"><a href=" ./contact.php">Contact</a></li>
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
          <i class="fa-solid fa-camera-retro" style="margin-right: 5px; color: whitesmoke;"></i>
          <i class="fa-solid fa-aperture" style="margin-right: 5px; color: whitesmoke;"></i>
          <strong>Photo</strong>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
      </div>
    </div>
  </header>

  <main>
    <section class="py-5 text-center container">
      <!-- ページタイトル -->
      <div class="row py-lg-5">
        <div class="col-lg-6 col-md-8 mx-auto">
          <h1 class="page-title">Photo</h1>
          <!-- <p class="lead text-muted">リードコピー</p> -->
        </div>
      </div>
      <!-- 写真のスライダー -->
      <div class="slider">
        <?php foreach ($images as $photo) { ?>
          <div><?php echo '<img src="', $photo, '">'; ?></div>
        <?php } ?>
      </div>

      <div id="view" class="row">
        <?php foreach ($images as $photo) { ?>
          <div class="col-2">
            <?php echo '<img src="', $photo, '" alt="thumbnail" class="img-fluid img-thumbnail" style="margin-bottom : 15px;">'; ?>
          </div>
        <?php } ?>
      </div>
    </section>
  </main>

  <script src="https://kit.fontawesome.com/678cad97f5.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>