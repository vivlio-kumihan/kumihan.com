<!-- sign inのページ -->
<!-- HTMLを確認してからフォームへ入力された値の処理のコードを見てみる。 -->

<?php
require_once('./lib/function.php');

// HTMLを読んでみて、以下にインスタンスがある状態を理しした上でコードを読み進めてみる。
// $_POST
//   $_POST['name']
//   $_POST['email']
//   $_POST['password']
//   $_POST['confirm_password']

// エラーメッセージ対応。配列として初期化。
$err_mesg = array();

// POST情報が入ってきた場合の処理開始。
if ($_POST) {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $confirm_password = $_POST['confirm_password'];

  // 入力チェックをする。
  //   名前
  // $_POSTに 'name'の値が無ければ、
  if (!$name || mb_strlen($name) > 20) {
    $err_mesg[] = 'お名前を入力してください。';
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

//   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $db_name = 'quad9_db';
//     try {
//       $dsn = "mysql:dbname=quad9_db;host=mysql57.quad9.sakura.ne.jp;charset=utf8";
//       $user = "quad9";
//       $password = "Bf109tugumi";
//       $dbh = new PDO($dsn, $user, $password);
//     } catch (PDOException $e) {
//       // $eにエラーメッセージが含まれてたら、getMessage()で取り出して処理しますよという命令。
//       echo ("ここでか？　接続に失敗しました。" . $e->getMessage());
//       die();
//     }
//     $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//   }
  
//   // データ重複のチェック
//   try {
//     $sql = "SECECT COUNT(id) FROM menber WHERE name = :name";
//     $stmt = $dbh->prepare($sql);
//     $stmt->bindValue(':name', $name, PDO::PARAM_STR);
//     $stmt->excute();
//     $count = $stmt->FETCH(PDO::FETCH_ASSOC);
//     if ($count['COUNT(id)' > 0]) {
//       $err_mesg[] = '記入されたお名前は既に登録されています。';
//     }    
//   } catch (PDOException $e) {
//     echo ("接続に失敗しました。" . $e->getMessage());
//     die();
//   }
  
//   try {
//     $sql = "SECECT COUNT(id) FROM menber WHERE email = :email";
//     $stmt = $dbh->prepare($sql);
//     $stmt->bindValue(':email', email, PDO::PARAM_STR);
//     $stmt->excute();
//     $count = $stmt->FETCH(PDO::FETCH_ASSOC);
//     if ($count['COUNT(id)' > 0]) {
//       $err_mesg[] = '記入されたおEメールアドレスは既に登録されています。';
//     }    
//   } catch (PDOException $e) {
//     echo ("接続に失敗しました。" . $e->getMessage());
//     die();
//   }

//   // データの書き込み
//   $date = date('Y-m-d H:i:s');
//   $sql = "INSERT INTO member (`name`, `email`, `password`, `created`) VALUE (:name, :email:, :password, '{$date}')";
//   $stmt = $dbh->prepare($sql);
//   $stmt->bindValue(':name', $name, PDO::PARAM_STR);
//   $stmt->bindValue(':email', $email, PDO::PARAM_STR);
//   $stmt->bindValue(':password', $password, PDO::PARAM_STR);
//   if (!$stmt->execute()) {
//     return 'データの書き込みに失敗しました。';
//   }

//   // 登録を済ませたので、ログイン画面へメンバーページへリダイレクトする。
//   if (!$err_mesg) {
//     $host = $_SERVER['HTTP_HOST'];
//     $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
//     header("Location: //$host$uri/member.php");
//     exit;
//   }
// } else {
//   // GETの時の処理
//   // メールアドレスは正しく、パスワードに不正があって再度入力しなければいけない場合、
//   // 入力欄が全てリセットされてしまう。
//   // ユーザーの利便性を考えて、メールアドレスは残してパスワードだけ入力を促す画面構成にするため、
//   // 『form』の『input属性value』に『echo htmlspecialchars($_POST['email'])』とする。
//   // そうすると、GET時に初回の入力で『$_POST['email']』て定義されていないとPHの警がが出る。
//   // 回避策として変数を初期化しておく。
//   $_POST = array();
//   $_POST['name'] = '';
//   $_POST['email'] = '';
//   $_POST['password'] = '';
// }
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
    </div>
  </div>
</body>