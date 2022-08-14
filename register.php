<!-- sign inのページ -->
<!-- HTMLを確認してからフォームへ入力された値の処理のコードを見てみる。 -->

<?php
// HTMLを読んでみて、以下にインスタンスがある状態を理しした上でコードを読み進めてみる。
// $_POST
//   $_POST['email']
//   $_POST['password']
//   $_POST['confirm_password']

// エラーメッセージ対応。配列として初期化。
$err_mesg = array();

// POST情報が入ってきた場合の処理開始。
if ($_POST) {

  // 入力チェックをする。
  //   Eメールアドレス
  // $_POSTに 'email'の値が無ければ、
  if (!$_POST['email']) {
    $err_mesg[] = 'Eメールアドレスを入力してください。';
  // 100文字以上の入力があれば、
  } elseif (mb_strlen($_POST['email']) > 100) {
    $err_mesg[] = '100文字以内で入力してください。';
  // 入力されたEメールアドレスをvalidateしてみて不正であれば、
  } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $err_mesg[] = '入力されたEメールアドレスは不正です。';
  }
  //   パスワード
  // $_POSTに 'password'の値が無ければ、
  if (!$_POST['password']) {
    $err_mesg[] = 'パスワードを入力してください。';
  // 17文字以上の入力があれば、
  // 要確認　0から9、a-zA-Z、-（ハイフン）、 _（アンダーバー）以外が入力されたらの条件も入れたい。
  } elseif (mb_strlen($_POST['password']) > 17) {
    $err_mesg[] = 'パスワードは、16文字以内で入力してください。';
  }
  //   確　認
  // パスワードと確認用の入力が違ったら、
  if ($_POST['password'] !== $_POST['confirm_password']) {
    $err_mesg[] = '確認用に入力されたパスワードが一致しません。';
  }
  //   ユーザーの重複
  // 擬似DBの操作　ファイル操作
  // 機密書類の置き場所が外部からは接続できない一つ上の階層にして管理。
  $user_file = '../tmp/user_info.txt';
  // このパスのファイルが存在していればTRUEを返す関数で条件分岐する。
  if (file_exists($user_file)) {
    // ファイルの中身をインスタンスにしてとってくる関数を充てる。
    $users = file_get_contents($user_file);
    // 指定した記号で要素に分割し配列に格納する関数を充てる。
    // 要確認　文字列 => 配列 『explode』
    $users = explode("\n", $users);
    foreach ($users as $user) {
      // 文字列をCSVとして認識し、カンマ区切りで配列のインスタンスを生成する関数を充てる。
      $user_info = str_getcsv($user);
      // ここで真偽を確かめる。
      if ($user_info[0] === $_POST['email']) {
        $err_mesg[] = '入力されたEメールアドレスはすでに登録されています。';
        // foreachで回して該当する結果がなければここでサクッと処理を終える。
        break;
      }
    }
  }

  // 上記入力チェッをを無事終えて以下の新規ユーザ登録処理に入る。
  // 新規ユーザ登録処理
  if (!$err_mesg) {
    // Sha1とかMD5とかいろいろあったけど過去の話。この1行で安全なパスワード管理のためのハッシュ化をする。
    $pw_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
    // 擬似DBにスプールする文字列の生成
    // CSVの文字列を作るのならこちらでいいかもしれない。
    $line = "{$_POST['email']},{$pw_hash}\n";
    // $line = '"'.$_POST['email'].'","'.$pw_hash.'"'."\n"; // 一応置いておく。
    // ファイルの最後に新規登録ユーザーの情報を追加するコード。たった1行で済ませる。素敵だ。
    // 上村さんはこういうとことでインスタンスを変数に格納するのが好きよようだが…
    // 私は不要だと思う。
    $ret = file_put_contents($user_file, $line, FILE_APPEND);
  }

  // 登録を済ませたので、ログイン画面へリダイレクトする。
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
          <label class="form-label">Eメールアドレス</label>
          <!-- valueに入力した値をいれておき、再入力の際のユーザーの入力の手間を省く。-->
          <!-- 値はXSS対策のためHTMLエスケープしておく。 -->
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