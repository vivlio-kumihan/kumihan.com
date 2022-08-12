<?php







// phpinfo()
?>




<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>確認画面</title>
</head>

<body>
  <?php if ($_POST) { ?>
    <!-- フォームからの入力確認画面 -->
    <form action="./contactform.php" method="post">
      名前<?php echo $_POST['fullname'] ?><br>
      Eメール<?php echo $_POST['email'] ?><br>
      メッセージ<?php echo nl2br($_POST['message']) ?><br>
      <input type="submit" name="back" value="戻る" />
      <input type="submit" name="send" value="送信" />
    </form>
  <?php } else { ?>
    <!-- GETの時のフォーム -->
    <!-- フォームへの入力画面 -->
    <!-- デフォルトではGET -->
    <form action="./contactform.php" method="post">
      名前<input type="text" name="fullname" value=""><br>
      Eメール<input type="email" name="email" value=""><br>
      お問合せ内容 <br>
      <textarea name="message" id="" cols="40" rows="8"></textarea><br>
      <input type="submit" name="confirm" value="送信">
    </form>
  <?php } ?>
  <?php print_r($_POST); ?>
</body>

</html>