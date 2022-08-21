<?php
require_once('./lib/function.php');

// セッション開始
// ログインフォームでの認証を経てWebPage内へ入場する。
// その際セッションをスタートする宣言をする。
// 一回入ったらブラウザを閉じるまでログインの認証は要らない、自由に往来できる切符を渡すようなイメージ。
// 閲覧できる各ページには、この切符を最初の行に貼り付けてある。
session_start();

// エラーメッセージ対応。配列として初期化。
$err_mesg = array();

// POST情報が入ってきた場合の処理開始。
if ($_POST) {
  $email = $_POST['email'];
  $password = $_POST['password'];

  // 入力チェックをする。
  //   Eメールアドレス
  // $_POSTに 'email'の値が無ければ、
  if (!$email) {
    $err_mesg[] = 'Eメールアドレスを入力してください。';
    // 100文字以上の入力があれば、
  } elseif (mb_strlen($email) > 100) {
    $err_mesg[] = '100文字以内のアドレスを入力してください。';
    // 入力されたEメールアドレスをvalidateしてみて不正であれば、
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $err_mesg[] = '入力されたEメールアドレスは不正です。';
  }
  //   パスワード
  // $_POSTに 'password'の値が無ければ、
  if (!$password) {
    $err_mesg[] = 'パスワードを入力してください。';
    // 17文字以上の入力があれば、
    // 要確認　0から9、a-zA-Z、-（ハイフン）、 _（アンダーバー）以外が入力されたらの条件も入れたい。
  } elseif (mb_strlen($password) > 17) {
    $err_mesg[] = 'パスワードは、16文字以内で入力してください。';
  }

  // DB接続に係る変数を生成
  $dsn = "mysql:dbname=quad9_db;host=mysql57.quad9.sakura.ne.jp;charset=utf8";
  $user = "quad9";
  $pwd = "";
  $dbh = new PDO($dsn, $user, $pwd);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  try {
    $sql = "SELECT * FROM `member` WHERE `email` = :email LIMIT 1";
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
      $data = $stmt->fetch(PDO::FETCH_ASSOC);
      if (password_verify($password, $data['password'])) {
        $_SESSION['email'] = $email;
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location: //$host$uri/member.php");
        exit;
      } else {
        $err_mesg[] = 'ログイン情報が間違っています。再入力をお願いします。';
      }
    } else {
      $err_mesg[] = 'ログイン情報が間違っています。再入力をお願いします。';
    }
  } catch (PDOException $e) {
    echo ("接続に失敗しました。" . $e->getMessage());
    die();
  }
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
    <div class="mx-auto" style="margin-top:150px; width: 450px;">
      <?php
      if ($err_mesg) {
        echo '<div class="alert alert-danger" role="alert">';
        echo implode('<br>', $err_mesg);
        echo '</div>';
      }
      ?>
      <h3 style="text-align: center;">ログイン</h3>
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
      <p style="margin-top: 20px; size: 0.8em; text-align: center;">はじめての方は<a href="./register.php" style="text-decoration: none; color:cornflowerblue">メンバー登録</a>をお願いします。</p>
      <p style="margin-top: 20px; size: 0.8em; text-align: center;"><a href="./register.php" style="text-decoration: none; color:cornflowerblue">パスワードを忘れた方</a>はこちらから。</p>
    </div>
  </div>
</body>

</html>