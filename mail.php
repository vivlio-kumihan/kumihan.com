<?php
// さくらのレンタルサーバーでは日本語大丈夫だったが、一応記載しておく。
mb_language("ja");
mb_internal_encoding("UTF-8");
mail('studio.quad9@gmail.com', 'テストで送信する', "こんにちは、こんにちは、こんにちは。\r\nこんにちは、
世界");
// 『bin2hex()』=> 16進数に変換する。
// 『random_bytes()』=> 乱数を生成する。
// $pass = bin2hex(random_bytes(5));
?>
