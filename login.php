<?php
require_once('../../tmp/conf.php');
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
  // Eメールアドレス
  // $_POSTに 'email'の値が無ければ、
  if (!$email) {
    $err_mesg[] = 'Eメールアドレスを入力してください。';
    // 100文字以上の入力があれば、
  } elseif (mb_strlen($email) > 100) {
    $err_mesg[] = '100文字以内でアドレスを入力してください。';
    // 入力されたEメールアドレスをvalidateしてみて不正であれば、
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $err_mesg[] = '入力されたEメールアドレスは不正です。';
  }
  // パスワード
  // $_POSTに'password'の値が無ければ、
  if (!$password) {
    $err_mesg[] = 'パスワードを入力してください。';
    // 17文字以上の入力があれば、
    // 要確認　0から9、a-zA-Z、-（ハイフン）、 _（アンダーバー）以外が入力されたらの条件も入れたい。
  } elseif (mb_strlen($password) > 17) {
    $err_mesg[] = 'パスワードは、16文字以内で入力してください。';
  }

  // DB接続に係る変数を生成
  $dsn = DNS;
  $user = DB_USER;
  $pwd = DB_PASSWORD;
  $dbh = new PDO($dsn, $user, $pwd);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  // $dbh = get_db_connect();

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
      // この分岐大切。ユーザーに優しくしようと思うのならやならいといけない分岐。
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
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
  <meta name="generator" content="Hugo 0.84.0">
  <title>ログイン</title>
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
    }
    ?>
    <form action="./login.php" method="POST">
      <h3 class="form-heading">ログイン</h3>

      <div class="form-floating">
        <input class="form-control" type="email" id="floatingInput" name="email" value="<?php echo forxss($_POST['email']) ?>">
        <label for="floatingInput">Eメールアドレス</label>
      </div>
      <div class="form-floating">
        <input class="form-control" type="password" id="floatingPassword" name="password" value="">
        <label for="floatingPassword">パスワード</label>
      </div>

      <!-- <div class="checkbox mb-3">
        <label>
          <input type="checkbox" value="remember-me"> Remember me
        </label> -->
      </div>
      <button id="btn" class="w-100 btn btn-lg btn-primary" type="submit">送信</button>

      <p class="notes">はじめての方は<br><a href="./register.php" style="text-decoration: none; color:cornflowerblue">メンバー登録</a>をお願いします。</p>
      <!-- <p class="notes">はじめての方は<br><a href="./register.php" style="text-decoration: none; color:cornflowerblue">メンバー登録</a>をお願いします。</p> -->
      <p class="notes"><a href="./forget_pw.php" style="text-decoration: none; color:cornflowerblue">パスワードを忘れた方</a><br>はこちらから。</p>

      <p class="mt-5 mb-3 text-muted">&copy; kumihan.com</p>
    </form>
  </main>

  <script src="https://kit.fontawesome.com/678cad97f5.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>