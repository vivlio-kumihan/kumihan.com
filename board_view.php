<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>kumihanBBS</title>
  <link rel="stylesheet" href="./assets/css/view.css">
  <link rel="stylesheet" href="assets/css/fonts.css" />
  <link rel="stylesheet" href="./assets/css/fontawesome-all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="./assets/css/another-page.css">
</head>

<body>
  <header>
    <div class="collapse bg-dark" id="navbarHeader">
      <div class="container">
        <div class="row">
          <div class="col-sm-8 col-md-7 py-4">
            <h4 class="text-white">About</h4>
            <p class="text-muted">Add some information about the album below, the author, or any other background context. Make it a few sentences long so folks can pick up some informative tidbits. Then, link them off to some social networking sites or contact information.</p>
          </div>
          <div class="col-sm-4 offset-md-1 py-4">
            <ul class="list-unstyled">
              <li class="header-menu"><a href="./index.php" style="a:hover{color: black} ">Home</a></li>
              <li class="header-menu"><a href="./member.php">Member</a></li>
              <li class="header-menu"><a href="./photo.php">Photo</a></li>
              <li class="header-menu"><a href="./book.php">Book</a></li>
              <li class="header-menu"><a href="./blog.php" target="blank">Blog</a></li>
              <li class="header-menu" style="margin-top: 10px;"><a href="./contact.php">Contact</a></li>
              <li class="header-menu"><a href="./register.php">SignUp</a></li>
              <li class="header-menu"><a href="./login.php">LogIn</a></li>
              <li class="header-menu"><a href="./logout.php">LogOut</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="navbar navbar-dark bg-dark shadow-sm">
      <div class="container">
        <a href="#" class="navbar-brand d-flex align-items-center">
          <i class="fa-solid fa-comments" style="margin-right: 8px; color: whitesmoke;"></i>
          <strong>kumihanBBS</strong>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
      </div>
    </div>
  </header>

  <div class="container">
    <div class="row">
      <?php
      $ua = $_SERVER['HTTP_USER_AGENT'];
      if ((strpos($ua, 'Android') !== false) && (strpos($ua, 'Mobile') !== false) || (strpos($ua, 'iPhone') !== false) || (strpos($ua, 'Windows Phone') !== false)) {
      ?>
        <!-- スマホの場合に読み込むソースを記述 -->
        <div class="col-12" style="margin-top: 30px;">
      <?php } elseif ((strpos($ua, 'Android') !== false) || (strpos($ua, 'iPad') !== false)) { ?>
        <!-- タブレットの場合に読み込むソースを記述 -->
        <div class="col-8" style="margin-top: 30px;">
      <?php } else { ?>
        <!-- PCの場合に読み込むソースを記述 -->
        <div class="col-6" style="margin-top: 30px;">
      <?php } ?>
        <h1 class="page-title">kumihanBBS</h1>
        <!-- エラーがあった場合に警告を出現させる仕組みは『if』。 -->
        <?php if (count($err_mesg)) {
          foreach ($err_mesg as $err) {
            echo '<p style="color: red">' . $err . '</p>';
          }
        } ?>
        <span class="write-button">
          <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal" style="color: white">投稿へ</button>
        </span>
        <div id="modal" class="modal fade" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title"">kumihanBBS</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <nav>
                  <!-- 書き込み -->
                  <form class="BBS-input-form" action="./board.php" method="POST">
                    <div class="mb-3">
                      <label class="form-label">お名前</label>
                      <input class="form-control" type="text" name="name" value="">
                    </div>
                    <div class="mb-3">
                      <label class="form-label">メッセージ</label>
                      <textarea class="form-control" name="comment" rows="12" cols="40"></textarea>
                    </div>
                    <div class="submit">
                      <button type="submit" class="btn btn-success btn-sm" value="送信">送信</button>
                    </div>
                  </form>
                </nav>
              </div>
            </div>
          </div>
        </div>
        <!-- 掲示板 -->
        <section>
          <ul class="timeline">
            <?php
            if (count($data)) {
              foreach (array_reverse($data) as $row) { ?>
                <li class="timeline-item mb-5">
                  <div class="user"><?php echo forxss($row['name']); ?></div>
                  <p class="text-muted mb-2 fw-bold" style="font-size: 0.85rem;"><?php echo $row['created']; ?></p>
                  <p class="text-muted"><?php echo nl2br(forxss($row['comment'])); ?></p>
                </li>
              <?php } ?>
            <?php } ?>
          </ul>
        </section>
      </div>
    </div>
  </div>

  <script src="https://kit.fontawesome.com/678cad97f5.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <script>
    var myModal = document.getElementById('Modal')
    var myInput = document.getElementById('Input')

    myModal.addEventListener('shown.bs.modal', function() {
      myInput.focus()
    })
  </script>
</body>

</html>