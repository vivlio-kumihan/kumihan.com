

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>kumihanBBS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="./assets/css/view.css">
</head>

<body>
  <div class="container">
    <div class="row">
      <div class="col-6">
        <h1>kumihanBBS</h1>
        <!-- エラーがあった場合に警告を出現させる仕組みは『if』。 -->
        <?php if (count($errs)) {
          foreach ($errs as $err) {
            echo '<p style="color: red">' . $err . '</p>';
          }
        } ?>
        <span class="write-button">
          <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modal" style="color: white">書き込む</button>
        </span>
        <div id="modal" class="modal fade" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">kumihanBBS</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <nav>
                  <!-- 書き込み -->
                  <form class="BBS-input-form" action="./board.php" method="POST">
                    <div class="mb-3">
                      <label class="form-label">お名前</label>
                      <input class="form-control" type="text" name="name" value="<?php echo $_POST['name'] ?>">
                    </div>
                    <div class="mb-3">
                      <label class="form-label">メッセージ</label>
                      <textarea class="form-control" name="comment" rows="12" cols="40"><?php echo $_POST['comment'] ?></textarea>
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
            <?php if (count($data)) :
              foreach (array_reverse($data) as $row) : ?>
                <li class="timeline-item mb-5">
                  <div class="user"><?php echo html_escape($row['name']); ?></div>
                  <p class="text-muted mb-2 fw-bold"><?php echo $row['created']; ?></p>
                  <p class="text-muted"><?php echo nl2br(html_escape($row['comment'])); ?></p>
                </li>
            <?php endforeach;
            endif; ?>
          </ul>
        </section>
      </div>
    </div>
  </div>
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