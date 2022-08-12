<?php
// print_r($_SERVER);
// sessionを始める宣言をする。たった1行。
session_start();
$err_mesg = array();
if ($_POST) {
  // POST情報がある場合の処理
  // 1. 入力チェック。
  // Eメールアドレス
  if (!$_POST['email']) {
    $err_mesg[] = 'Eメールアドレスを入力してください。';
  } elseif (mb_strlen($_POST['email']) > 100) {
    $err_mesg[] = '100文字以内で入力してください。';
  } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $err_mesg[] = '入力されたEメールアドレスは不正です。';
  }
  // パスワード
  if (!$_POST['password']) {
    $err_mesg[] = 'パスワードを入力してください。';
  } elseif (mb_strlen($_POST['password']) > 17) {
    $err_mesg[] = 'パスワードは、16文字以内で入力してください。';
  }
  //  認証チェック
  $user_file = '../tmp/user_info.txt';
  if (file_exists($user_file)) {
    $users = file_get_contents($user_file);
    $users = explode("\n", $users);
    foreach ($users as $user) {
      $user_info = str_getcsv($user);
      // Eメールアドレスが一致しているかどうかを問い。
      if ($user_info[0] === $_POST['email']) {
        // TRUEであればパスワードが一致しているかを問う。
        if (password_verify($_POST['password'], $user_info[1])) {
          // Eメールアドレス、パスワードとも認証が通ったので、sessionをmemberonly.phpへここで渡す。
          $_SESSION['email'] = $_POST['email'];
          // 一致していると、リダイレクトしてメンバ画面へリダイレクトする処理をする。
          $host = $_SERVER['HTTP_HOST'];
          $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
          header("Location: //$host$uri/memberonly.php");
          exit;
        }
      }
    }
    // このエラーメッセージ要るか？
    // $err_mesg[] = 'ユーザー名またはパスワードが一致しませんでした。';
  } else {
    // GETの時の処理
    // 初回アクセスですでにsessionを持っている状態であればloginを通過させてmemberonlyへ行かせる。
    // 連想配列『$_SESSION['email']』要素があり、その内容が存在しているならば。。。という意味。
    if (isset($_SESSION['email']) && $_SESSION['email']) {
      // リダイレクト処理（これは1行じゃ無理）をする。
      $host = $_SERVER['HTTP_HOST'];
      $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
      header("Location: //$host$uri/memberonly.php");
      exit;
    }
    $_POST = array();
    $_POST['email'] = '';
  }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ログインフォーム</title>
  <style>
    .submit {
      text-align: center;
    }
  </style>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
  <div class="container">
    <div class="mx-auto" style="margin-top:150px; width: 400px;">
      <?php
      if ($err_mesg) {
        echo '<div class="alert alert-danger" role="alert">';
        echo implode('<br>', $err_mesg);
        echo '</div>';
      }
      ?>
      <form action="./login.php" method="post">
        <div class="mb-3">
          <label class="form-label">Eメールアドレス</label>
          <input class="form-control" type="email" name="email" value="<?php echo htmlspecialchars($_POST['email']) ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">パスワード</label>
          <input class="form-control" type="password" name="password" value="">
        </div>
        <div class="submit">
          <button type="submit" class="btn btn-primary btn-sm" value="送信">送信</button>
        </div>
      </form>
    </div>
  </div>
</body>

</html>