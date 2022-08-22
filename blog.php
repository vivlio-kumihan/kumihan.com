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
} else {
  header("Location: //vivlio-kumihan.github.io/practice-stroke/");
}
?>

<!-- <a href="https://vivlio-kumihan.github.io/practice-stroke/" target=”_blank”>Blog</a> -->