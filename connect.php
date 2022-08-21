<?php
$dsn = "mysql:dbname=quad9_db;host=mysql57.quad9.sakura.ne.jp;charset=utf8";
$user = "quad9";
$password = "Bf109tugumi";

try {
    $dbh = new PDO($dsn, $user, $password);
    $dbh -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo '接続に成功しました。';
} catch(PDOException $e) {
    print("接続に失敗しました。".$e -> getMessage());
    die();
}