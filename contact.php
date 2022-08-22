<?php
// セッション開始
session_start();
// セッションの切符も持っていない訪問者にログインページへリダイレクト処理。
if (!$_SESSION['email']) {
  $host = $_SERVER['HTTP_HOST'];
  $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
  header("Location: //$host$uri/login.php");
  exit;
}

// 一つのファイルで複数の出力を実現させるための方法。
// GET、POSTで行き先を振り分ける。

// 関数解説 isseet()
// isset($_POST(key)) => is set ? => keyは設定されているか？
// 配列に当該のkeyが設定されているのか？　かつ、keyに値は格納されているのか？
$toward = 'input';
if (isset($_POST['back']) && $_POST['back']) {
  // 何もしない
  // POSTに『confirm』で送られ来たら
} elseif (isset($_POST['confirm']) && $_POST['confirm']) {
  // validation開始
  // お名前
  if (!$_POST['name']) {
    $err_mesg[] = 'お名前を入力してください。';
  } elseif (mb_strlen($_POST['name']) > 100) {
    $err_mesg[] = 'お名前は100文字以内で入力してください。';
  }
  // セッションに格納
  $_SESSION['name'] = htmlspecialchars($_POST['name'], ENT_QUOTES);

  // Eメールアドレス
  if (!$_POST['contact_email']) {
    $err_mesg[] = 'Eメールアドレスを入力してください。';
  } elseif (mb_strlen($_POST['contact_email']) > 200) {
    $err_mesg[] = 'Eメールアドレスは200文字以内で入力してください。';
  } elseif (!filter_var($_POST['contact_email'], FILTER_VALIDATE_EMAIL)) {
    $err_mesg[] = '入力されたEメールアドレスは不正です。';
  }
  // セッションに格納
  $_SESSION['contact_email'] = htmlspecialchars($_POST['contact_email'], ENT_QUOTES);

  // お問合せ
  if (!$_POST['mesg']) {
    $err_mesg[] = 'お問合せ内容を入力してください。';
  } elseif (mb_strlen($_POST['name']) > 1000) {
    $err_mesg[] = 'お名前は1000文字以内で入力してください。';
  }
  // セッションに格納
  $_SESSION['mesg'] = htmlspecialchars($_POST['mesg'], ENT_QUOTES);

  // 行き先の振り分け
  if ($err_mesg) {
    // エラーがあったらinputつまり、フォームの画面へ振り分けられる。
    // フォーム入力画面への符号をここで格納する。
    $toward = 'input';
  } else {
    // validationが正常に済めば、確認画面へ振り分けられる。
    // ここにCSRF対策の合言葉（乱数）を忍び込ませる処理を追加する。
    // 確が画面の送信ボタンを押す際にサーバーへ向かって発する合言葉をここで変数に格納する。
    $token = bin2hex(random_bytes(32));
    // サーバー側に保存させておく合言葉を先ほどの変数を使って格納する。
    $_SESSION['token'] = $token;
    // 確認画面への符号をここで格納する。
    $toward = 'confirm';
  }

  // POSTに『send』で送られ来たら
} elseif (isset($_POST['send']) && $_POST['send']) {
  // そもそもトークンがなんらかの理由で無かった時、セッションにEメール情報が保存されなかった時
  // エラーを返す。
  if (!$_POST['token'] || !$_SESSION['token'] || !$_SESSION['contact_email']) {
    $err_mesg = 'CSRFなどセキュリティ上、不正な処理を感ししました。最初から処理をやり直してください。';
    // この作業で入力されたセッション情報を初期化し、
    $_SESSION['name'] = '';
    $_SESSION['contact_email'] = '';
    $_SESSION['mesg'] = '';
    // フォーム入力画面へ差し戻す符をを発行する。
    $toward = 'input';

    // 送信ボタンを押されてインスタンスと伴に合言葉がやってくる。
    // サーバーに保ししておいた合言葉とここで符合させる。
  } elseif ($_POST['token'] != $_SESSION['token']) {
    // エラーで警告し、
    $err_mesg = 'CSRFなどセキュリティ上、不正な処理を感ししました。最初から処理をやり直してください。';
    // この作業で入力されたセッション情報を初期化し、
    $_SESSION['name'] = '';
    $_SESSION['contact_email'] = '';
    $_SESSION['mesg'] = '';
    // フォーム入力画面へ差し戻す符をを発行する。
    $toward = 'input';
  } else {
    $message = "お問合せを受け付けました。\r\n"
      . "お名前: " . $_SESSION['name'] . "\r\n"
      . "Eメールアドレス: " . $_SESSION['contact_email'] . "\r\n"
      . "お問合せ内容:\r\n"
      . preg_replace("/\r\n|\r|\n/", "\r\n", $_SESSION['mesg']);
    mail($_SESSION['contact_email'], 'お問合せを受け付けました。', $message);
    mail('studio.quad9@gmail.com', "{$_SESSION['name']}様からお問合せ受信の件", $message);
    // お問合せで使ったセッションを解放する。もちろんEmailのセッションは残さないといけない。
    $_SESSION['name'] = '';
    $_SESSION['contact_email'] = '';
    $_SESSION['mesg'] = '';
    $toward = 'send';
  }
  // GETで来た時の初回表示
} else {
  $_SESSION['name'] = '';
  $_SESSION['contact_email'] = '';
  $_SESSION['mesg'] = '';
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>お問合せ</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
  <div class="container">
    <div class="mx-auto" style="margin-top:50px; width: 400px;">
      <?php
      // 要確認　HTML内のPHPコードのエスケープのやり方を確認する。
      if ($err_mesg) {
        echo '<div class="alert alert-danger" role="alert">';
        echo implode('<br>', $err_mesg);
        echo '</div>';
      }
      ?>
      <?php if ($toward == 'input') { ?>
        <h3 style="text-align: center;">お問合せ</h3>
        <!-- 入力画面 -->
        <form action="./contact.php" method="POST">
          <div class="mb-3">
            <label class="form-label">お名前</label>
            <input class="form-control" type="text" name="name" value="<? echo $_SESSION['name'] ?>">
          </div>
          <div class="mb-3">
            <label class="form-label">Eメールアドレス</label>
            <input class="form-control" type="email" name="contact_email" value="<? echo $_SESSION['contact_email'] ?>">
          </div>
          <div class="mb-3">
            <label class="form-label">お問合せ内容</label>
            <textarea class="form-control" cols="40" rows="8" name="mesg"><? echo $_SESSION['mesg'] ?></textarea>
          </div>
          <div class="submit">
            <button type="submit" class="btn btn-primary btn-sm" name="confirm" value="確認">確認</button>
          </div>
        </form>
        <p style="margin-top: 20px; size: 0.8em; text-align: center;"><a href="./member.php" style="text-decoration: none; color:cornflowerblue">登録メンバーページ</a>へ戻る</p>
      <?php } elseif ($toward == 'confirm') { ?>

        <!-- 確認画面 -->
        <form action="./contact.php" method="POST">
          <!-- 合言葉を忍ばせる。 -->
          <input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?>">
          <p>入力の確認をお願いします。</p>
          <label class="form-label">お名前： <?php echo $_SESSION['name'] ?></label><br>
          <label class="form-label">Eメールアドレス： <?php echo $_SESSION['contact_email'] ?></label><br>
          <!-- PHPのコードとして渡ってきた値の改行をHTMLのbrタグに変換する関数を充てる。 -->
          <label class="form-label">お問合せ内容： <?php echo nl2br($_SESSION['mesg']) ?></label>
          <div class="submit">
            <button type="submit" class="btn btn-primary btn-sm" name="back" value="戻る">戻る</button>
            <button type="submit" class="btn btn-primary btn-sm" name="send" value="送信">送信</button>
          </div>
        </form>
      <?php } else { ?>
        <p style="text-align: center;">お問合せを送信しました。</p>
        <p style="margin-top: 20px; size: 0.8em; text-align: center;"><a href="./member.php" style="text-decoration: none; color:cornflowerblue">メンバーページ</a>へ移動する</p>
      <?php } ?>
    </div>
  </div>
</body>

</html>