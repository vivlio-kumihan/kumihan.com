<!-- session切符を持っていないことが前提になる。 -->

<?php
require_once('../tmp/conf.php');
require_once('./lib/function.php');
// 初期化
$err_mesg = array();
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
  } else {
    // DB接続に係る変数を生成
    $dsn = DNS;
    $user = DB_USER;
    $pwd = DB_PASSWORD;
    $dbh = new PDO($dsn, $user, $pwd);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {
      $sql = "SELECT * FROM member WHERE email = :email LIMIT 1";
      $stmt = $dbh->prepare($sql);
      $stmt->bindValue(':email', $email, PDO::PARAM_STR);
      $stmt->execute();
      // 入力したEメールアドレス『$_POST['email']』でDBを検索し、1件のヒットがあったら、
      if ($stmt->rowCount() > 0) {
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        // ================ 重要 =================
        // 再発行するパスワードを生成する。
        $tmp_pw = bin2hex(random_bytes(5));
        // ================ 重要 =================
        // サーバーにメールを送信させる命令。
        // 日本語の送信で文字化けが起こる場合、mail.phpを参照する。
        $mesg = "パスワードを変更しました。\r\n新パスワード => ".$tmp_pw."\r\n";
        mail($email, 'パスワードの再発行いたしました。', $mesg);
        // パスワードハッシュをかける。
        $hashed_tmp_pw = password_hash($tmp_pw, PASSWORD_DEFAULT);
        // 該当のデータをアップデートする。
        $sql = "UPDATE member SET password = :password WHERE {$data['id']}";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':password', $hashed_tmp_pw, PDO::PARAM_STR);
        $stmt->execute();
        $mesg[] = "パスワードを登録されている、あなたのEメールアドレス宛に送信しました。";
        $complete = true;
      } else {
        $err_mesg[] = '登録されたパスワードと違います。';
        $err_mesg[] = 'パスワードを<a href="./logout.php">再発行</a>されますか？';
      }
    } catch (PDOException $e) {
      print("接続に失敗しました。" . $e->getMessage());
      die();
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
  //   $host = $_SERVER['HTTP_HOST'];
  //   $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
  //   header("Location: //$host$uri/member.php");
  //   exit;
  // }
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
      } elseif ($mesg) {
        echo '<div class="alert alert-success" role="alert">';
        echo implode('<br>', $mesg);
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
            <input class="form-control" type="email" name="email" value="<?php echo forxss($_POST['email']) ?>">
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