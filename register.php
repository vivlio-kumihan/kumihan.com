<?php
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
  // 確認
  if ($_POST['password'] !== $_POST['confirm_password']) {
    $err_mesg[] = '確認用に入力されたパスワードが一致しません。';
  }
  //  ユーザーの重複
  $user_file = '../tmp/user_info.txt';
  // このパスのファイルが存在していればTRUEを返す関数
  if (file_exists($user_file)) {
    // ファイルの中身をインスタンスにしてとってくる関数
    $users = file_get_contents($user_file);
    // 指定した記号で要素に分割し配列に格納する関数
    $users = explode("\n", $users);
    foreach ($users as $user) {
      // 文字列をCSVとしてインスタンスにする関数
      $user_info = str_getcsv($user);
      if ($user_info[0] === $_POST['email']) {
        $err_mesg[] = '入力されたEメールアドレスはすでに登録されています。';
        break;
      }
    }
  }

  // 2. 新規ユーザ登録処理
  if (!$err_mesg) {
    $pw_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
    // CSVの文字列を作るのならこちらでいいかもしれない。
    $line = "{$_POST['email']},{$pw_hash}\n";
    // $line = '"'.$_POST['email'].'","'.$pw_hash.'"'."\n"; // 一応置いておく。
    $ret = file_put_contents($user_file, $line, FILE_APPEND);
  }

  // 3. 入力チェック後に問題がなければログイン画面にリダイレクトする。
  if (!$err_mesg) {
    $host = $_SERVER['HTTP_HOST'];
    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    header("Location: //$host$uri/login.php");
    exit;
  }
} else {
  // GETの時の処理
  // メールアドレスは正しく、パスワードに不正があって再度入力しなければいけない場合、
  // 入力欄が全てリセットされてしまう。
  // ユーザーの利便性を考えて、メールアドレスは残してパスワードだけ入力を促す画面構成にするため、
  // 『form』の『input属性value』に『echo htmlspecialchars($_POST['email'])』とする。
  // そうすると、GET時に初回の入力で『$_POST['email']』て定義されていないとPHの警がが出る。
  // 回避策として変数を初期化しておく。
  $_POST = array();
  $_POST['email'] = '';
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>新規登録</title>
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
      // 「implode」エラーメッセージを文字列にする関数に「br」で改行して出力する。
      echo implode('<br>', $err_mesg);
      echo '</div>';
    }
    ?>
      <form action="./register.php" method="post">
        <div class="mb-3">
          <label class="form-label">Eメールアドレス</label>
          <input class="form-control" type="email" name="email" value="<?php echo htmlspecialchars($_POST['email']) ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">パスワード</label>
          <input class="form-control" type="password" name="password" value="">
        </div>
        <div class="mb-3">
          <label class="form-label">パスワード(確認)</label>
          <input class="form-control" type="password" name="confirm_password" value="">
        </div>
        <div class="submit">
          <button type="submit" class="btn btn-primary btn-sm" value="新規登録">新規登録</button>
        </div>
      </form>
    </div>
  </div>
</body>

</html>