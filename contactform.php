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
$mode = 'input';
if( isset($_POST['back']) && $_POST['back'] ){
  // 何もしない
} else if( isset($_POST['confirm']) && $_POST['confirm'] ){
  $_SESSION['fullname'] = $_POST['fullname'];
  $_SESSION['contact_email']    = $_POST['contact_email'];
  $_SESSION['message']  = $_POST['message'];
  $mode = 'confirm';
} else if( isset($_POST['send']) && $_POST['send'] ){
  // 送信ボタンを押したとき
  $message  = "お問い合わせを受け付けました \r\n"
            . "名前: " . $_SESSION['fullname'] . "\r\n"
            . "email: " . $_SESSION['contact_email'] . "\r\n"
            . "お問い合わせ内容:\r\n"
            . preg_replace("/\r\n|\r|\n/", "\r\n", $_SESSION['message']);
  mail($_SESSION['contact_email'],'お問い合わせありがとうございます',$message);
  mail('studio.quad9@gmail.com','お問い合わせありがとうございます',$message);
  $_SESSION = array();
  $mode = 'send';
} else {
  $_SESSION['fullname'] = "";
  $_SESSION['contact_email']    = "";
  $_SESSION['message']  = "";
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="utf-8">
	<title>お問い合わせフォーム</title>
</head>
<body>
  <?php if( $mode == 'input' ){ ?>
    <!-- 入力画面 -->
    <form action="./contactform.php" method="post">
      名前    <input type="text"    name="fullname" value="<?php echo $_SESSION['fullname'] ?>"><br>
      Eメール <input type="email"   name="contact_email"    value="<?php echo $_SESSION['contact_email'] ?>"><br>
      お問い合わせ内容<br>
      <textarea cols="40" rows="8" name="message"><?php echo $_SESSION['message'] ?></textarea><br>
      <input type="submit" name="confirm" value="確認" />
    </form>
  <?php } else if( $mode == 'confirm' ){ ?>
    <!-- 確認画面 -->
    <form action="./contactform.php" method="post">
      名前    <?php echo $_SESSION['fullname'] ?><br>
      Eメール <?php echo $_SESSION['contact_email'] ?><br>
      お問い合わせ内容<br>
      <?php echo nl2br($_SESSION['message']) ?><br>
      <input type="submit" name="back" value="戻る" />
      <input type="submit" name="send" value="送信" />
    </form>
  <?php } else { ?>
    <!-- 完了画面 -->
    送信しました。お問い合わせありがとうございました。<br>
  <?php } ?>
</body>
</html>


