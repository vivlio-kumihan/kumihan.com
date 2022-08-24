<?php
// 方針として、
// 自作関数をプロジェクトに『lib（ライブラリー）』ディレクトリを設置
// DB関連の秘匿情をwww以外の階層で管理する。
require_once('../tmp/conf.php');
require_once('./lib/function.php');

// エラーメッセージ対応。配列として初期化。
$err_mesg = array();

// POST情報が入ってきた場合の処理開始。
if ($_POST) {
  // 自作関数でフォームから入力されたPOSTの中身を変数に格納する。
  $name = get_post('name');
  $email = get_post('email');
  $password = get_post('password');
  $confirm_password = get_post('confirm_password');
  // DB接続に係る変数を生成
  // 課題　ライブラリかよ呼び出してDBのインスタンを生成出来るようにする。
  $dsn = DNS;
  $user = DB_USER;
  $pwd = DB_PASSWORD;
  // PHPからSQLを使ってDBを操るための肝の部分。
  $dbh = new PDO($dsn, $user, $pwd);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // 入力チェックをする。
  //   名前
  // $_POSTに 'name'の値が無ければ、
  if (!$name || mb_strlen($name) > 20) {
    $err_mesg[] = 'お名前を入力してください。';
    // DBに既に登録されている氏名の場合にエラを出す処理をする箇所。
  } elseif ($name) {
    try {
      // 任意の値で検索してヒットした件数を手がかりに、
      // 値の重複を回避するコード
      $sql = "SELECT COUNT(id) FROM `member` WHERE `name` = :name";
      $stmt = $dbh->prepare($sql);
      $stmt->bindValue(':name', $name, PDO::PARAM_STR);
      $stmt->execute();
      $count = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($count['COUNT(id)'] > 0) {
        $err_mesg[] = '記入されたお名前は既に登録されています。';
      }
    } catch (PDOException $e) {
      echo ("接続に失敗しました。" . $e->getMessage());
      die();
    }
  }
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

  // このifが大切。ここでifで分岐させないと値の重複を感知しても、
  // そのまま素通りでDBと登録されてしまう。
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
    // 登録を済ませたので、ログイン画面へメンバーページへリダイレクトする。
    $host = $_SERVER['HTTP_HOST'];
    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    header("Location: //$host$uri/member.php");
    exit;
  }
} else {
  // GETの時の処理
  // メールアドレスは正しく、パスワードに不正があって再度入力しなければいけない場合、
  // 入力欄が全てリセットされてしまう。
  // ユーザーの利便性を考えて、メールアドレスは残してパスワードだけ入力を促す画面構成にするため、
  // 『form』の『input属性value』に『echo htmlspecialchars($_POST['email'])』とする。
  // そうすると、GET時に初回の入力で『$_POST['name']』『$_POST['email']』て定義されていないとPHPの警告が出るらしい。
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
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>メンバー登録</title>
  <style>
    .submit {
      text-align: center;
    }
  </style>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
  <div class="container">
    <div class="mx-auto" style="margin-top:50px; width: 450px;">
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
      <h3 style="text-align: center;">メンバー登録</h3>
      <!-- POSTで自分自身（register.php）へインスタンスを投げるフォームの宣言。 -->
      <form action="./register.php" method="POST">
        <div class="mb-3">
          <label class="form-label">お名前</label>
          <!-- valueに入力した値をいれておき、再入力の際のユーザーの入力の手間を省く。-->
          <!-- 値はXSS対策のためHTMLエスケープしておく。 -->
          <input class="form-control" type="text" name="name" value="<?php echo forxss($_POST['name']) ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Eメールアドレス</label>
          <input class="form-control" type="email" name="email" value="<?php echo forxss($_POST['email']) ?>">
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
      <p style="margin-top: 20px; size: 0.8em; text-align: center;"><a href="./index.php" style="text-decoration: none; color:cornflowerblue">サイトトップ</a>に移動する。</p>
    </div>
  </div>
</body>