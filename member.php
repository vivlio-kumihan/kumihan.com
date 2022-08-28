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
?>

<!doctype html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <title>登録メンバーのページ</title>
  <link rel="stylesheet" href="assets/css/fonts.css">
  <link rel="stylesheet" href="./assets/css/fontawesome-all.min.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
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
          <i class="fa-solid fa-people-group" style="margin-right: 5px; color: whitesmoke;"></i>
          <strong>Member Page</strong>
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
          <h1 class="fw-light">登録メンバーのページ</h1>
          <p style="margin-top: 20px; size: 0.8em; text-align: center;">パスワードの変更は<a href="./change_pw.php" style="text-decoration: none; color:cornflowerblue">こちらから</a>お願いします。</p>
          <!-- <p class="lead text-muted">リードコピー</p> -->



        </div>
      </div>
    </section>
  </main>

  <footer class="text-muted py-5">
    <div class="container">
      <p class="float-end mb-1">
        <a href="#">Back to top</a>
      </p>
    </div>
  </footer>
  <script src="https://kit.fontawesome.com/678cad97f5.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>