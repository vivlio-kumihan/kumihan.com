<?php
// POSTで受信したかどうかをトリガーにする方法
if ($_POST) {
  echo $_POST['push_btn'];
  echo "<br>";
  echo $_POST['name'];
  echo "<br>";
  echo $_POST['comment'];
}

// フォームのボタンを押したかどうかをトリガーにする方法
// いろいろある。
if ($_POST['push_btn']) {
  echo "受信した。";
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>確認する</title>
  <style>
    * {
      margin: 0;
      padding: 0;
    }

    .container {
      width: 100%;
      background-color: whitesmoke;
    }

    .bbs {
      margin: 0 auto;
      width: 500px;
    }

    article {
      margin-bottom: 15px;
    }

    .name_area {
      display: flex;
      align-items: center;
    }

    .user_name {
      margin-right: 20px;
    }

    .board span {
      font-size: 0.8rem;
      font-weight: 900;
    }

    textarea {
      width: 400px;
      margin-top: 20px;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="bbs">
      <h1>BBS</h1>
      <div class="board">
        <article>
          <div class="name_area">
            <span>名前：</span>
            <p class="user_name">takahiro</p>
            <span>時刻：</span>
            <time>:2022/8/16</time>
          </div>
          <p class="comment_area">手書きのテストです。</p>
        </article>
        <form class="form" method="POST">
          <div>
            <!-- ボタンを押すとvalueの値が送られるようだ。 -->
            <button type="submit" name="push_btn" value="pushed button">書き込む</button>
            <label>名前：</label>
            <input type="text" name="name" value="">
          </div>
          <div>
            <textarea name="comment" cols="30" rows="10"></textarea>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>

</html>