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
    $sql = "SELECT * FROM user WHERE email = :email LIMIT 1";
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':email', $_SESSION['email'], PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
      $data = $stmt->fetch(PDO::FETCH_ASSOC);
      if (password_verify($password, $data['password'])) {
        // 新しいパスワードのハッシュ値を生成する。
        $hashed_new_pw = password_hash($new_password, PASSWORD_DEFAULT);
        $sql = "UPDATE member SET password = :hashed_new_pw WHERE $data['id']";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':hashed_new_pw', $hashed_new_pw, PDO::PARAM_STR);
        $stmt->execute();
      } else {
        echo '登録されたパスワードと違います。';
      }
    }
  } catch (PDOException $e) {
    print("接続に失敗しました。" . $e->getMessage());
    die();
  } else {
    // GETで情報がきた時の処理
    $_POST = array();
  }
}
  
if (!$complete) {
  // このエラーメッセージ要るか？
  $err_mesg[] = '現在のパスワードが正しくありません。';
}

try {
  $dbh = new PDO($dsn, $user, $password);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "UPDATE `user` SET `email` = :email WHERE id = 2";
  $stmt = $dbh->prepare($sql);
  $stmt->bindValue(':email', $email, PDO::PARAM_STR);
  $stmt->execute();
  echo '処理が終了しました。';
} catch (PDOException $e) {
  print("接続に失敗しました。" . $e->getMessage());
  die();
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
  <script src="https://kit.fontawesome.com/678cad97f5.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>