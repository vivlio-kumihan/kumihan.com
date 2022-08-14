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

$err_mesg = array();
$complete = false;

if ($_POST) {
  // POST情報がある場合の処理
  // 現在のパスワードについてのvalidation
  if (!$_POST['password']) {
    $err_mesg[] = '現在のパスワードを入力してください。';
  } elseif (mb_strlen($_POST['password']) > 17) {
    $err_mesg[] = 'パスワードは、16文字以内で入力してください。';
  }
  // 新しいパスワードについてのvalidation
  if (!$_POST['new_password']) {
    $err_mesg[] = '新しいパスワードを入力してください。';
  } elseif (mb_strlen($_POST['new_password']) > 17) {
    $err_mesg[] = 'パスワードは、16文字以内で入力してください。';
  }
  //  認証チェック
  $user_file = '../tmp/user_info.txt';
  if (file_exists($user_file)) {
    $users = file_get_contents($user_file);
    // 改行コード『\n』を目印に1行ごと配列に格納する。
    $users = explode("\n", $users);
    foreach ($users as $idx => $user) {
      // 入ってきた1行をCSV的に分解し（カンマを目印に）配列に格納する。
      $user_info = str_getcsv($user);
      // Eメールアドレスが一致しているかどうかを問う。
      // フォームからEメールアドレスはPOSTされていない。では、どうやって認証するか？
      // Eメールアドレスはsessionに保存されているのでそれを利用する。
      if ($user_info[0] === $_SESSION['email']) {
        // TRUEであれば現在のパスワードが一致しているかを問う。
        if (password_verify($_POST['password'], $user_info[1])) {
          // パスワーがが一致していれば、
          // 新しいパスワードのハッシュ値を生成する。
          $new_pw_hash = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
          // CSVの文字列を作るのならこちらでいいかもしれない。
          $line = "{$_SESSION['email']},{$new_pw_hash}";
          $users[$idx] = $line;
          // 配列の区切りに『\n（改行）』を入れ替えて文字列に変更する。
          $tmp_users = implode("\n", $users);
          // この文字列をもってファイルを全て上書きする。大胆な書き換えですな。
          $ret = file_put_contents($user_file, $tmp_users);
          // フォームの表示を枝分かれさせるための指標
          $complete = true;
          // 処理を終える。
          break;
          // ユーザー情報を変更する。
        }
      }
    }
    if (!$complete) {
      // このエラーメッセージ要るか？
      $err_mesg[] = '現在のパスワードが正しくありません。';
    }
  } else {
    // GETで情報がきた時の処理
    $_POST = array();
  }
}
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
      <?php if ($complete) { ?>
        <p>パスワードを変更しました。</p>
        <a href="./memberonly.php">インデックスページへ</a>
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
</body>

</html>