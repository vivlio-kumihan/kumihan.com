<?php
// セッション切符を持っていることが前提
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

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>本の形でパッケージする</title>
  <link rel="stylesheet" href="./assets/css/view.css">
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
              <li class="header-menu"><a href="./index.php" style="a:hover{color: black} ">Home</a></li>
              <li class="header-menu"><a href="./member.php">Member</a></li>
              <li class="header-menu"><a href="./photo.php">Photo</a></li>
              <li class="header-menu"><a href="./board.php">BBS</a></li>
              <li class="header-menu"><a href="./blog.php" target="blank">Blog</a></li>
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
          <i class="fa-solid fa-book" style="margin-right: 8px; color: whitesmoke;"></i>
          <strong>Book</strong>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
      </div>
    </div>
  </header>

  <body>
    <div class="container" style="margin: 30px auto;">
      <?php
      $ua = $_SERVER['HTTP_USER_AGENT'];
      if ((strpos($ua, 'Android') !== false) && (strpos($ua, 'Mobile') !== false) || (strpos($ua, 'iPhone') !== false) || (strpos($ua, 'Windows Phone') !== false)) {
      ?>
        <!-- スマホの場合に読み込むソースを記述 -->
        <div class="col-12" style="margin: 0 auto;">
        <?php } elseif ((strpos($ua, 'Android') !== false) || (strpos($ua, 'iPad') !== false)) { ?>
        <!-- タブレットの場合に読み込むソースを記述 -->
        <div class="col-10" style="margin: 10px auto 0;">
        <?php } else { ?>
        <!-- PCの場合に読み込むソースを記述 -->
        <div class="col-10" style="margin: 20px auto;">
        <?php } ?>
            <h3 style="margin-bottom: 20px;">情報を本の形でパッケージして届ける</h3>
            <p>情報の流通の主役がWEBに移って久しいです。</p>
            <p>奪われ続ける商機を取り戻す方策の一つとして、畑違いだという批判は承知の上で、『WEBの技術』を使って『システム』を売ることを本気で考えてみてはいかがでしょうか。</p>
            <p>現在、WordPressでWEB案件の自社開発を社長は進めておられます。</p>
            <p>私は、それに加え『電子』と『紙』を同時で出力できる『CSS組版システム』に備えることも、京都大学をはじめ学校の『本づくり』に半世紀以上携わってきた北斗プリントにとって重要な鍵になると考えています。</p>

            <p>『CSS組版システム』とは、どういうものか、昨年、東京で開催された技術書典11で「Vivliostyleユーザー会」が頒布した同人誌『Vivliostyleで本を作ろう Vol.5』という本を参考に見ていただこうと思います。</p>
            <ul>
              <li>
                <a href="./vivliostyle-viewer-latest/viewer/#src=http://quad9.sakura.ne.jp/sample/vivliostyle_doc/ja/vivliostyle-user-group-vol5/content/index.html" target=”_blank”>通常ウェブページ</a>として閲覧。</p>
              </li>
              <li>
                <a href="./vivliostyle-viewer-latest/viewer/#src=http://quad9.sakura.ne.jp/sample/vivliostyle_doc/ja/vivliostyle-user-group-vol5/content/&bookMode=true" target=”_blank”>Vivliostyle Viewer</a>で閲覧です。</p>
              </li>
              <li>
                <a href="./vivliostyle-viewer-latest/viewer/#src=http://quad9.sakura.ne.jp/sample/vivliostyle_doc/ja/vivliostyle-user-group-vol5/content/&bookMode=true&userStyle=data:,/*<viewer>*/%0A/*</viewer>*/%0A@page%7Bmarks:crop%20cross;bleed:3mm%7D" target=”_blank”>Vivliostyle Viewer</a>でトンボ付き出力です。</p>
              </li>
            </ul>
            </div>
          </div>

          <script src="https://kit.fontawesome.com/678cad97f5.js" crossorigin="anonymous"></script>
          <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  </body>

</html>