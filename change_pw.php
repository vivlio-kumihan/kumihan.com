<?php
// require_once('../../tmp/conf.php');
// require_once('./lib/function.php');
require_once('./conf.php');
require_once('./function.php');
// セッション開始
session_start();
// セッションの切符も持っていない訪問者にログインページへリダイレクト処理。
if (!$_SESSION['email']) {
  $host = $_SERVER['HTTP_HOST'];
  $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
  header("Location: //$host$uri/login.php");
  exit;
}
// 初期化
$err_mesg = array();
$mesg = array();
// 初期状態でEメールアドレスを入力するフォームを出しておきたいがための真偽値をここで設定する。
$complete = false;

// POSTでパスワードが投げられる。
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
        $mesg[] = "パスワードを<br>変更しました。";
        $complete = true;
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
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
  <meta name="generator" content="Hugo 0.84.0">
  <title>パスワードの変更</title>
  <link rel="stylesheet" href="assets/css/fonts.css">
  <link rel="stylesheet" href="./assets/css/fontawesome-all.min.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="./assets/css/bs_signin.css">
  <link rel="stylesheet" href="./assets/css/another-page.css">

  <style>
    .bd-placeholder-img {
      font-size: 1.125rem;
      text-anchor: middle;
      -webkit-user-select: none;
      -moz-user-select: none;
      user-select: none;
    }

    @media (min-width: 768px) {
      .bd-placeholder-img-lg {
        font-size: 3.5rem;
      }
    }

    h3.form-heading {
      font-family: 'Noto Sans JP', sans-serif;
      font-weight: 700;
      font-size: 24px;
    }

    #btn {
      font-family: 'Noto Sans JP', sans-serif;
      font-weight: 900;
      font-size: 18px;
    }

    p.notes {
      margin-top: 20px;
      font-size: 0.9em;
      line-height: 1.2;
      text-align: center;
    }

    h3.form-heading {
      margin-bottom: 20px;
    }

    .form-signin input[type="email"] {
      margin-bottom: 0px;
      border-bottom-right-radius: 0.25rem;
      border-bottom-left-radius: 0.25rem;
    }

    .form-signin input[type="password"] {
      margin-bottom: 0px;
      border-top-left-radius: 0.25rem;
      border-top-right-radius: 0.25rem;
    }

    button {
      margin-top: 20px;
    }
  </style>

</head>

<body class="text-center">
  <main class="form-signin">
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
      <p>一旦ログアウトして<br>ご確認ください。</p>
      <a href="./logout.php" style="font-size: 0.85rem;">ログアウトへ</a>
    <?php } else { ?>
      <form action="./change_pw.php" method="POST">
        <h3 class="form-heading">パスワードの変更</h3>
        <div class="form-floating">
          <input class="form-control" type="password" id="floatingInput" name="password" value="">
          <label for="floatingInput">現在のパスワード</label>
        </div>
        <div class="form-floating">
          <input class="form-control" type="password" id="floatingPassword" name="new_password" value="">
          <label for="floatingPassword">新しいパスワード</label>
        </div>
        <button id="btn" class="w-100 btn btn-lg btn-primary" type="submit">送信</button>
        <p class="notes"><br><a href="./member.php" style="text-decoration: none; color:cornflowerblue">メンバーページ</a>へ戻る。</p>
        <p class="mt-5 mb-3 text-muted">&copy; kumihan.com</p>
      </form>
    <?php } ?>
  </main>

  <script src="https://kit.fontawesome.com/678cad97f5.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>