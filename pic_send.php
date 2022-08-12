<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>pic send</title>
</head>
<body>
  <h1>画像のアップロード</h1>
  <form action="./pic_receive.php" method="POST" enctype="multipart/form-data">
    <p><input type="file" name="img"></p>
    <p><input type="submit" value="送信"></p>
  </form>
</body>