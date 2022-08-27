<?php
// // 最初の設定
// // セッションの切符を持っている。
// session_start();
// // セッションの切符も持っていない訪問者にログインページへリダイレクト処理。
// if (!$_SESSION['email']) {
//   $host = $_SERVER['HTTP_HOST'];
//   $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
//   header("Location: //$host$uri/login.php");
//   exit;
// }
// // SESSIONに空の配列を渡して初期化してログアウトする。
// $_SESSION = array();


// ログインページは不要、ログインページに遷移させてメッセージを出す仕様に変えてはどうか？
// でも、こんな書き方に疑問。
// セッション切符を持っていることが前提
// セッション開始
session_start();
// セッションの切符も持っていない訪問者にログインページへリダイレクト処理。

if (!$_SESSION['email']) {
  // ログインページでダイアロを出せないか？
  $host = $_SERVER['HTTP_HOST'];
  $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
  header("Location: //$host$uri/login.php");
  exit;
} else {
  // if ($mesg) {
  //   echo '<div class="alert alert-danger" role="alert">';
  //   echo implode('<br>', $mesg);
  //   echo '</div>';
  // }
  // ログインページでダイアロを出せないか？
  // SESSIONに空の配列を渡して初期化してログアウトする。
  $_SESSION = array();
  $host = $_SERVER['HTTP_HOST'];
  $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
  header("Location: //$host$uri/login.php");
}
?>

<!-- <!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ログアウト</title>
  <style>
    .submit {
      text-align: center;
    }
  </style>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
  <div class="container">
    <div class="mx-auto" style="margin-top:150px; width: 450px;">
      <h3>ログアウトしました。</h3>
      <a href="./login.php">ログインページへ</a>
    </div>
  </div>
</body>

</html> -->