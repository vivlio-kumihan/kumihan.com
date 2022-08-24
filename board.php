<?php
require_once('../tmp/conf.php');
require_once('./lib/function.php');

// セッション開始
session_start();
// セッションの切符も持っていない訪問者にログインページへリダイレクト処理。
if (!$_SESSION['email']) {
  $host = $_SERVER['HTTP_HOST'];
  $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
  header("Location: //$host$uri/login.php");
  exit;
}

$err_mesg = array();
$data = array();
// DB接続に係る変数を生成
// $db_ins = get_db_connect();
try {
  $dsn = DNS;
  $user = DB_USER;
  $pwd = DB_PASSWORD;
  $dbh = new PDO($dsn, $user, $pwd);
} catch (PDOException $e) {
  echo ("接続に失敗しました。".$e->getMessage());
  die();  
}
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = get_post('name');
  $comment = get_post('comment');
  
  // 値のvalidation
  if (!check_words($name, 50)) {
    $err_mesg[] = '氏名欄を修正してください。';
  }
  if (!check_words($comment, 2000)) {
    $err_mesg[] = 'コメント欄を修正してください。';
  }
  // DBにコメントを追加していく。
  if (count($err_mesg) === 0) {
    insert_comment($dbh, $name, $comment);
  }
}
      
// HTMLへ渡すために、
// DBにスプールされた全データを連想配列に変換して格納する関数。
$data = all_select_comments($dbh);

// ここで生成されて$data、
// つまり、現状のDBの状態を連想配列に変換した変数が
// 『view.php』を含める・組み込む（include）と命令することで、
// インスタンが該当ファイルへ渡っていく。素敵だ。
include_once('./board_view.php');

