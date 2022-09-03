<?php
require_once('../../tmp/conf.php');
require_once('./lib/function.php');

// 初期化
$err_mesg = array();
$mesg = array();
// 初期状態でEメールアドレスを入力するフォームを出しておきたいがための真偽値をここで設定する。
$complete = false;

// POSTでEメールアドレスが投げられる。
if ($_POST) {
  $email = get_post('email');

  // Eメールアドレス
  if (!$email) {
    $err_mesg[] = 'Eメールアドレスを入力してください。';
    // 100文字以上の入力があれば、
  } elseif (mb_strlen($email) > 100) {
    $err_mesg[] = '100文字以内のアドレスを入力してください。';
    // 入力されたEメールアドレスをvalidateしてみて不正であれば、
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $err_mesg[] = '入力されたEメールアドレスは不正です。';
  }

  // DB接続に係る変数を生成
  $dsn = DNS;
  $user = DB_USER;
  $pwd = DB_PASSWORD;
  $dbh = new PDO($dsn, $user, $pwd);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  //////////////////////////////////////////////// 質問
  // registerと似てるけど異なる'COUNT(id)'とrowCount()

  try {
    $sql = "SELECT * FROM member WHERE email = :email LIMIT 1";
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
      $data = $stmt->fetch(PDO::FETCH_ASSOC);
      // ================ 重要 =================
      // 再発行するパスワードを生成する。
      $tmp_pw = bin2hex(random_bytes(5));
      // ================ 重要 =================
      // サーバーにメールを送信させる命令。
      // 日本語の送信で文字化けが起こる場合、mail.phpを参照する。
      $mail_mesg = "パスワードを変更しました。\r\n新パスワード => " . $tmp_pw . "\r\n";
      mail($email, 'パスワードの再発行いたしました。', $mail_mesg);
      // パスワードハッシュをかける。
      $hashed_tmp_pw = password_hash($tmp_pw, PASSWORD_DEFAULT);
      // 該当のデータをアップデートする。
      $sql = "UPDATE member SET password = :password WHERE {$data['id']}";
      $stmt = $dbh->prepare($sql);
      $stmt->bindValue(':password', $hashed_tmp_pw, PDO::PARAM_STR);
      $stmt->execute();
      $complete = true;
      $mesg[] = "パスワードを登録されているEメールアドレス宛に送信しました。";
      // $host = $_SERVER['HTTP_HOST'];
      // $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
      // header("Location: //$host$uri/logout.php");
    } else {
      $err_mesg[] = '登録されたEメールアドレスと違います。';
    }
  } catch (PDOException $e) {
    print("接続に失敗しました。" . $e->getMessage());
    die();
  }
} else {
  // GETで情報がきた時の処理
  $_POST = array();
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
  <meta name="generator" content="Hugo 0.84.0">
  <title>パスワードの再発行</title>
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
      echo '<div class="alert alert-danger" role="alert style="font-size: 0.85rem;">';
      echo implode('<br>', $err_mesg);
      echo '</div>';
    } elseif ($mesg) {
      echo '<div class="alert alert-success" role="alert" style="font-size: 0.85rem;">';
      echo implode('<br>', $mesg);
      echo '</div>';
    }
    ?>
    <?php if ($complete) { ?>
      <a href="./logout.php" style="font-size: 0.85rem;">ログアウトへ</a>
    <?php } else { ?>
      <form action="./forget_pw.php" method="POST">
        <h3 class="form-heading">パスワードの再発行</h3>
        <div class="form-floating">
          <input class="form-control" type="email" id="floatingPassword" name="email" value="">
          <label for="floatingPassword">Eメールアドレス</label>
        </div>
        <button id="btn" class="w-100 btn btn-lg btn-primary" type="submit">送信</button>
        <p class="notes"><br><a href="./login.php" style="text-decoration: none; color:cornflowerblue">ログイン</a>へ戻る。</p>
        <p class="mt-5 mb-3 text-muted">&copy; kumihan.com</p>
      </form>
    <?php } ?>
  </main>

  <script src="https://kit.fontawesome.com/678cad97f5.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>