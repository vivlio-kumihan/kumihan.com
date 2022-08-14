<?php
// print_r($_SERVER);

// セッション開始
// ログインフォームでの認証を経てWebPage内へ入場する。
// その際セッションをスタートする宣言をする。
// 一回入ったらブラウザを閉じるまでログインの認証は要らない、自由に往来できる切符を渡すようなイメージ。
// 閲覧できる各ページには、この切符を最初の行に貼り付けてある。
session_start();
// 初期化
$err_mesg = array();
// register.phpでやった処理。
// 初級者にわかりやすさ優先の方針だからだろう。処理の流れをおぼえることを優先しましょう。
// POST情報がある場合の処理
if ($_POST) {
  // 説明省略。register.php参照する。
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
  //  認　証
  $user_file = '../tmp/user_info.txt';
  if (file_exists($user_file)) {
    $users = file_get_contents($user_file);
    $users = explode("\n", $users);
    foreach ($users as $user) {
      $user_info = str_getcsv($user);
      if ($user_info[0] === $_POST['email']) {
        if (password_verify($_POST['password'], $user_info[1])) {
          $_SESSION['email'] = $_POST['email'];
          $host = $_SERVER['HTTP_HOST'];
          $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
          header("Location: //$host$uri/index.php");
          exit;
        }
      }
    }
    // このエラーメッセージ要るか？
    // $err_mesg[] = 'ユーザー名またはパスワードが一致しませんでした。';
  } else {
    // GETの時の処理
    // 初回アクセスで既にsessionの切符を持っている状態であれば、login手続きを通過させてindex.phpへ通す。
    // 連想配列『$_SESSION['email']』要素があり、その内容が存在しているならばリダイレクトさせる。
    if (isset($_SESSION['email']) && $_SESSION['email']) {
      // リダイレクト処理
      $host = $_SERVER['HTTP_HOST'];
      $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
      header("Location: //$host$uri/index.php");
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
  <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
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
      <p style="margin-top: 20px; size: 0.8em">はじめての方は、<a href="./register.php" style="text-decoration: none; color:cornflowerblue">Sign in</a>をお願いします。</p>
    </div>
  </div>
</body>

</html>