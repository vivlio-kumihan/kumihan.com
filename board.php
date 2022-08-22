<?php
require_once('./lib/function.php');

$errs = [];
$data = [];
// DB接続に係る変数を生成
$dsn = "mysql:dbname=quad9_db;host=mysql57.quad9.sakura.ne.jp;charset=utf8";
$user = "quad9";
$pwd = "";
$dbh = new PDO($dsn, $user, $pwd);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = get_post('name');
  $comment = get_post('comment');
  // 値のvalidation
  if (!check_words($name, 50)) {
    $errs[] = '氏名欄を修正してください。';
  }
  // 値のvalidation
  if (!check_words($comment, 2000)) {
    $errs[] = 'コメント欄を修正してください。';
  }
  // DBにコメントを追加していく。
  if (count($errs) === 0) {
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
include_once('view.php');

