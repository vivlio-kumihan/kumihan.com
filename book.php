<?php
// セッション切符を持っていることが前提
// セッション開始
session_start();
// セッションの切符も持っていない訪問者にログインページへリダイレクト処理。
if (!$_SESSION['email']) {
  $host = $_SERVER['HTTP_HOST'];
  $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
  header("Location: //$host$uri/login.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Book</title>
</head>
<body>
  <h3>Book</h3>
  <p>こちらが<a href="./sample/index.html" target="”_blank”">WebPageのサンプルページ</a> です。<br>そして、 <a href="./vivliostyle-viewer-latest/viewer/#src=https://kumihan.com/sample/index.html&amp;bookMode=true" target="”_blank”">WebBookのサンプルページ</a> です。</p>
  
</body>
</html>