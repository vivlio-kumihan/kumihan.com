<?php
// 方針として、
// 自作関数をプロジェクトに『lib（ライブラリー）』ディレクトリを設置
// DB関連の秘匿情をwww以外の階層で管理する。
require_once('../tmp/conf.php');
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

$err_mesg = array();
$mesg = array();
$complete = false;

if ($_POST) {
  // 自作関数でフォームから入力されたPOSTの中身を変数に格納する。
  $password = get_post('password');
  $new_password = get_post('new_password');

  // POST情報がある場合の処理
  // 現在のパスワードについてのvalidation
  if (!$password) {
    $err_mesg[] = '現在のパスワードを入力してください。';
  } elseif (mb_strlen($password) > 17) {
    $err_mesg[] = 'パスワードは、16文字以内で入力してください。';
  }

  // 新しいパスワードについてのvalidation
  if (!$new_password) {
    $err_mesg[] = '新しいパスワードを入力してください。';
  } elseif (mb_strlen($new_password) > 17) {
    $err_mesg[] = 'パスワードは、16文字以内で入力してください。';
  }

  // DB接続に係る変数を生成
  $dsn = DNS;
  $user = DB_USER;
  $pwd = DB_PASSWORD;
  $dbh = new PDO($dsn, $user, $pwd);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  try {
    $sql = "SELECT * FROM member WHERE email = :email LIMIT 1";
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':email', $_SESSION['email'], PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
      $data = $stmt->fetch(PDO::FETCH_ASSOC);
      // 新しいパスワードのハッシュ値を生成させて初期化する。
      $hashed_new_pw = password_hash($new_password, PASSWORD_DEFAULT);
      if (password_verify($password, $data['password'])) {
        $sql = "UPDATE member SET password = :password WHERE {$data['id']}";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':password', $hashed_new_pw, PDO::PARAM_STR);
        $stmt->execute();
        $mesg[] = "パスワードを変更しました。";
      } else {
        $err_mesg[] = '登録されたパスワードと違います。';
        $err_mesg[] = 'パスワードを<a href="./logout.php">再発行</a>されますか？';
      }
    }
  } catch (PDOException $e) {
    print("接続に失敗しました。" . $e->getMessage());
    die();
  }
} else {
  // GETで情報がきた時の処理
  $_POST = array();
}

// if (!$complete) {
//   // このエラーメッセージ要るか？
//   $err_mesg[] = '現在のパスワードが正しくありません。';
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>パスワード変更</title>
  <style>
    .submit {
      text-align: center;
    }
  </style>
  <link rel="stylesheet" href="./assets/css/another-page.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
  <header>
    <div class="collapse bg-dark" id="navbarHeader">
      <div class="container">
        <div class="row">
          <div class="col-sm-8 col-md-7 py-4">
            <h4 class="text-white">About</h4>
            <p class="text-muted">Add some information about the album below, the author, or any other background context. Make it a few sentences long so folks can pick up some informative tidbits. Then, link them off to some social networking sites or contact information.</p>
          </div>
          <div class="col-sm-4 offset-md-1 py-4">
            <ul class="list-unstyled">
              <li class="header-menu"><a href="./index.php">Home</a></li>
              <li class="header-menu"><a href="./member.php">Member</a></li>
              <li class="header-menu"><a href="./photo.php">Photo</a></li>
              <li class="header-menu"><a href="./book.php">Book</a></li>
              <li class="header-menu"><a href="./blog.php" target="blank">Blog</a></li>
              <li class="header-menu"><a href="./board.php">BBS</a></li>
              <li class="header-menu"><a href="./contact.php">Contact</a></li>
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
          <i class="fa-solid fa-key" style="margin-right: 5px; color: whitesmoke;"></i>
          <strong>Change Password</strong>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
      </div>
    </div>
  </header>

  <div class="container">
    <div class="mx-auto" style="margin-top:150px; width: 400px;">
      <?php
      if ($err_mesg) {
        echo '<div class="alert alert-danger" role="alert">';
        echo implode('<br>', $err_mesg);
        echo '</div>';
      } elseif ($mesg) {
        echo '<div class="alert alert-success" role="alert">';
        echo implode('<br>', $mesg);
        echo '</div>';
      }
      ?>
      <?php if ($complete) { ?>
        <p>パスワードを変更しました。</p>
        <a href="./member.php">インデックスページへ</a>
      <?php } else { ?>
        <form action="./change_pw.php" method="POST">
          <div class="mb-3">
            <label class="form-label">現在のパスワード</label>
            <input class="form-control" type="password" name="password" value="">
          </div>
          <div class="mb-3">
            <label class="form-label">新しいパスワード</label>
            <input class="form-control" type="password" name="new_password" value="">
          </div>
          <div class="submit">
            <button type="submit" class="btn btn-primary btn-sm" value="変更">変更する</button>
          </div>
        </form>
      <?php } ?>
    </div>
  </div>

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