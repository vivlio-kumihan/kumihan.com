<?php
// 方針として、
// 自作関数をプロジェクトに『lib（ライブラリー）』ディレクトリを設置
// DB関連の秘匿情をwww以外の階層で管理する。
require_once('./conf.php');
require_once('./function.php');
// エラーメッセージ対応。配列として初期化。
$err_mesg = array();

// POST情報が入ってきた場合の処理開始。
if ($_POST) {
  echo "postした";
  // 自作関数でフォームから入力されたPOSTの中身を変数に格納する。
  $name = get_post('name');
  echo $name;
  $email = get_post('email');
  echo $email;
  $password = get_post('password');
  echo $password;
  $confirm_password = get_post('confirm_password');
  echo $confirm_password;
  try {
    $dsn = DNS;
    echo $dsn;
    $user = DB_USER;
    echo $user;
    $pwd = DB_PASSWORD;
    echo $pwd;
    $dbh = new PDO($dsn, $user, $pwd);
    

  } catch (PDOException $e) {
    // $eにエラーメッセージが含まれてたら、getMessage()で取り出して処理しますよという命令。
    echo ("接続に失敗しました。" . $e->getMessage());
    die();
  }
  echo "$dbhを通過前";
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  echo "$dbhを通過後";

  // $dbh = get_db_connect();

  // 入力チェックをする。
  //   名前
  // $_POSTに 'name'の値が無ければ、
  if (!$name || mb_strlen($name) > 20) {
    $err_mesg[] = 'お名前を入力してください。';
    // DBに既に『氏名』が登録されているの場合にエラーを出す処理をする箇所。
  } elseif ($name) {
    try {
      $res = name_exists($dbh, $name);
      if ($res) {
        $err_mesg[] = '記入されたお名前は既に登録されています。';
      }
    } catch (PDOException $e) {
      echo ("接続に失敗しました。" . $e->getMessage());
      die();
    }
  }

  echo "name ok";
  


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
    // 氏名の時と理屈・やり方は同じ。
    // 課題　リファクタリングの対象。
  } elseif ($email) {
    try {
      $sql = "SELECT COUNT(id) FROM `member` WHERE `email` = :email";
      $stmt = $dbh->prepare($sql);
      $stmt->bindValue(':email', $email, PDO::PARAM_STR);
      $stmt->execute();
      $count = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($count['COUNT(id)'] > 0) {
        $err_mesg[] = '記入されたEメールアドレスは既に登録されています。';
      }
    } catch (PDOException $e) {
      echo ("接続に失敗しました。" . $e->getMessage());
      die();
    }
  }

  echo "email ok";


  //   パスワード
  // $_POSTに 'password'の値が無ければ、
  if (!$password) {
    $err_mesg[] = 'パスワードを入力してください。';
    // 17文字以上の入力があれば、
    // 要確認　0から9、a-zA-Z、-（ハイフン）、 _（アンダーバー）以外が入力されたらの条件も入れたい。
  } elseif (mb_strlen($password) > 17) {
    $err_mesg[] = 'パスワードは、16文字以内で入力してください。';
  }
  //   確　認
  // パスワードと確認用の入力が違ったら、
  if (!$confirm_password) {
    $err_mesg[] = '確認用にパスワードを入力してください。';
  } elseif ($password !== $confirm_password) {
    $err_mesg[] = '確認用に入力されたパスワードが一致しません。';
  }
  // パスワーを暗号化する。
  $password = password_hash($password, PASSWORD_DEFAULT);

  echo "pw ok";
  // このifが大切。ここでifで分岐させないと値の重複を感知しても、
  // そのまま素通りでDBと登録されてしまう。


  //////////////////////////////////////////////////////  質問
  // ここのsqlに入っているカッコ　なぜ前のsqlにはないのか？
  // :変数名の理解が不十分
  // foreachで回せるが短いから意味なし？
  if (!$err_mesg) {
    try {
      $date = date('Y-m-d H:i:s');
      $sql = "INSERT INTO `member`(`name`, `email`, `password`, `created`) VALUES (:name, :email, :password, '{$date}')";
      $stmt = $dbh->prepare($sql);
      $stmt->bindValue(':name', $name, PDO::PARAM_STR);
      $stmt->bindValue(':email', $email, PDO::PARAM_STR);
      $stmt->bindValue(':password', $password, PDO::PARAM_STR);
      $stmt->execute();
    } catch (PDOException $e) {
      echo ("接続に失敗しました。" . $e->getMessage());
      die();
    }

    ////////////////////////////////////////////////////  質問
    // '/\\'は何か？
    // exit;必要なところと不要なところ　何で区別してる？
    // リダイレクトする処理はこれが最適か？　であれば関数化したい。
    
    // 登録を済ませたので、ログイン画面へメンバーページへリダイレクトする。
    $host = $_SERVER['HTTP_HOST'];
    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    header("Location: //$host$uri/member.php");
    exit;
  }
} else {
  // GETの時の処理
  // メールアドレスは正しく、パスワードに不正があって
  // 再度入力しなければいけない場合、入力欄が全てリセット
  // されてしまう。
  // ユーザーの利便性を考えて、メールアドレスは残して
  // パスワードだけ入力を促す画面構成にする。
  // 『form』の『input属性value』に
  // 『echo htmlspecialchars($_POST['email'])』とする。
  
  //////////////////////////////////////////////////////  質問
  // 入ががあってDBに値が通信されるまでどこにもXSS対策がないように見える。
  // 22–25行の変数の初期化時にXSS対策のコードを差し込んだらいいのではないか？

  // GET時に初回の入力で
  // 『$_POST['name']』『$_POST['email']』を
  // 空にしておかないとPHPの警告が出るらしい。
  // 回避策として変数を初期化しておく。
  $_POST = array();
  $_POST['name'] = '';
  $_POST['email'] = '';
  $_SESSION = array();
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
    // 要確認　HTML内のPHPコードのエスケープのやり方を確認する。
    if ($err_mesg) {
      echo '<div class="alert alert-danger" role="alert">';
      // $err_mesgに中に格納されているエラーメッセージは配列の状態になっていて、
      // 関数「implode」を使って1行ずつ「br」で改行しながら出力させているのだと思う。
      // 要確認　配列を1行ずつ吐き出す 『implode』
      echo implode('<br>', $err_mesg);
      echo '</div>';
    }
    ?>
    <!-- POSTで自分自身（register.php）へインスタンスを投げるフォームの宣言。 -->
    <form action="./register.php" method="POST">
      <h3 class="form-heading">メンバー登録</h3>
      <div class="form-floating">
        <input class="form-control" type="text" id="floatingInput" name="name" value="<?php echo forxss($_POST['name']) ?>">
        <label for="floatingInput">お名前</label>
      </div>
      <div class="form-floating">
        <input class="form-control" type="email" id="floatingInput" name="email" value="<?php echo forxss($_POST['email']) ?>">
        <label for="floatingInput">Eメールアドレス</label>
      </div>
      <div class="form-floating">
        <input class="form-control" type="password" id="floatingPassword" name="password" value="">
        <label for="floatingPassword">パスワード</label>
      </div>
      <div class="form-floating">
        <input class="form-control" type="password" id="floatingPassword" name="confirm_password" value="">
        <label for="floatingPassword">パスワード(確認)</label>
      </div>

      <button id="btn" class="w-100 btn btn-lg btn-primary" type="submit">送信</button>

      <p class="notes"><a href="./index.php" style="text-decoration: none; color:cornflowerblue">サイトトップ</a>に移動する。</a></p>

      <p class="mt-5 mb-3 text-muted">&copy; kumihan.com</p>
    </form>
  </main>
</body>

</html>