<?php
// DB
define('DNS', 'mysql:dbname=quad9_db;host=mysql57.quad9.sakura.ne.jp;charset=utf8');
define('DB_USER', 'quad9');
define('DB_PASSWORD', 'Bf109tugumi');
define('SITE_URL', 'http://quad9.sakura.ne.jp/www/');

// error　よく分ってないのでエスケーししておく。
// 開発時の設定。すべてのエラーを出力する。
// 公開時には引数を『0』に設定する。
// error_reporting(E_ALL & ~E_NOTICE);
// session_set_cookie_params(1440, '/');
?>