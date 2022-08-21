<!-- このページへのリンクはloginページに設置しておく。 -->
<!-- session切符を持っていないことが前提になる。 -->

<?php
// 初期化
$err_mesg = array();
// 初期状態でEメールアドレスを入力するフォームを出しておきたいがための真偽値をここで設定する。
$complete = false;

// POSTでEメールアドレスが投げられる。
if ($_POST) {
  // 入力チェック。
  // Eメールアドレス
  if (!$_POST['email']) {
    $err_mesg[] = 'Eメールアドレスを入力してください。';
    // 100文字以上の入力があれば、
  } elseif (mb_strlen($email) > 100) {
    $err_mesg[] = '100文字以内のアドレスを入力してください。';
    // 入力されたEメールアドレスをvalidateしてみて不正であれば、
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $err_mesg[] = '入力されたEメールアドレスは不正です。';
  } elseif ($email) {
    // DB接続に係る変数を生成
    $dsn = "mysql:dbname=quad9_db;host=mysql57.quad9.sakura.ne.jp;charset=utf8";
    $user = "quad9";
    $pwd = "PASSWORDTODB";
    $dbh = new PDO($dsn, $user, $pwd);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {
      $sql = "SELECT COUNT(id) FROM `member` WHERE `email` = :email";
      $stmt = $dbh->prepare($sql);
      $stmt->bindValue(':email', $email, PDO::PARAM_STR);
      $stmt->execute();
      $count = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($count['COUNT(id)'] > 0) {
        // ================ 重要 =================
        // 入力したEメールアドレス『$_POST['email']』が『user_info.txt』に記載されていれば、
        // 再発行するパスワードを生成する。
        $new_pw = bin2hex(random_bytes(5));
        // ================ 重要 =================
        // サーバーにメールを送信させる命令。
        // 日本をを送信で文字化けが起こる場合、mail.phpを参照する。
        $mesg = "パスワードを変更しました。\r\n" . $pw . "\r\n";
        mail($_POST['email'], 'パスワードの再発行について', $mesg);
        // user_info.txt（後のDB）の全文変更作業の開始。
        $pw_hash = password_hash($pw, PASSWORD_DEFAULT);
      }
    } catch (PDOException $e) {
      echo ("接続に失敗しました。" . $e->getMessage());
      die();
    }
  }

  //  認証チェック
  $user_file = '../tmp/user_info.txt';
  if (file_exists($user_file)) {
    $users = file_get_contents($user_file);
    $users = explode("\n", $users);
    foreach ($users as $idx => $user) {
      $user_info = str_getcsv($user);
      // Eメールアドレスが一致しているかどうかを問い。
      if ($user_info[0] === $_POST['email']) {


        // CSVの文字列を作るのならこちらでいいかもしれない。
        $line = "{$_POST['email']},{$pw_hash}";
        // $line = '"'.$_POST['email'].'","'.$pw_hash.'"'; // 一応置いておく。
        $users[$idx] = $line;
        // 配列の区切りに『\n（改行）』を入れ替えて文字列に変更する。
        $tmp_users = implode("\n", $users);
        // この文字列をもってファイルを全て上書きする。大胆な書き換えですな。
        $ret = file_put_contents($user_file, $tmp_users);
        // 処理が上手くいった場合、ここで処理を完了したい。
        // 処理が不良だった場合、エラーメッセージを出すことを想定した変数の生成。
        // 変数に『true』を代入する場合。初期化で代入する値『false』
        $complete = true;
        // 処理を終える。
        break;
      }
    }
    // falseではない => trueであれば、メッセージを変数に代入する。
    //そそもそも、このエラーメッセージ要るか？
    if (!$complete) {
      $err_mesg[] = 'Eメールアドレスが不正です。';
    }
  } else {
    // GETの時の処理
    // 確認　この処理は不要では？
    // if (isset($_SESSION['email']) && $_SESSION['email']) {
    //   // リダイレクト処理（これは1行じゃ無理）をする。
    //   $host = $_SERVER['HTTP_HOST'];
    //   $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    //   header("Location: //$host$uri/memberonly.php");
    //   exit;
    // }
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
  <title>パスワードの再発行</title>
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
      <!-- 初期状態で入力欄を配置する。 -->
      <!-- 再発行処理が完了しているかの真偽は変数$completeで判断する。 -->
      <?php if ($complete) { ?>
        <p>登録されているEメールアドレス宛にパスワードを再発行しました。</p>
        <a href="./login.php">ログインページへ</a>
      <?php } else { ?>
        <!-- POSTで自分自身にインスタンス（Eメールアドレス）を投げる。 -->
        <form action="./forget_pw.php" method="POST">
          <div class="mb-3">
            <label class="form-label">Eメールアドレス</label>
            <input class="form-control" type="email" name="email" value="<?php echo htmlspecialchars($_POST['email']) ?>">
          </div>
          <div class="submit">
            <button type="submit" class="btn btn-primary btn-sm" value="再発行">再発行</button>
          </div>
        </form>
      <?php } ?>
    </div>
  </div>
</body>

</html>